<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStreamingData extends Model
{
    protected $fillable = [
        'channel_id',
        'video_id',
        'title',
        'thumbnail_url',
        'scheduled_time',
        'live_status',
        'type',
    ];

    public $timestamps = false;

    protected $table = 'live_streaming_data';

    protected $casts = [
        'scheduled_time' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'channel_id', 'channel_id');
    }
}
