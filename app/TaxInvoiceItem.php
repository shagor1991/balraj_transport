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

    public function record(){
        return $this->belongsTo(TruckRecords::class,'item_id');
    }

    public function supplier(){
        return $this->belongsTo(PartyInfo::class,'supplier_id');
    }
}
