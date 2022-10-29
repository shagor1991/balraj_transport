<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierInvoiceItem extends Model
{
    public function invoice(){
        return $this->belongsTo(SupplierInvoice::class);
    }

    public function truck(){
        return $this->belongsTo(Truck::class,'truck_id');
    }
}
