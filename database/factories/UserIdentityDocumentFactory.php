<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserIdentityDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserIdentityDocument>
 */
class UserIdentityDocumentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'country_code' => 'CO',
            'document_type' => 'CC',
            'document_number' => (string) fake()->unique()->numerify('##########'),
            'is_primary' => false,
            'verified_at' => null,
            'verification_source' => null,
        ];
    }

    public function primary(): self
    {
        return $this->state(fn () => ['is_primary' => true]);
    }
}
