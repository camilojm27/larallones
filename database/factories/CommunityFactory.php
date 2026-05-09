<?php

namespace Database\Factories;

use App\Models\Models\Community;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Community>
 */
class CommunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'name' => $this->faker->company,
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->paragraph,
            'verified' => $this->faker->boolean,
            'logo' => $this->faker->imageUrl(),
            'banner' => $this->faker->imageUrl(),
            'NIT' => $this->faker->unique()->numerify('##########'),
            'legal_representative' => $this->faker->name,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'website' => $this->faker->url,
        ];
    }
}
