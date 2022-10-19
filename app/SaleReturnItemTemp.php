<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleReturnItemTemp extends Model
{
    public function item()
    {
        return $this->belongsTo(ItemList::class);
    }
}
