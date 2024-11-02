<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $table = 'villages';

    protected $fillable = ['name', 'isolation_id'];  

    /**
     * Get the isolation that owns the village.
     */
    public function isolation()
    {
        return $this->belongsTo(Isolation::class);
    }
}
