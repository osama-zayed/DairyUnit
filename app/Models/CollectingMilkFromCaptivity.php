<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectingMilkFromCaptivity extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'collecting_milk_from_captivities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'collection_date_and_time',
        'period',
        'quantity',
        'association_id',
        'associations_branche_id',
        'farmer_id',
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
     * Get the association's branch associated with the milk collection.
     */
    public function associationsBranch()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the farmer associated with the milk collection.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    /**
     * Get the user associated with the milk collection.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}