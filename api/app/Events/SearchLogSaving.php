<?php

namespace App\Events;

use App\Models\SearchLog;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SearchLogSaving
{
    use Dispatchable, SerializesModels;

    /**
     * @var SearchLog
     */
    public $searchLog;

    /**
     * Create a new event instance.
     *
     * @param SearchLog $searchLog
     */
    public function __construct(SearchLog $searchLog)
    {
        $this->searchLog = $searchLog;
    }
}
