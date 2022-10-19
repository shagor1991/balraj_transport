<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptVoucherTemp extends Model
{
    public function party(){
        return $this->belongsTo(PartyInfo::class,'party_info_id');
    }
}
