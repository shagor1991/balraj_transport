<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    public function item()
    {
        return $this->belongsTo(ItemList::class);
    }

    public function itemPrice($invoice,$item)
    {
        $inItem=InvoiceItem::where('invoice_no',$invoice)->where('item_id',$item)->first();
        return $inItem;
    }

    public function invoice($invoice)
    {
        $invoice=Invoice::where('invoice_no',$invoice)->first();
        $customer=$invoice->partyInfo($invoice->customer_name)->pi_name;
        return $customer;
    }

    public function inv()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }


    public function vatAmount()
    {
        return $this->hasMany(SaleReturnItem::class,'sale_return_id')->sum('vat_amount');
    }

    public function TotalAmount()
    {
        return $this->hasMany(SaleReturnItem::class,'sale_return_id')->sum('cost_price');
    }
    public function taxableAmount()
    {
        return $this->hasMany(SaleReturnItem::class,'sale_return_id')->sum('total_unit_price');
    }
}
