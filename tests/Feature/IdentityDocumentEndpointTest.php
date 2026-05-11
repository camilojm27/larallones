<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserIdentityDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdentityDocumentEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_identity_document(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);

        $response = $this->actingAs($user)->postJson('/api/me/identity-documents', [
            'country_code' => 'CO',
            'document_type' => 'cc',
            'document_number' => '1.234.567.890',
            'is_primary' => true,
        ]);

        $response->assertCreated();
        $response->assertJson([
            'country_code' => 'CO',
            'document_type' => 'CC',
            'document_number' => '1234567890',
            'is_primary' => true,
        ]);

        $this->assertDatabaseHas('user_identity_documents', [
            'user_id' => $user->id,
            'country_code' => 'CO',
            'document_type' => 'CC',
            'document_number' => '1234567890',
            'is_primary' => true,
        ]);
    }

    public function test_normalization_collides_with_punctuated_duplicate(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);
        $other = User::factory()->create(['country_code' => 'CO']);

        UserIdentityDocument::factory()->for($other)->create([
            'country_code' => 'CO',
            'document_type' => 'CC',
            'document_number' => '1234567890',
        ]);

        $response = $this->actingAs($user)->postJson('/api/me/identity-documents', [
            'country_code' => 'CO',
            'document_type' => 'CC',
            'document_number' => '1.234.567.890',
        ]);

        $response->assertStatus(409);
        $response->assertJson(['normalized_number' => '1234567890']);
    }

    public function test_same_number_in_different_country_is_allowed(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);
        $other = User::factory()->create(['country_code' => 'AR']);

        UserIdentityDocument::factory()->for($other)->create([
            'country_code' => 'AR',
            'document_type' => 'DNI',
            'document_number' => '1234567890',
        ]);

        $response = $this->actingAs($user)->postJson('/api/me/identity-documents', [
            'country_code' => 'CO',
            'document_type' => 'CC',
            'document_number' => '1234567890',
        ]);

        $response->assertCreated();
    }

    public function test_setting_is_primary_clears_previous_primary_for_user(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);

        $first = UserIdentityDocument::factory()->for($user)->primary()->create([
            'document_number' => '1111111111',
        ]);

        $response = $this->actingAs($user)->postJson('/api/me/identity-documents', [
            'country_code' => 'CO',
            'document_type' => 'PA',
            'document_number' => 'AB123456',
            'is_primary' => true,
        ]);

        $response->assertCreated();
        $this->assertFalse($first->refresh()->is_primary);
    }

    public function test_user_can_list_their_identity_documents(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);
        $stranger = User::factory()->create(['country_code' => 'CO']);

        UserIdentityDocument::factory()->for($user)->count(2)->create();
        UserIdentityDocument::factory()->for($stranger)->create();

        $response = $this->actingAs($user)->getJson('/api/me/identity-documents');

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }

    public function test_user_cannot_modify_someone_elses_document(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);
        $stranger = User::factory()->create(['country_code' => 'CO']);
        $document = UserIdentityDocument::factory()->for($stranger)->create();

        $this->actingAs($user)
            ->patchJson("/api/me/identity-documents/{$document->id}", ['is_primary' => true])
            ->assertForbidden();

        $this->actingAs($user)
            ->deleteJson("/api/me/identity-documents/{$document->id}")
            ->assertForbidden();
    }

    public function test_user_can_delete_their_document(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);
        $document = UserIdentityDocument::factory()->for($user)->create();

        $this->actingAs($user)
            ->deleteJson("/api/me/identity-documents/{$document->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('user_identity_documents', ['id' => $document->id]);
    }

    public function test_endpoints_require_authentication(): void
    {
        $this->getJson('/api/me/identity-documents')->assertUnauthorized();
        $this->postJson('/api/me/identity-documents', [])->assertUnauthorized();
    }
}
