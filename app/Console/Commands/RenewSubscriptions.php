<?php

namespace App\Console\Commands;

use App\Mail\PaymentReceived;
use App\Services\PaymentService;
use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class RenewSubscriptions extends Command
{
    protected PaymentService $paymentService;
    protected $signature = 'subscriptions:renew';
    protected $description = 'Renew subscriptions that are due';

    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
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

            $paymentSuccessful = $this->paymentService->processPayment($subscription->user, $subscription->price);

            if ($paymentSuccessful) {
                $subscription->renew();

                $transaction = $subscription->transactions()->create([
                    'user_id' => $subscription->user_id,
                    'subscription_id' => $subscription->id,
                    'price' => Subscription::PRICE,
                ]);

                Mail::to($subscription->user->email)->send(new PaymentReceived($transaction));

                $this->info('Subscription renewed for user: ' . $subscription->user->email);

                sleep(1);
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
