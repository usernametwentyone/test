<?php

namespace App\Domains\Subscription\Repositories;

use App\Domains\Subscription\Models\Subscription;

/**
 * @class SubscriptionRepository
 * @package App\Domains\Subscription\Repositories
 */
class SubscriptionRepository
{
    /**
     * @param $userId
     * @return Subscription
     */
    public function findByUserId($userId): Subscription
    {
        return Subscription::where('user_id', $userId)->first();
    }

    /**
     * @param Subscription $subscription
     * @return Subscription
     */
    public function save(Subscription $subscription): Subscription
    {
        $subscription->save();
        return $subscription;
    }

}
