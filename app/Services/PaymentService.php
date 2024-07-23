<?php

namespace App\Services;

use App\Models\Subscription;
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

    /**
     * @param Subscription $subscription
     * @param array $data
     * @return Subscription
     */
    public function updateSubscription(Subscription $subscription, array $data): Subscription
    {
        $subscription->update($data);

        return $subscription;
    }
}
