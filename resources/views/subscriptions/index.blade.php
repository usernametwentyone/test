<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 text-green-700 bg-green-100 rounded">
                    {{ __('Subscription updated successfully!') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 text-red-700 bg-red-100 rounded">
                    {{ __('An error occurred while updating the subscription.') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">{{ __('Current Subscription Status') }}</h1>

                <table class="min-w-full divide-y divide-gray-200 mb-8">
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('Plan') }}</th>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subscription->plan->name }}</td>
                    </tr>
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('Number of Users') }}</th>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subscription->number_of_users }}</td>
                    </tr>
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('Total Cost') }}</th>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subscription->total_cost }} {{ __('euro') }}</td>
                    </tr>
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('Payment Frequency') }}</th>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subscription->payment_frequency === 'monthly' ? __('Monthly') : __('Yearly') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('Expires On') }}</th>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subscription->current_period_end }}</td>
                    </tr>
                    </tbody>
                </table>

                @if($pendingChange)
                    <h2 class="text-xl font-semibold mb-4">{{ __('Upcoming Changes') }}</h2>
                    <table class="min-w-full divide-y divide-gray-200 mb-8">
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('New Plan') }}</th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pendingChange->newPlan->name }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('New Number of Users') }}</th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pendingChange->new_number_of_users }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('New Total Cost') }}</th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pendingChange->new_total_cost }} {{ __('euro') }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('New Payment Frequency') }}</th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pendingChange->new_payment_frequency === 'monthly' ? __('Monthly') : __('Yearly') }}
                            </td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">{{ __('Changes Will Take Effect On') }}</th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pendingChange->change_effective_date }}</td>
                        </tr>
                        </tbody>
                    </table>

                    <form action="{{ route('subscription.pending-change.destroy') }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to cancel the pending changes?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ __('Cancel Pending Changes') }}
                        </button>
                    </form>
                @endif

                <br>
                <br>
                <h1 class="text-xl font-semibold mb-4">{{ __('Change Subscription') }}</h1>

                @if ($errors->any())
                    <div class="mb-4 p-4 text-red-700 bg-red-100 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('subscription.update') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="plan_id" class="block text-sm font-medium text-gray-700">{{ __('Plan') }}</label>
                        <select name="plan_id" id="plan_id" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ $plan->id === $subscription->plan_id ? 'selected' : '' }}>
                                    {{ $plan->name }} - {{ $plan->price_per_user_per_month }} {{ __('euro') }}/{{ __('user') }}/{{ __('month') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="number_of_users" class="block text-sm font-medium text-gray-700">{{ __('Number of Users') }}</label>
                        <input type="number" name="number_of_users" id="number_of_users" class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" value="{{ old('number_of_users', $subscription->number_of_users) }}" min="1">
                    </div>

                    <div class="mb-6">
                        <label for="payment_frequency" class="block text-sm font-medium text-gray-700">{{ __('Payment Frequency') }}</label>
                        <select name="payment_frequency" id="payment_frequency" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="monthly" {{ $subscription->payment_frequency === 'monthly' ? 'selected' : '' }}>
                                {{ __('Monthly') }}
                            </option>
                            <option value="yearly" {{ $subscription->payment_frequency === 'yearly' ? 'selected' : '' }}>
                                {{ __('Yearly (20% discount)') }}
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Save Changes') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
