<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductLogging
{
    use Dispatchable, SerializesModels;

    /**
     * Instance of Product Model.
     * @var Product
     */
    public $model;

    /**
     * Create a new event instance.
     *
     * @param Product $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
}
