<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property string $field_key
 * @property mixed $value
 */
class UserProfileDatum extends Model
{
    protected $table = 'user_profile_data';

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = null;

    protected $fillable = [
        'user_id',
        'field_key',
        'value',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fieldDefinition(): BelongsTo
    {
        return $this->belongsTo(ProfileFieldDefinition::class, 'field_key', 'field_key');
    }
}
