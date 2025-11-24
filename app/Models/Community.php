<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Community extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'verified',
        'logo',
        'banner',
        'NIT',
        'legal_representative',
        'address',
        'phone_number',
        'email',
        'website',
    ];

    /**
     * Get the owner of the community.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The users that belong to the community.
     */
    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    /**
     * Get the custom fields for the community.
     */
    public function customFields()
    {
        return $this->hasMany(CustomField::class);
    }

    /**
     * Get the events for the community.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
