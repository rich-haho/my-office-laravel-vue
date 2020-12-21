<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendStripeId extends Mailable
{
    use Queueable, SerializesModels;

    public $object;
    public $description;

    /**
     * Create a new notification instance.
     * @return void
     */
    public function __construct($object, $description)
    {
        $this->object = $object;
        $this->description = $description;
    }

    public function build()
    {
        return $this->subject($this->object)
                    ->markdown('emails.new_stripe_partner');
    }
}
