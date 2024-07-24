<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'renewal_at' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d H:i:s'),
        ];
    }
}
