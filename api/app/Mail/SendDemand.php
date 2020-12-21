<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDemand extends Mailable
{
    use Queueable, SerializesModels;

    public $category;
    public $object;
    public $description;
    public $contact;
    public $from;
    public $replyToAddress;
    public $replyToName;

    /**
     * Create a new notification instance.
     * @return void
     */
    public function __construct($category, $object, $description, $contact, $user)
    {
        $this->category = $category;
        $this->object = $object;
        $this->description = $description;
        $this->contact = $contact;
        $this->replyToAddress = $user->email;
        $this->replyToName = $user->name;
    }

    public function build()
    {
        return $this->subject(__('mail.demand.subject', ['object' => $this->object]))
                    ->replyTo($this->replyToAddress, $this->replyToName)
                    ->markdown('emails.demand');
    }
}
