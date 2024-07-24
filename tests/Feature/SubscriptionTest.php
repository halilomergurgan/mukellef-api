<?php

namespace Tests\Feature;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function a_user_can_add_a_subscription()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscriptionData = [
            'renewal_at' => now()->addMonth()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson('/api/user/' . $user->id . '/subscription', $subscriptionData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'renewal_at' => $subscriptionData['renewal_at'],
        ]);
    }

    #[Test]
    public function subscription_requires_renewal_at()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/user/' . $user->id . '/subscription', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('renewal_at');
    }

    #[Test]
    public function a_user_can_update_a_subscription()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscription = Subscription::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'renewal_at' => now()->addMonths(2)->format('Y-m-d H:i:s'),
        ];

        $response = $this->putJson('/api/user/' . $user->id . '/subscription/' . $subscription->id, $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'renewal_at' => $updatedData['renewal_at'],
        ]);
    }

    #[Test]
    public function a_user_can_delete_a_subscription()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscription = Subscription::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/user/' . $user->id . '/subscription/' . $subscription->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('subscriptions', [
            'id' => $subscription->id,
        ]);
    }

    #[Test]
    public function a_user_can_process_a_payment()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscription = Subscription::factory()->create(['user_id' => $user->id]);

        $paymentData = [
            'subscription_id' => $subscription->id
        ];

        $response = $this->postJson('/api/user/' . $user->id . '/transaction', $paymentData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'price' => Subscription::PRICE,
        ]);
    }

    #[Test]
    public function subscription_cannot_have_past_renewal_date()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscriptionData = [
            'renewal_at' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson('/api/user/' . $user->id . '/subscription', $subscriptionData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('renewal_at');
    }

    #[Test]
    public function update_requires_renewal_at()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscription = Subscription::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson('/api/user/' . $user->id . '/subscription/' . $subscription->id, []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('renewal_at');
    }

    #[Test]
    public function update_cannot_have_past_renewal_date()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscription = Subscription::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'renewal_at' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
        ];

        $response = $this->putJson('/api/user/' . $user->id . '/subscription/' . $subscription->id, $updatedData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('renewal_at');
    }

    #[Test]
    public function an_unauthorized_user_cannot_add_or_update_subscription()
    {
        $user = User::factory()->create();

        $subscription = Subscription::factory()->create(['user_id' => $user->id]);

        $subscriptionData = [
            'renewal_at' => now()->addMonth()->format('Y-m-d H:i:s'),
        ];

        $updatedData = [
            'renewal_at' => now()->addMonths(2)->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson('/api/user/' . $user->id . '/subscription', $subscriptionData);
        $response->assertStatus(401);

        $response = $this->putJson('/api/user/' . $user->id . '/subscription/' . $subscription->id, $updatedData);
        $response->assertStatus(401);
    }
}
