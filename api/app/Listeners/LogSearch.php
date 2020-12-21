<?php

namespace App\Listeners;

use App\Events\ProductLogging;
use App\Events\ServiceLogging;
use App\Models\SearchLog;
use App\Models\Service;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogSearch
 * @package App\Listeners
 */
class LogSearch
{
    /**
     * Laravel User ID.
     *
     * @var int|string|null
     */
    private $userId;

    /**
     * Checking if user is an administrator on the back office.
     *
     * @var bool
     */
    private $isAdmin;

    /**
     * Log constructor.
     *
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->isAdmin = $authManager->guard('admin')->check();
        $this->userId = $authManager->guard('api')->id();
    }

    /**
     * Handle the event.
     *
     * Saving a SearchLog when triggered.
     *
     * @param  ProductLogging|ServiceLogging $event
     * @return Model|bool
     */
    public function handle($event)
    {
        // Getting the morphed model instance.
        $model = $event->model;
        // Getting the correct loggable type.
        $loggableType = get_class($model) === Service::class ? 'services' : 'products';
        // Checking if the current model has already been logged into the search log.
        $duplicates = SearchLog::where('loggable_id', $model->id)->where('loggable_type', $loggableType);

        // If model has already been registered in log search, we update its updated_at timestamp
        // in order to sort it in the frontend, then aborting.
        if ($duplicates->count() > 0) {
            $duplicate = $duplicates->get()->first();
            $duplicate->touch();
            return true;
        }

        // Aborting Event Handling if the user is an administrator, unauthenticated.
        if ($this->userId === null || $this->isAdmin === true) {
            return true;
        }

        // Instantiating a new search log entry.
        $log = new SearchLog([
            'user_id' => $this->userId,
        ]);

        // Syncing morphed model with the instantiated search log and saving it.
        return $model->searches()->save($log);
    }
}
