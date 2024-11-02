<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;

    protected $fillable = ['name'];  

    /**
     * Get the directorates for the governorate.
     */
    public function directorates()
    {
        return $this->hasMany(Directorate::class);
    }
}
