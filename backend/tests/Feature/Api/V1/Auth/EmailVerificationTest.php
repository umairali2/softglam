<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
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

    public function test_registration_sends_a_verification_notification(): void
    {
        Notification::fake();

        $this->postJson('/api/v1/auth/register', [
            'name' => 'Umair Ali',
            'email' => 'umair@example.com',
            'password' => 'StrongerPass123',
            'password_confirmation' => 'StrongerPass123',
        ])->assertCreated();

        $user = User::query()->where('email', 'umair@example.com')->firstOrFail();
        Notification::assertSentTo($user, VerifyEmail::class);
        $this->assertNull($user->email_verified_at);
    }

    public function test_signed_link_verifies_the_email_and_redirects_to_the_frontend(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute(
            'api.v1.auth.verification.verify',
            now()->addHour(),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $response = $this->get($url);

        $response->assertRedirect('http://localhost:3000/verify-email?status=verified');
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_tampered_signed_link_is_rejected(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute(
            'api.v1.auth.verification.verify',
            now()->addHour(),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->get($url.'tampered')->assertForbidden();
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_wrong_hash_redirects_with_invalid_status(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute(
            'api.v1.auth.verification.verify',
            now()->addHour(),
            ['id' => $user->id, 'hash' => sha1('other@example.com')],
        );

        $this->get($url)->assertRedirect('http://localhost:3000/verify-email?status=invalid');
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_authenticated_user_can_request_a_new_verification_link(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();
        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk();

        $this->postJson('/api/v1/auth/email/verification-notification')
            ->assertAccepted()
            ->assertJsonPath('data.status', 'sent');

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verification_resend_is_unavailable_to_guests_and_already_verified_users(): void
    {
        Notification::fake();

        $this->postJson('/api/v1/auth/email/verification-notification')->assertUnauthorized();

        $user = User::factory()->create();
        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk();

        $this->postJson('/api/v1/auth/email/verification-notification')
            ->assertOk()
            ->assertJsonPath('data.status', 'already_verified');
        Notification::assertNothingSent();
    }
}
