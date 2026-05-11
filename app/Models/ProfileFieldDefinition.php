<?php

namespace App\Models;

use App\Casts\PostgresStringArray;
use Database\Factories\ProfileFieldDefinitionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $field_key
 * @property string $data_type
 * @property array<string, mixed> $validation_rules
 * @property bool $is_pii
 * @property list<string>|null $countries
 * @property string $i18n_label_key
 */
class ProfileFieldDefinition extends Model
{
    /** @use HasFactory<ProfileFieldDefinitionFactory> */
    use HasFactory;

    protected $primaryKey = 'field_key';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'field_key',
        'data_type',
        'validation_rules',
        'is_pii',
        'countries',
        'i18n_label_key',
    ];

    protected function casts(): array
    {
        return [
            'validation_rules' => 'array',
            'is_pii' => 'boolean',
            'countries' => PostgresStringArray::class,
        ];
    }

    public function appliesToCountry(?string $countryCode): bool
    {
        if ($this->countries === null || $this->countries === []) {
            return true;
        }

        if ($countryCode === null) {
            return false;
        }

        return in_array(strtoupper($countryCode), array_map('strtoupper', $this->countries), true);
    }
}
