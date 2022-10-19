<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    public function party(){
        return $this->belongsTo(PartyInfo::class,'owner');
    }
}
