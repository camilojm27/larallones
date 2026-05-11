<?php

namespace Database\Factories;

use App\Models\ProfileFieldDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProfileFieldDefinition>
 */
class ProfileFieldDefinitionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fieldKey = fake()->unique()->slug(2, false);

        return [
            'field_key' => $fieldKey,
            'data_type' => 'string',
            'validation_rules' => [],
            'is_pii' => false,
            'countries' => null,
            'i18n_label_key' => "profile.fields.{$fieldKey}.label",
        ];
    }

    /**
     * @param  list<string>  $countries
     */
    public function forCountries(array $countries): self
    {
        return $this->state(fn () => ['countries' => $countries]);
    }

    public function pii(): self
    {
        return $this->state(fn () => ['is_pii' => true]);
    }
}
