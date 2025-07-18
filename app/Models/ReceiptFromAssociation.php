<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptFromAssociation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receipt_from_associations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'transfer_to_factory_id',
        'association_id',
        'driver_id',
        'factory_id',
        'start_time_of_collection',
        'end_time_of_collection',
        'quantity',
        'package_cleanliness',
        'transport_cleanliness',
        'driver_personal_hygiene',
        'ac_operation',
        'user_id',
        'notes',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function transferToFactory()
    {
        return $this->belongsTo(TransferToFactory::class, 'transfer_to_factory_id');
    }
    public function association()
    {
        return $this->belongsTo(User::class, 'association_id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }
}
