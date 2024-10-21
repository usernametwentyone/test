<?php

namespace App\Domains\Subscription\Services;

use App\Domains\Subscription\Models\PendingSubscriptionChange;
use App\Domains\Subscription\Models\Subscription;
use App\Domains\Subscription\Repositories\PendingSubscriptionChangeRepository;
use App\Domains\Subscription\Repositories\SubscriptionPeriodRepository;
use App\Domains\Subscription\Repositories\SubscriptionRepository;
use Illuminate\Support\Carbon;

/**
 * @class SubscriptionService
 * @package App\Domains\Subscription\Services
 *
 * @property SubscriptionRepository $subscriptionRepo
 * @property PendingSubscriptionChangeRepository $pendingChangeRepo
 */
class SubscriptionService
{
    /**
     * @var SubscriptionRepository
     */
    protected SubscriptionRepository $subscriptionRepo;
    /**
     * @var PendingSubscriptionChangeRepository
     */
    protected PendingSubscriptionChangeRepository $pendingChangeRepo;

    protected SubscriptionPeriodRepository $subscriptionPeriodRepo;

    /**
     * @param SubscriptionRepository $subscriptionRepo
     * @param PendingSubscriptionChangeRepository $pendingChangeRepo
     * @param SubscriptionPeriodRepository $subscriptionPeriodRepo
     */
    public function __construct(
        SubscriptionRepository              $subscriptionRepo,
        PendingSubscriptionChangeRepository $pendingChangeRepo,
        SubscriptionPeriodRepository        $subscriptionPeriodRepo
    )
    {
        $this->subscriptionRepo = $subscriptionRepo;
        $this->pendingChangeRepo = $pendingChangeRepo;
        $this->subscriptionPeriodRepo = $subscriptionPeriodRepo;
    }

    /**
     * @param $userId
     * @return Subscription|null
     */
    public function getCurrentSubscription($userId): ?Subscription
    {
        return $this->subscriptionRepo->findByUserId($userId);
    }

    /**
     * @param $subscriptionId
     * @return PendingSubscriptionChange|null
     */
    public function getPendingChange($subscriptionId): ?PendingSubscriptionChange
    {
        return $this->pendingChangeRepo->findBySubscriptionId($subscriptionId);
    }

    /**
     * @return void
     */
    public function applyPendingChanges(): void
    {
        $pendingChanges = PendingSubscriptionChange::where('status', 'pending')
            ->where('change_effective_date', '<=', Carbon::today())
            ->get();

        foreach ($pendingChanges as $change) {
            $subscription = $change->subscription;

            $this->subscriptionPeriodRepo->create([
                'subscription_id' => $subscription->id,
                'plan_id' => $subscription->plan_id,
                'number_of_users' => $subscription->number_of_users,
                'payment_frequency' => $subscription->payment_frequency,
                'discount_percentage' => $subscription->discount_percentage,
                'total_cost' => $subscription->total_cost,
                'period_start' => Carbon::parse($subscription->current_period_end)->subMonths(
                    $subscription->payment_frequency === 'monthly' ? 1 : 12
                ),
                'period_end' => $subscription->current_period_end,
            ]);

            $subscription->plan_id = $change->new_plan_id;
            $subscription->number_of_users = $change->new_number_of_users;
            $subscription->payment_frequency = $change->new_payment_frequency;
            $subscription->discount_percentage = $change->new_discount_percentage;
            $subscription->total_cost = $change->new_total_cost;
            $subscription->current_period_end = Carbon::parse($subscription->current_period_end)
                ->addMonths($subscription->payment_frequency === 'monthly' ? 1 : 12);

            $this->subscriptionRepo->save($subscription);

            $change->status = 'applied';
            $this->pendingChangeRepo->save($change);
        }
    }
}
