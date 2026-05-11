<?php

namespace App\Models;

use App\Support\IdentityDocumentNormalizer;
use Database\Factories\UserIdentityDocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $country_code
 * @property string $document_type
 * @property string $document_number
 * @property bool $is_primary
 * @property Carbon|null $verified_at
 * @property string|null $verification_source
 */
class UserIdentityDocument extends Model
{
    /** @use HasFactory<UserIdentityDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country_code',
        'document_type',
        'document_number',
        'is_primary',
        'verified_at',
        'verification_source',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setCountryCodeAttribute(?string $value): void
    {
        $this->attributes['country_code'] = $value === null ? null : strtoupper($value);
    }

    public function setDocumentTypeAttribute(?string $value): void
    {
        $this->attributes['document_type'] = $value === null ? null : strtoupper($value);
    }

    public function setDocumentNumberAttribute(?string $value): void
    {
        $this->attributes['document_number'] = $value === null
            ? null
            : IdentityDocumentNormalizer::normalize($value);
    }
}
