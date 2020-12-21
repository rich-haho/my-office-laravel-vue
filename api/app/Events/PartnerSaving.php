<?php

namespace App\Events;

use App\Models\Partner;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartnerSaving
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Partner
     */
    public $partner;

    /**
     * Create a new event instance.
     *
     * @param Partner $partner
     */
    public function __construct(Partner $partner)
    {
        $this->partner = $partner;
    }
}
