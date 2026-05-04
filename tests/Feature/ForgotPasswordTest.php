<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_sends_reset_link_for_existing_user(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['status']);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_forgot_password_returns_success_for_nonexistent_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'nobody@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['status']);

        Notification::assertNothingSent();
    }

    public function test_forgot_password_requires_valid_email(): void
    {
        $response = $this->postJson('/api/forgot-password', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_forgot_password_requires_email_field(): void
    {
        $response = $this->postJson('/api/forgot-password', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_reset_password_form_is_accessible(): void
    {
        $response = $this->get(route('password.reset', [
            'token' => 'fake-token',
            'email' => 'user@example.com',
        ]));

        $response->assertStatus(200);
    }
}
