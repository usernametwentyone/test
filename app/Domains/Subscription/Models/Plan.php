<?php

namespace App\Domains\Subscription\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @class Plan
 * @package App\Domains\Subscription\Models
 *
 * @property int $id
 * @property string $name
 * @property float $price_per_user_per_month
 * @property string $created_at
 * @property string $updated_at
 */
class Plan extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'price_per_user_per_month'
    ];
}
