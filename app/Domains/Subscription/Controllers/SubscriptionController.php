<?php

namespace App\Domains\Subscription\Controllers;

use App\Domains\Subscription\Models\Plan;
use App\Domains\Subscription\Services\SubscriptionChangeService;
use App\Domains\Subscription\Services\SubscriptionService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class SubscriptionController
 * @package App\Domains\Subscription\Controllers
 *
 * @property SubscriptionService $subscriptionService
 * @property SubscriptionChangeService $subscriptionChangeService
 */
class SubscriptionController extends Controller
{
    /**
     * @var SubscriptionService
     */
    protected SubscriptionService $subscriptionService;
    /**
     * @var SubscriptionChangeService
     */
    protected SubscriptionChangeService $subscriptionChangeService;

    /**
     * @param SubscriptionService $subscriptionService
     * @param SubscriptionChangeService $subscriptionChangeService
     */
    public function __construct(
        SubscriptionService       $subscriptionService,
        SubscriptionChangeService $subscriptionChangeService
    )
    {
        $this->subscriptionService = $subscriptionService;
        $this->subscriptionChangeService = $subscriptionChangeService;
    }

    /**
     * @return View|Factory|Application|\Illuminate\View\View|RedirectResponse
     */
    public function show(): View|Factory|Application|\Illuminate\View\View|RedirectResponse
    {
        $userId = auth()->id();
        $subscription = $this->subscriptionService->getCurrentSubscription($userId);

        $pendingChange = $this->subscriptionService->getPendingChange($subscription->id);
        $plans = Plan::all();

        return view('subscriptions.index', compact('subscription', 'pendingChange', 'plans'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $userId = auth()->id();
        $subscription = $this->subscriptionService->getCurrentSubscription($userId);

        $data = $request->only(['plan_id', 'number_of_users', 'payment_frequency']);
        $data['discount_percentage'] = $data['payment_frequency'] === 'yearly' ? 20 : 0;

        try {
            $this->subscriptionChangeService->changeSubscription($subscription, $data);
            return redirect()->back()->with('success', 'Subscription change request submitted successfully');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroyPendingChange(): RedirectResponse
    {
        $userId = auth()->id();
        $subscription = $this->subscriptionService->getCurrentSubscription($userId);

        if (!$subscription) {
            return redirect()->route('subscription.show')->withErrors(__('You do not have an active subscription.'));
        }

        $pendingChange = $this->subscriptionService->getPendingChange($subscription->id);

        if (!$pendingChange) {
            return redirect()->route('subscription.show')->withErrors(__('You have no pending subscription changes to cancel.'));
        }

        try {
            $this->subscriptionChangeService->cancelPendingChange($pendingChange);
            return redirect()->route('subscription.show')->with('success', __('Pending subscription changes have been canceled.'));
        } catch (Exception $e) {
            return redirect()->route('subscription.show')->withErrors($e->getMessage());
        }
    }
}

