<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'farmers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'phone',
        'association_id',
        'associations_branche_id',
    ];

    /**
     * Get the association associated with the farmer.
     */
    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    /**
     * Get the association's branch associated with the farmer.
     */
    public function associationsBranch()
    {
        return $this->belongsTo(AssociationsBranch::class);
    }
}