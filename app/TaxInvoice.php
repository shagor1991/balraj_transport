<?php

namespace App;

use App\Models\CostCenter;
use Illuminate\Database\Eloquent\Model;

class TaxInvoice extends Model
{
    public function items(){
        return $this->hasMany(TaxInvoiceItem::class,'invoice_id');
    }

    public function customer(){
        return $this->belongsTo(PartyInfo::class,'customer_id');
    }

    public function project(){
        return $this->belongsTo(ProjectDetail::class,'project_id');
    }

    // public function cost_center(){
    //     return $this->belongsTo(CostCenter::class,'project_id');
    // }
}
