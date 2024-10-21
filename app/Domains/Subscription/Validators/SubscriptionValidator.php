<?php

namespace App\Domains\Subscription\Validators;

use Illuminate\Support\Facades\Validator;
use RuntimeException;

/**
 * @class SubscriptionValidator
 * @package App\Domains\Subscription\Validators
 */
class SubscriptionValidator
{
    /**
     * @param $data
     * @return void
     */
    public function validate($data): void
    {
        $rules = [
            'plan_id' => 'required|exists:plans,id',
            'number_of_users' => 'required|integer|min:1',
            'payment_frequency' => 'required|in:monthly,yearly',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new RuntimeException($validator->errors()->first());
        }
    }
}
