<?php

namespace App\Domains\Subscription\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PendingSubscriptionChange
 * @package App\Domains\Subscription\Models
 *
 * @property int $id
 * @property int $subscription_id
 * @property int $new_plan_id
 * @property int $new_number_of_users
 * @property string $new_payment_frequency
 * @property float $new_discount_percentage
 * @property float $new_total_cost
 * @property string $change_effective_date
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Subscription $subscription
 * @property Plan $newPlan
 */
class PendingSubscriptionChange extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'subscription_id',
        'new_plan_id',
        'new_number_of_users',
        'new_payment_frequency',
        'new_discount_percentage',
        'new_total_cost',
        'change_effective_date',
        'status',
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
    public function newPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'new_plan_id');
    }
}
