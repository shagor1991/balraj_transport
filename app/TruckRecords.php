<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TruckRecords extends Model
{
    public function truck(){
        return $this->belongsTo(Truck::class);
    }

    public function customer(){
        return $this->belongsTo(PartyInfo::class,'customer_id');
    }

    public function supplier(){
        return $this->belongsTo(PartyInfo::class,'truck_owner');
    }
}
