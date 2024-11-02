<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'isolation_id', 'directorate_id', 'governorate_id'];

    /**
     * Get the isolation that owns the village.
     */
    public function isolation()
    {
        return $this->belongsTo(Isolation::class);
    }
    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}
