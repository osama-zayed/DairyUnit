<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'families';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'phone',
        'status',
        'association_id',
        'associations_branche_id',
        'number_of_cows_produced',
        'number_of_cows_unproductive',
    ];

    /**
     * Get the association associated with the Family.
     */
    public function association()
    {
        return $this->belongsTo(User::class);
    }
    public function associationsBranche()
    {
        return $this->belongsTo(User::class,'associations_branche_id');
    }

}