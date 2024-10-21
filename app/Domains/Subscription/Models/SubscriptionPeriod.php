<?php

namespace App\Domains\Subscription\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @class SubscriptionPeriod
 * @package App\Domains\Subscription\Models
 *
 * @property int $id
 * @property int $subscription_id
 * @property int $plan_id
 * @property int $number_of_users
 * @property string $payment_frequency
 * @property float $discount_percentage
 * @property float $total_cost
 * @property string $period_start
 * @property string $period_end
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Subscription $subscription
 * @property Plan $plan
 */
class SubscriptionPeriod extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'subscription_id',
        'plan_id',
        'number_of_users',
        'payment_frequency',
        'discount_percentage',
        'total_cost',
        'period_start',
        'period_end',
    ];

    /**
     * @return BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
