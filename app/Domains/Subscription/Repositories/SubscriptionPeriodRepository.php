<?php

namespace App\Domains\Subscription\Repositories;

use App\Domains\Subscription\Models\SubscriptionPeriod;

/**
 * @class SubscriptionPeriodRepository
 * @package App\Domains\Subscription\Repositories
 */
class SubscriptionPeriodRepository
{
    /**
     * @param array $data
     * @return SubscriptionPeriod
     */
    public function create(array $data): SubscriptionPeriod
    {
        return SubscriptionPeriod::create($data);
    }

    /**
     * @param SubscriptionPeriod $subscriptionPeriod
     * @return SubscriptionPeriod
     */
    public function save(SubscriptionPeriod $subscriptionPeriod): SubscriptionPeriod
    {
        $subscriptionPeriod->save();
        return $subscriptionPeriod;
    }
}

