<?php

namespace App\Domains\Subscription\Repositories;

use App\Domains\Subscription\Models\PendingSubscriptionChange;

/**
 * @class PendingSubscriptionChangeRepository
 * @package App\Domains\Subscription\Repositories
 */
class PendingSubscriptionChangeRepository
{
    /**
     * @param $subscriptionId
     * @return PendingSubscriptionChange|null
     */
    public function findBySubscriptionId($subscriptionId): ?PendingSubscriptionChange
    {
        return PendingSubscriptionChange::where('subscription_id', $subscriptionId)
            ->where('status', 'pending')
            ->first();
    }

    /**
     * @param PendingSubscriptionChange $pendingChange
     * @return PendingSubscriptionChange
     */
    public function save(PendingSubscriptionChange $pendingChange): PendingSubscriptionChange
    {
        $pendingChange->save();
        return $pendingChange;
    }

}
