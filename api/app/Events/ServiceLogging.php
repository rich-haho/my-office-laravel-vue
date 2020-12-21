<?php

namespace App\Events;

use App\Models\Service;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceLogging
{
    use Dispatchable, SerializesModels;

    /**
     * Instance of Service Model.
     *
     * @var Service
     */
    public $model;

    /**
     * Create a new event instance.
     *
     * @param Service $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
}
