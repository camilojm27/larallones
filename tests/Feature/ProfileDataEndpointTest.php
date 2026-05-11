<?php

namespace Tests\Feature;

use App\Models\ProfileFieldDefinition;
use App\Models\User;
use App\Models\UserProfileDatum;
use Database\Seeders\ProfileFieldDefinitionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileDataEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProfileFieldDefinitionSeeder::class);
    }

    public function test_index_returns_field_definitions_filtered_by_country_and_user_values(): void
    {
        ProfileFieldDefinition::factory()->forCountries(['MX'])->create([
            'field_key' => 'curp',
            'data_type' => 'string',
        ]);

        $user = User::factory()->create(['country_code' => 'CO']);
        UserProfileDatum::query()->insert([
            'user_id' => $user->id,
            'field_key' => 'blood_type',
            'value' => json_encode('O+'),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->getJson('/api/me/profile-data');

        $response->assertOk();
        $response->assertJsonPath('data.blood_type', 'O+');
        $response->assertJsonMissingPath('data.curp');

        $definitions = collect($response->json('definitions'))->pluck('field_key');
        $this->assertContains('blood_type', $definitions);
        $this->assertNotContains('curp', $definitions);
    }

    public function test_update_persists_valid_field_values(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);

        $response = $this->actingAs($user)->putJson('/api/me/profile-data', [
            'blood_type' => 'O+',
            'birth_date' => '1995-04-21',
            'emergency_contact' => [
                'name' => 'Maria',
                'phone' => '+573001234567',
                'relationship' => 'sister',
            ],
            'dietary_restrictions' => ['vegetarian', 'gluten_free'],
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('user_profile_data', [
            'user_id' => $user->id,
            'field_key' => 'blood_type',
        ]);

        $stored = UserProfileDatum::query()
            ->where('user_id', $user->id)
            ->where('field_key', 'emergency_contact')
            ->first();
        $this->assertSame('Maria', $stored->value['name']);
        $this->assertSame('+573001234567', $stored->value['phone']);
    }

    public function test_update_rejects_unknown_field_key(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);

        $response = $this->actingAs($user)->putJson('/api/me/profile-data', [
            'totally_made_up' => 'value',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['totally_made_up']);
    }

    public function test_update_rejects_field_not_applicable_to_user_country(): void
    {
        ProfileFieldDefinition::factory()->forCountries(['MX'])->create([
            'field_key' => 'curp',
            'data_type' => 'string',
        ]);

        $user = User::factory()->create(['country_code' => 'CO']);

        $response = $this->actingAs($user)->putJson('/api/me/profile-data', [
            'curp' => 'AAAA000000HDFRRR00',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['curp']);
    }

    public function test_update_validates_enum_values(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);

        $response = $this->actingAs($user)->putJson('/api/me/profile-data', [
            'blood_type' => 'Z+',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['blood_type']);
    }

    public function test_destroy_removes_a_field_value(): void
    {
        $user = User::factory()->create(['country_code' => 'CO']);

        UserProfileDatum::query()->insert([
            'user_id' => $user->id,
            'field_key' => 'blood_type',
            'value' => json_encode('O+'),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/me/profile-data/blood_type');

        $response->assertNoContent();
        $this->assertDatabaseMissing('user_profile_data', [
            'user_id' => $user->id,
            'field_key' => 'blood_type',
        ]);
    }

    public function test_user_cannot_read_or_update_data_unauthenticated(): void
    {
        $this->getJson('/api/me/profile-data')->assertUnauthorized();
        $this->putJson('/api/me/profile-data', ['blood_type' => 'O+'])->assertUnauthorized();
    }
}
