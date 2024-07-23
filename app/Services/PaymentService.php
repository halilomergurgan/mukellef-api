<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;

class PaymentService
{
    /**
     * @param User $user
     * @param array $data
     * @return Subscription
     */
    public function storeSubscription(User $user, array $data): Subscription
    {
        return  $user->subscriptions()->create($data);
    }

    public function updateSubscription(Subscription $subscription, array $data): Subscription
    {
        $subscription->update($data);

        return $subscription;
    }

    /**
     * @param Subscription $subscription
     * @return bool
     */
    public function destroySubscription(Subscription $subscription): bool
    {
        return $subscription->delete();
    }

    /**
     * @param User $user
     * @param float|null $amount
     * @return bool
     */
    public function processPayment(User $user, ?float $amount): bool
    {
        $provider = $user->payment_provider;

        return match ($provider) {
            'iyzico' => $this->payWithIyzico($amount),
            default => $this->payWithStripe($amount),
        };
    }

    /**
     * @param float|null $amount
     * @return bool
     */
    private function payWithStripe(?float $amount): bool
    {
        // always true
        return true;
    }

    /**
     * @param float|null $amount
     * @return bool
     */
    private function payWithIyzico(?float $amount): bool
    {
        // always true
        return true;
    }

    /**
     * @param User $user
     * @param $subscriptionId
     * @param $price
     * @return Transaction
     */
    public function createTransaction(User $user, $subscriptionId, float $price = 100): Transaction
    {
        return Transaction::create([
            'user_id' => $user->id,
            'subscription_id' => $subscriptionId,
            'price' => $price,
        ]);
    }
}
