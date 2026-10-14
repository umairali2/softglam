<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
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

    public function test_valid_payload_registers_and_authenticates_a_customer(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => '  Umair Ali  ',
            'email' => 'UMAIR@EXAMPLE.COM',
            'password' => 'StrongerPass123',
            'password_confirmation' => 'StrongerPass123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Umair Ali')
            ->assertJsonPath('data.email', 'umair@example.com')
            ->assertJsonMissingPath('data.password');
        $user = User::query()->where('email', 'umair@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('StrongerPass123', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_rejects_a_duplicate_email_with_422(): void
    {
        User::factory()->create(['email' => 'umair@example.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Umair Ali',
            'email' => 'UMAIR@EXAMPLE.COM',
            'password' => 'StrongerPass123',
            'password_confirmation' => 'StrongerPass123',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
        $this->assertSame(1, User::query()->count());
    }

    public function test_valid_credentials_create_a_session_and_return_the_current_user(): void
    {
        $user = User::factory()->create([
            'email' => 'umair@example.com',
            'password' => 'StrongerPass123',
        ]);

        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'UMAIR@EXAMPLE.COM',
            'password' => 'StrongerPass123',
        ]);
        $currentUserResponse = $this->getJson('/api/v1/auth/user');

        $loginResponse
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);
        $currentUserResponse
            ->assertOk()
            ->assertJsonPath('data.email', 'umair@example.com');
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_return_the_same_422_error(): void
    {
        User::factory()->create(['email' => 'known@example.com']);

        $knownEmailResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'known@example.com',
            'password' => 'WrongPassword123',
        ]);
        $unknownEmailResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'unknown@example.com',
            'password' => 'WrongPassword123',
        ]);

        $knownEmailResponse
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
        $unknownEmailResponse
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
        $this->assertSame(
            $knownEmailResponse->json('errors.email.0'),
            $unknownEmailResponse->json('errors.email.0'),
        );
        $this->assertGuest();
    }

    public function test_unauthenticated_current_user_request_returns_401(): void
    {
        $this->getJson('/api/v1/auth/user')->assertUnauthorized();
    }

    public function test_logout_invalidates_the_session(): void
    {
        User::factory()->create([
            'email' => 'umair@example.com',
            'password' => 'StrongerPass123',
        ]);
        $this->postJson('/api/v1/auth/login', [
            'email' => 'umair@example.com',
            'password' => 'StrongerPass123',
        ])->assertOk();

        $this->postJson('/api/v1/auth/logout')->assertNoContent();
        Auth::forgetGuards();

        $this->getJson('/api/v1/auth/user')->assertUnauthorized();
    }

    public function test_login_is_rate_limited_after_five_attempts(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->postJson('/api/v1/auth/login', [
                'email' => 'unknown@example.com',
                'password' => 'WrongPassword123',
            ])->assertUnprocessable();
        }

        $this->postJson('/api/v1/auth/login', [
            'email' => 'unknown@example.com',
            'password' => 'WrongPassword123',
        ])->assertTooManyRequests();
    }
}
