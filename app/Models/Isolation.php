<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Isolation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'directorate_id', 'governorate_id'];  

    /**
     * Get the directorate that owns the isolation.
     */
    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
    /**
     * Get the villages for the isolation.
     */
    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
