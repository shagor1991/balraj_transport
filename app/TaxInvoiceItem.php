<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxInvoiceItem extends Model
{
    public function invoice(){
        return $this->belongsTo(TaxInvoice::class);
    }

    public function truck(){
        return $this->belongsTo(Truck::class,'truck_id');
    }
}
