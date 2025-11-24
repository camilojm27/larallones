<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\CustomField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_community()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/communities', [
            'name' => 'Test Community',
            'description' => 'A test community',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('name', 'Test Community');

        $this->assertDatabaseHas('communities', [
            'name' => 'Test Community',
            'owner_id' => $user->id,
        ]);
    }

    public function test_user_can_list_communities()
    {
        $user = User::factory()->create();
        $community = Community::create([
            'owner_id' => $user->id,
            'name' => 'My Community',
            'slug' => 'my-community',
        ]);
        $community->members()->attach($user->id, ['role' => 'admin']);

        $response = $this->actingAs($user)->getJson('/api/communities');

        $response->assertStatus(200)
            ->assertJsonStructure(['my_communities', 'owned_communities']);
    }

    public function test_user_can_join_community()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $community = Community::create([
            'owner_id' => $owner->id,
            'name' => 'Joinable Community',
            'slug' => 'joinable-community',
        ]);

        $response = $this->actingAs($user)->postJson("/api/communities/{$community->id}/join");

        $response->assertStatus(200);

        $this->assertDatabaseHas('community_user', [
            'user_id' => $user->id,
            'community_id' => $community->id,
        ]);
    }

    public function test_user_can_join_community_with_custom_fields()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $community = Community::create([
            'owner_id' => $owner->id,
            'name' => 'Custom Field Community',
            'slug' => 'custom-field-community',
        ]);

        $field = CustomField::create([
            'community_id' => $community->id,
            'name' => 'Favorite Color',
            'slug' => 'favorite_color',
            'type' => 'text',
            'is_required' => true,
        ]);

        $response = $this->actingAs($user)->postJson("/api/communities/{$community->id}/join", [
            'custom_fields' => [
                'favorite_color' => 'Blue',
            ],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('custom_field_values', [
            'user_id' => $user->id,
            'custom_field_id' => $field->id,
            'value' => 'Blue',
        ]);
    }
}
