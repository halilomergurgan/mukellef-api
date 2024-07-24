<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class UserRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function a_user_can_register()
    {
        $user = User::factory()->make([
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    #[Test]
    public function name_is_required_for_registration()
    {
        $user = User::factory()->make([
            'name' => null,
        ]);

        $response = $this->postJson('/api/register', [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    #[Test]
    public function email_is_required_for_registration()
    {
        $user = User::factory()->make([
            'email' => null,
        ]);

        $response = $this->postJson('/api/register', [
            'name' => $user->name,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    #[Test]
    public function email_must_be_valid_for_registration()
    {
        $user = User::factory()->make([
            'email' => 'not-an-email',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => $user->name,
            'email' => 'not-an-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    #[Test]
    public function email_must_be_unique_for_registration()
    {
        $existingUser = User::factory()->create();

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => $existingUser->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    #[Test]
    public function password_is_required_for_registration()
    {
        $user = User::factory()->make([
            'password' => null,
        ]);

        $response = $this->postJson('/api/register', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    #[Test]
    public function password_must_be_confirmed_for_registration()
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    #[Test]
    public function password_must_be_at_least_eight_characters_for_registration()
    {
        $user = User::factory()->make([
            'password' => 'short',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }
}
