<?php

namespace App\Domains\Subscription\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @class Subscription
 * @package App\Domains\Subscription\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $plan_id
 * @property int $number_of_users
 * @property string $payment_frequency
 * @property float $discount_percentage
 * @property float $total_cost
 * @property string $current_period_end
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Plan $plan
 * @property User $user
 * @property PendingSubscriptionChange $pendingChange
 */
class Subscription extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'number_of_users',
        'payment_frequency',
        'discount_percentage',
        'total_cost',
        'current_period_end',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function pendingChange(): HasOne
    {
        return $this->hasOne(PendingSubscriptionChange::class);
    }
}
