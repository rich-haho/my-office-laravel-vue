<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendFmServicesRequest;
use App\Mail\SendFmServices;
use Illuminate\Auth\AuthManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Mail\Mailer;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class FmServicesController extends Controller
{
    /**
     * Laravel Response Factory implementation.
     *
     * @var ResponseFactory
     */
    public $response;

    /**
     * Laravel Auth implementation.
     *
     * @var AuthManager
     */
    public $auth;

    /**
     * Mail Sender implementation.
     *
     * @var Mailer
     */
    public $mail;


    /**
     * Laravel configuration repository implementation.
     *
     * @var Config
     */
    public $config;


    public function __construct(
        ResponseFactory $responseFactory,
        AuthManager $authManager,
        Mailer $mailSender,
        Config $config
    ) {
        $this->response = $responseFactory;
        $this->auth = $authManager;
        $this->mail = $mailSender;
        $this->config = $config;
    }

    public function send(SendFmServicesRequest $request)
    {
        $subject = $request->subject;
        $description = $request->description;
        $contact = $request->contact;
        $building = $request->building;
        $emailRedirect = $this->config->get('zenoffice.mails.fm_services');

        $user = $this->auth->guard('api')->user();

        $fmServicesNotification = new SendFmServices(
            $subject,
            $description,
            $contact,
            $building,
            $user
        );

        try {
            $this->mail->to($emailRedirect)->send($fmServicesNotification);
        } catch (\Exception $exception) {
            throw new UnprocessableEntityHttpException('Envoi impossible, veuillez contacter un administrateur');
        }

        return $this->response->json([], 204);
    }
}
