<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Services\PaymentService;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    protected PaymentService $paymentService;

    /**
     * @param PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(StoreSubscriptionRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        return response()->json([
            'data' => [
                'subscription' => SubscriptionResource::make($this->paymentService->storeSubscription($user, $data))
            ]
        ], 201);
    }

    /**
     * @param UpdateSubscriptionRequest $request
     * @param User $user
     * @param Subscription $subscription
     * @return JsonResponse
     */
    public function update(UpdateSubscriptionRequest $request, User $user, Subscription $subscription): JsonResponse
    {
        $data = $request->validated();

        if ($user->id !== $subscription->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $updatedSubscription = $this->paymentService->updateSubscription($subscription, $data);

        return response()->json([
            'data' => [
                'subscription' => SubscriptionResource::make($updatedSubscription)
            ]
        ], 201);
    }
}
