<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function test_user_details_are_returned_in_expected_structure()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscriptions = Subscription::factory()->count(2)->create(['user_id' => $user->id]);
        $transactions = Transaction::factory()->count(2)->create([
            'user_id' => $user->id,
            'subscription_id' => $subscriptions->first()->id,
            'price' => 100,
        ]);

        $response = $this->getJson('/api/user/' . $user->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'message' => 'Success',
            'status_code' => 200,
        ]);

        foreach ($subscriptions as $subscription) {
            $response->assertJsonFragment([
                'id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'username' => $subscription->user->name,
                'renewal_at' => Carbon::parse($subscription->renewal_at)->format('Y-m-d H:i:s'),
            ]);
        }

        foreach ($transactions as $transaction) {
            $response->assertJsonFragment([
                'id' => $transaction->id,
                'subscription_id' => $transaction->subscription_id,
                'price' => number_format($transaction->price, 2, '.', ''),
            ]);
        }
    }
}
