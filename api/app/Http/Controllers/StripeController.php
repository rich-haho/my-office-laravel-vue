<?php

namespace App\Http\Controllers;

use App\Enum\BookingStatus;
use App\Mail\SendStripeId;
use App\Models\Booking;
use App\Models\Currency;
use App\Models\SlotBooking;
use App\Notifications\NewBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeController extends Controller
{
    /**
     * Handle invoice payment succeeded.
     *
     * @param Request $request
     * @param string $currency
     * @return JsonResponse
     */
    public function handleWebhook(Request $request, $currency)
    {
        /**
         * @var Currency
         */
        $currency = Currency::where('code', $currency)->first();
        Stripe::setApiKey($currency->stripe_sk);

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = $currency->stripe_whsec;

        /**
         * @var string
         */
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        $event = null;

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            abort(400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            abort(400);
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            list($userId, $productId, $partnerId, $manualProduct, $time) = explode('_', $session->client_reference_id);

            $product = Booking\Product::create([
                'name'          => $session->display_items[0]->custom->name,
                'product_id'    => $productId,
                'description'   => $session->display_items[0]->custom->description,
                'date_slot'     => $session->metadata->date,
                'start_time'    => $session->metadata->start_time,
                'end_time'      => $session->metadata->end_time,
                'price'         => $session->display_items[0]->amount / 100,
                'currency'      => $session->display_items[0]->currency,
                'image'         => $session->display_items[0]->custom->images ?
                    $session->display_items[0]->custom->images[0] : ''
            ]);

            $booking = Booking::create([
                'user_id'       => $userId,
                'product_id'    => $product->id,
                'partner_id'    => $partnerId,
                'site_id'       => $session->metadata->site_id,
                'date'          => date('Y-m-d H:i:s', intval($time)),
                'quantity'      => $session->display_items[0]->quantity,
                'manual_product' => $manualProduct === 'M',
                'status'        => $manualProduct === 'M' ? BookingStatus::prepaid() : BookingStatus::paid(),
            ]);

            if ($session->metadata->slot_id !== null) {
                $slotBooking = SlotBooking::create([
                    'booking_id'    => $booking->id,
                    'slot_id'       => $session->metadata->slot_id,
                    'quantity'      => $session->display_items[0]->quantity,
                    'date'          => $session->metadata->date,
                    'start_time'    => $session->metadata->start_time,
                    'end_time'      => $session->metadata->end_time
                ]);
            }

            // Notify the partner
            $partner = $booking->partner;
            Notification::send($partner, new NewBooking($booking));
        }

        return response()->json([]);
    }

    public function connectStripePartner(Request $request, $currency)
    {
        /**
         * @var Currency
         */
        $currency = Currency::where('code', $currency)->first();
        Stripe::setApiKey($currency->stripe_sk);

        try {
            $response = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->code,
            ]);
            $description = 'Un nouveau partenaire a connecté son
         compte Stripe au vôtre. Voici son id : ' . $response->toArray()['stripe_user_id'];
        } catch (\Exception $e) {
            $description = 'Un nouveau partenaire a tenté de connecter son
         compte Stripe au vôtre. Une erreur est survenue : ' . $e->getMessage();
        }

        $subject = 'Nouveau partenaire Stripe';
        $sendTo = env('MAIL_TO_DEMAND');
        Mail::to($sendTo)->send(new SendStripeId($subject, $description));

        return Redirect::to(env('PLATFORM_URL') . '/admin/users/login');
    }
}
