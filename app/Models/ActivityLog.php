<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'activity_log';
    protected $fillable = [
        'id',
        'log_name',
        'description',
        'event',
        'subject_id',
        'causer_id',
        'created_at',
    ];
}
