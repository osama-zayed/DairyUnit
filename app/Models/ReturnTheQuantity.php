<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnTheQuantity extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'returns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'receipt_from_association_id',
        'return_to',
        'defective_quantity_due_to_coagulation',
        'defective_quantity_due_to_impurities',
        'defective_quantity_due_to_density',
        'defective_quantity_due_to_acidity',
        'notes',
    ];

    public function association()
    {
        return $this->belongsTo(User::class, 'receipt_from_association_id');
    }
  
}
