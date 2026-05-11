<?php

namespace Database\Seeders;

use App\Models\ProfileFieldDefinition;
use Illuminate\Database\Seeder;

class ProfileFieldDefinitionSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            [
                'field_key' => 'birth_date',
                'data_type' => 'date',
                'is_pii' => true,
                'countries' => null,
                'i18n_label_key' => 'profile.fields.birth_date.label',
                'validation_rules' => [
                    'rule' => 'date',
                    'before_or_equal' => 'today',
                ],
            ],
            [
                'field_key' => 'gender',
                'data_type' => 'enum',
                'is_pii' => true,
                'countries' => null,
                'i18n_label_key' => 'profile.fields.gender.label',
                'validation_rules' => [
                    'enum' => ['male', 'female', 'non_binary', 'other', 'prefer_not_say'],
                ],
            ],
            [
                'field_key' => 'sexual_orientation',
                'data_type' => 'enum',
                'is_pii' => true,
                'countries' => null,
                'i18n_label_key' => 'profile.fields.sexual_orientation.label',
                'validation_rules' => [
                    'enum' => ['heterosexual', 'homosexual', 'bisexual', 'asexual', 'pansexual', 'other', 'prefer_not_say'],
                ],
            ],
            [
                'field_key' => 'blood_type',
                'data_type' => 'enum',
                'is_pii' => true,
                'countries' => null,
                'i18n_label_key' => 'profile.fields.blood_type.label',
                'validation_rules' => [
                    'enum' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
                ],
            ],
            [
                'field_key' => 'health_coverage',
                'data_type' => 'string',
                'is_pii' => true,
                'countries' => null,
                'i18n_label_key' => 'profile.fields.health_coverage.label',
                'validation_rules' => [
                    'max_length' => 128,
                ],
            ],
            [
                'field_key' => 'emergency_contact',
                'data_type' => 'json',
                'is_pii' => true,
                'countries' => null,
                'i18n_label_key' => 'profile.fields.emergency_contact.label',
                'validation_rules' => [
                    'shape' => ['name' => 'string', 'phone' => 'string', 'relationship' => 'string'],
                    'required_keys' => ['name', 'phone'],
                ],
            ],
            [
                'field_key' => 'dietary_restrictions',
                'data_type' => 'json',
                'is_pii' => false,
                'countries' => null,
                'i18n_label_key' => 'profile.fields.dietary_restrictions.label',
                'validation_rules' => [
                    'array_of' => 'string',
                ],
            ],
        ];

        foreach ($catalog as $definition) {
            ProfileFieldDefinition::updateOrCreate(
                ['field_key' => $definition['field_key']],
                $definition,
            );
        }
    }
}
