<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'creator_id',
        'name',
        'description',
        'type',
        'location',
        'image',
        'start_time',
        'end_time',
        'transport_enabled',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'transport_enabled' => 'boolean',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_user')->withPivot('attendance_status');
    }
}
