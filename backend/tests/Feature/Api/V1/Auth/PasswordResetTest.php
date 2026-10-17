<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);
    }

    public function test_forgot_password_sends_a_reset_link_for_a_known_email(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'umair@example.com']);

        $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'UMAIR@EXAMPLE.COM',
        ])->assertAccepted();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_forgot_password_returns_the_same_response_for_an_unknown_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'nobody@example.com',
        ]);

        $response
            ->assertAccepted()
            ->assertJsonPath('data.message', 'If an account exists for that email, a reset link is on its way.');
        Notification::assertNothingSent();
    }

    public function test_valid_token_resets_the_password(): void
    {
        $user = User::factory()->create(['email' => 'umair@example.com']);
        $token = Password::createToken($user);

        $this->postJson('/api/v1/auth/reset-password', [
            'token' => $token,
            'email' => 'umair@example.com',
            'password' => 'NewStrongerPass456',
            'password_confirmation' => 'NewStrongerPass456',
        ])->assertOk()->assertJsonPath('data.status', 'password_reset');

        $this->assertTrue(Hash::check('NewStrongerPass456', $user->fresh()->password));
    }

    public function test_invalid_token_is_rejected(): void
    {
        User::factory()->create(['email' => 'umair@example.com']);

        $this->postJson('/api/v1/auth/reset-password', [
            'token' => 'not-a-real-token',
            'email' => 'umair@example.com',
            'password' => 'NewStrongerPass456',
            'password_confirmation' => 'NewStrongerPass456',
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }
}
