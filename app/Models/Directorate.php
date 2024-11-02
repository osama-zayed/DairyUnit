<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directorate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'governorate_id'];  

    /**
     * Get the governorate that owns the directorate.
     */
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    /**
     * Get the isolations for the directorate.
     */
    public function isolations()
    {
        return $this->hasMany(Isolation::class);
    }
}
