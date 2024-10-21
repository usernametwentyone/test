<?php


use App\Domains\Subscription\Controllers\SubscriptionController;

Route::middleware('auth')->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscription', [SubscriptionController::class, 'update'])->name('subscription.update');
    Route::delete('/subscription/pending-change', [SubscriptionController::class, 'destroyPendingChange'])
        ->name('subscription.pending-change.destroy');
});
