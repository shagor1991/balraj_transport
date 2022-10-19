<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    public function item()
    {
        return $this->belongsTo(ItemList::class,'item_id');
    }
}
