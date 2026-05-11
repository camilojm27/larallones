<?php

namespace Tests\Feature;

use App\Models\ProfileFieldDefinition;
use App\Models\User;
use Database\Seeders\ProfileFieldDefinitionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileFieldEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProfileFieldDefinitionSeeder::class);
    }

    public function test_returns_only_fields_applicable_to_user_country(): void
    {
        ProfileFieldDefinition::factory()->forCountries(['MX'])->create([
            'field_key' => 'curp',
            'data_type' => 'string',
        ]);
        ProfileFieldDefinition::factory()->forCountries(['CO'])->create([
            'field_key' => 'rut_co',
            'data_type' => 'string',
        ]);

        $user = User::factory()->create(['country_code' => 'CO']);

        $response = $this->actingAs($user)->getJson('/api/profile-fields');

        $response->assertOk();
        $keys = collect($response->json('data'))->pluck('field_key');

        $this->assertContains('blood_type', $keys);
        $this->assertContains('rut_co', $keys);
        $this->assertNotContains('curp', $keys);
    }

    public function test_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/profile-fields')->assertUnauthorized();
    }
}
