<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectingMilkFromFamily extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'collecting_milk_from_families';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'collection_date_and_time',
        'nots',
        'quantity',
        'association_id',
        'family_id',
        'user_id',
    ];

    /**
     * Get the association associated with the milk collection.
     */
    public function association()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Family associated with the milk collection.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the user associated with the milk collection.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}