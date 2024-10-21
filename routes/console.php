<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('app:apply-pending-subscription-changes', function () {
    $this->comment('Pending subscription changes applied.');
})->purpose('Apply pending subscription changes')->dailyAt('00:00');
