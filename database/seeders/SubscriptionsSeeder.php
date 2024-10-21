<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        $user = DB::table('users')->where('email', 'test@example.com')->first();
        $plan = DB::table('plans')->where('name', 'Lite')->first();

        DB::table('subscriptions')->insert([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'number_of_users' => 7,
            'payment_frequency' => 'monthly',
            'discount_percentage' => 0,
            'total_cost' => 28.00,
            'current_period_end' => Carbon::now()->addMonth()->format('Y-m-d'),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
