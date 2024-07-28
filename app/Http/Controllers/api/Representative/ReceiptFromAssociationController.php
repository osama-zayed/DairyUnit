<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptFromAssociationController\StoreRequest;
use App\Models\ReceiptFromAssociation;
use Illuminate\Http\Request;

class ReceiptFromAssociationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return self::responseError('jjj');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
      
    }

    /**
     * Display the specified resource.
     */
    public function show(ReceiptFromAssociation $receiptFromAssociation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReceiptFromAssociation $receiptFromAssociation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceiptFromAssociation $receiptFromAssociation)
    {
        //
    }
}
