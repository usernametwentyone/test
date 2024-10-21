<?php

namespace App\Domains\Subscription\Services;

use App\Domains\Subscription\Models\PendingSubscriptionChange;
use App\Domains\Subscription\Models\Plan;
use App\Domains\Subscription\Repositories\PendingSubscriptionChangeRepository;
use App\Domains\Subscription\Validators\SubscriptionValidator;
use Illuminate\Support\Carbon;
use RuntimeException;

/**
 * @class SubscriptionChangeService
 * @package App\Domains\Subscription\Services
 *
 * @property PendingSubscriptionChangeRepository $pendingChangeRepo
 * @property SubscriptionValidator $validator
 */
class SubscriptionChangeService
{
    /**
     * @var PendingSubscriptionChangeRepository
     */
    protected PendingSubscriptionChangeRepository $pendingChangeRepo;
    /**
     * @var SubscriptionValidator
     */
    protected SubscriptionValidator $validator;

    /**
     * @param PendingSubscriptionChangeRepository $pendingChangeRepo
     * @param SubscriptionValidator $validator
     */
    public function __construct(
        PendingSubscriptionChangeRepository $pendingChangeRepo,
        SubscriptionValidator               $validator
    )
    {
        $this->pendingChangeRepo = $pendingChangeRepo;
        $this->validator = $validator;
    }

    /**
     * @param $subscription
     * @param $data
     * @return PendingSubscriptionChange
     */
    public function changeSubscription($subscription, $data): PendingSubscriptionChange
    {
        $this->validator->validate($data);

        $pendingChange = $this->pendingChangeRepo->findBySubscriptionId($subscription->id);

        if ($pendingChange) {
            throw new RuntimeException('There is already a pending change for this subscription.');
        }

        $changeEffectiveDate = Carbon::parse($subscription->current_period_end)->addDay();

        $newTotalCost = $this->calculateTotalCost($data);

        $pendingChange = new PendingSubscriptionChange([
            'subscription_id' => $subscription->id,
            'new_plan_id' => $data['plan_id'],
            'new_number_of_users' => $data['number_of_users'],
            'new_payment_frequency' => $data['payment_frequency'],
            'new_discount_percentage' => $data['discount_percentage'],
            'new_total_cost' => $newTotalCost,
            'change_effective_date' => $changeEffectiveDate,
            'status' => 'pending',
        ]);

        $this->pendingChangeRepo->save($pendingChange);

        return $pendingChange;
    }

    /**
     * @param $data
     * @return float|int
     */
    protected function calculateTotalCost($data): float|int
    {
        $plan = Plan::find($data['plan_id']);
        $pricePerUser = $plan->price_per_user_per_month;
        $numberOfUsers = $data['number_of_users'];

        if ($data['payment_frequency'] === 'yearly') {
            $totalCost = $numberOfUsers * $pricePerUser * 12 * 0.8;
        } else {
            $totalCost = $numberOfUsers * $pricePerUser;
        }

        return $totalCost;
    }

    /**
     * @param $pendingChange
     * @return void
     */
    public function cancelPendingChange($pendingChange): void
    {
        $pendingChange->status = 'cancelled';
        $this->pendingChangeRepo->save($pendingChange);
    }
}
