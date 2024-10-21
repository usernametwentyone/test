<?php

namespace App\Console\Commands;

use App\Domains\Subscription\Services\SubscriptionService;
use Illuminate\Console\Command;

/**
 * @class ApplyPendingSubscriptionChanges
 * @package App\Console\Commands
 *
 * @property SubscriptionService $subscriptionService
 */
class ApplyPendingSubscriptionChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:apply-pending-subscription-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var SubscriptionService
     */
    protected SubscriptionService $subscriptionService;

    /**
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->subscriptionService->applyPendingChanges();
        $this->info(__('Pending subscription changes applied.'));
    }
}
