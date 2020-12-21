<?php

namespace App\Listeners;

use App\Events\SearchLogSaving;
use Illuminate\Auth\AuthManager;
use Illuminate\Config\Repository as ConfigRepository;

/**
 * Class LimitLogSearch
 * @package App\Listeners
 */
class LimitLogSearch
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
     * Configuration variable containing the search logs entry limit.
     *
     * @var int
     */
    private $logLimit;

    /**
     * Log constructor.
     *
     * @param AuthManager $authManager
     * @param ConfigRepository $configRepository
     */
    public function __construct(AuthManager $authManager, ConfigRepository $configRepository)
    {
        $this->isAdmin = $authManager->guard('admin')->check();
        $this->userId = $authManager->guard('api')->id();
        $this->logLimit = $configRepository->get('zenoffice.search.logs.limit');
    }

    /**
     * Handle the event.
     *
     * Remove oldest search logs if we reached the maximum threshold of search logs
     * for the current user.
     *
     * @param SearchLogSaving $event
     * @return bool
     */
    public function handle(SearchLogSaving $event)
    {
        // Instance of SearchLog Model needed to limit users search logs.
        $search = $event->searchLog;
        // Getting a Query Builder instance from the Model instead of using directly the model.
        $searches = $search->newModelQuery()->where('user_id', $this->userId);
        // Search Count will be used to skip and limit users search logs.
        $searchesCount = $searches->count();

        // Aborting Event Handling if the user is an administrator, or unauthenticated
        // Search count should also be inferior to log limit config in order to continue the event.
        if ($this->userId === null || $this->isAdmin === true || $searchesCount <= $this->logLimit) {
            return true;
        }

        // Database limit calculation.
        $limit = ($this->logLimit);

        // Getting the oldest searches ids from the database, based on their created_at timestamp.
        $searchesIds = $searches
            ->orderBy('created_at', 'desc')
            ->skip($limit)
            ->take($searchesCount - $limit)
            ->pluck('id');

        // Deleting oldest searches and maintaining only 5 search logs.
        $search->destroy($searchesIds);

        return true;
    }
}
