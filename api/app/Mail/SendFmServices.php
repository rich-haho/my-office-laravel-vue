<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFmServices extends Mailable
{
    use Queueable, SerializesModels;

    public $object;
    public $description;
    public $contact;
    public $from;
    public $replyToAddress;
    public $replyToName;
    public $building;

    public function __construct($object, $description, $contact, $building, $user)
    {
        $this->object = $object;
        $this->description = $description;
        $this->contact = $contact;
        $this->building = $building;
        $this->replyToAddress = $user->email;
        $this->replyToName = $user->name;
    }

    public function build()
    {
        return $this->subject(__('mail.fm_services.subject', ['object' => $this->object]))
                    ->replyTo($this->replyToAddress, $this->replyToName)
                    ->markdown('emails.fm_services');
    }
}
