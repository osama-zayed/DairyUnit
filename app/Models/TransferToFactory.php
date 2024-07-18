<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferToFactory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfer_to_factories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'association_id',
        'driver_id',
        'factory_id',
        'means_of_transportation',
        'quantity',
        'date_and_time',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    /**
     * Get the association that owns the transfer.
     */
    public function association()
    {
        return $this->belongsTo(User::class, 'association_id');
    }

    /**
     * Get the driver associated with the transfer.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Get the factory associated with the transfer.
     */
    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }
}