<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendDemandRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Auth;
use Mail;
use App\Mail\SendDemand;

class DemandController extends Controller
{
    public function send(SendDemandRequest $request)
    {
        $subject = $request->subject;
        $description = $request->description;

        $category = $request->category;
        $contact = $request->contact;
        $demand_email = env('MAIL_TO_DEMAND');
        $user = Auth::user();
        Mail::to($demand_email)->send(new SendDemand($category, $subject, $description, $contact, $user));
        return response()->json('success');
    }
}
