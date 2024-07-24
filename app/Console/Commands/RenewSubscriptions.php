<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;

class RenewSubscriptions extends Command
{
    protected $signature = 'subscriptions:renew';
    protected $description = 'Renew subscriptions that are due';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info('Starting subscription renew process...');

        $subscriptions = Subscription::where('renewal_at', '<=', Carbon::now())->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions due for renewal.');
            return;
        }

        foreach ($subscriptions as $subscription) {
            $this->info('Processing subscription for user: ' . $subscription->user->email);

            $paymentSuccessful = $this->processPayment($subscription->user);

            if ($paymentSuccessful) {
                $subscription->renew();
                $this->info('Subscription renewed for user: ' . $subscription->user->email);
            } else {
                $this->warn('Payment failed for user: ' . $subscription->user->email);
            }
        }

        $this->info('Subscription renew process completed.');
    }

    /**
     * @param $user
     * @return bool
     */
    protected function processPayment($user): bool
    {
        $paymentSuccessful = rand(0, 1) == 1;

        if ($paymentSuccessful) {
            $this->info('Payment successful for user: ' . $user->email);
        } else {
            $this->warn('Payment failed for user: ' . $user->email);
        }

        return $paymentSuccessful;
    }
}
