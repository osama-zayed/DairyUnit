<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptInvoiceFromStore extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receipt_invoice_from_stores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'association_id',
        'associations_branche_id',
        'quantity',
        'date_and_time',
        'notes',

    ];


    public function association()
    {
        return $this->belongsTo(User::class);
    }
    public function associationsBranche()
    {
        return $this->belongsTo(User::class,'associations_branche_id');
    }
}
