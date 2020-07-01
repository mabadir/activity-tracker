<?php

namespace Mabadir\ActivityTracker\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visitor_id', 'user_id', 'activity_type_id', 'payload'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * BelongsTo User
     */
   public function user()
   {
       $this->belongsTo(User::class);
   }
}
