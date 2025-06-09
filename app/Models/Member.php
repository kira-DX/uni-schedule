<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $primaryKey = 'channel_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'channel_id',
        'name',
    ];

    public $timestamps = false;

    protected $table = 'members';

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
