<?php

namespace App\Http\Controllers\backend;

use App\DebitCreditVoucher;
use App\DeliveryItem;
use App\DeliveryNote;
use App\FifoInvoice;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItem;
use App\ItemList;
use App\Journal;
use App\JournalRecord;
use App\JournalRecordsTemp;
use App\JournalTemp;
use App\Models\AccountHead;
use App\PartyInfo;
use App\ProjectDetail;
use App\PurchaseReturn;
use App\SaleOrder;
use App\SaleOrderItem;
use App\SaleOrderTemp;
use App\SaleReturn;
use App\SaleReturnItem;
use App\SaleReturnItemTemp;
use App\SaleReturnTemp;
use App\StockTransection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReturnController extends Controller
{
    public function salesReturn()
    {
        // $delete_invoice_temp = SaleReturnTemp::whereDate('created_at', '<', Carbon::today())->delete();

        $sub_invoice=Carbon::now()->format('Ymd');
        $dn_no=SaleReturnTemp::whereDate('created_at', Carbon::today())->where('sale_return_no','LIKE',"%{$sub_invoice}%")->latest()->first();
        if($dn_no)
        {
            $dn_code=rtrim($dn_no->sale_return_no,'SR');

            $no=$dn_code+1;
            $dcode=$no.'SR';
        }
        else
        {
            $dcode=Carbon::now()->format('Ymd').'01'.'SR';
        }

        $new_return=new SaleReturnTemp();
        $new_return->sale_return_no=$dcode;
        // dd($new_return);
        $new_return->save();

        $saleReturns=  SaleReturn::get();
        // dd($saleReturns);
        return view('backend.salesReturn.saleReturn', compact('saleReturns','new_return'));
    }

    public function findsaleInvoice(Request $request)
    {
        // return  $request->all();
        $invoice=Invoice::where('invoice_from',"Warehouse Sale")->where('invoice_no',$request->value)->first();

        $project=ProjectDetail::find($invoice->project_id);
        $customer=$invoice->partyInfo($invoice->customer_name);

        $items=$invoice->items($invoice->invoice_no);

        $temp_item_return=SaleReturnItemTemp::where('sale_return_no',$request->sale_return)->get();



        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.form.itemSelect2', ['items' => $items])->render(),
            'page2' => view('backend.ajax.salesReturnList', ['items' => $temp_item_return])->render(),
                'item' => $invoice, 'project'=>$project, 'items'=>$items, 'customer'=>$customer

        ]);
        }
    }



    public function tempSaleOrderReturn(Request $request)
    {
        // return $request->all();
        $item=InvoiceItem::where('id',$request->item_name)->first();
        // return $item;
        $qty=$item->quantity;
        $r_qty=0;
        $hav_return=SaleReturn::where('invoice_id',$item->invoice_id)->get();
        foreach($hav_return as $return)
        {
            $item=SaleReturnItem::where('sale_return_id',$return->id)->where('item_id',$item->item_id)->first();
            if($item)
            {
                $r_qty=$r_qty+$item->quantity;
            }
        }
        $av_qty=$qty-$r_qty;
        if($request->quantity>$av_qty)
        {
            return Response()->json(['error' =>"Invalid Entry. Invoice Quantity ". $av_qty]);
        }


        $s_return=SaleReturnTemp::where('sale_return_no',$request->sale_return_no)->first();
        
        $itemReturn=SaleReturnItemTemp::where('sale_return_no',$request->sale_return_no)->where('item_id',$item->item_id)->first();
        if(!$itemReturn)
            {

                $itemReturn=new SaleReturnItemTemp();
                $quantity=$request->quantity;
                $itemReturn->sale_return_no= $s_return->sale_return_no;
                $itemReturn->sale_return_id= $s_return->id;
            }
            else
            {
                $quantity=$request->quantity+$itemReturn->quantity;
            }

            if( $av_qty < (int)$quantity)
            {
            return Response()->json(['error' =>"Invalid Entry. Invoice Quantity ".  $av_qty]);
            }

        $itemReturn->item_id= $item->item_id;
        $itemReturn->color_id= $item->color_id;
        $itemReturn->size= $item->size;
        $itemReturn->style_id= $item->style_id;
        $itemReturn->barcode= $item->barcode;
        $itemReturn->quantity=$quantity;
        $itemReturn->cost_price= $item->cost_price/$item->quantity*$quantity;
        $itemReturn->net_amount= 1;
        $itemReturn->vat_rate= $item->vat_rate;
        $itemReturn->vat_amount= $item->vat_amount/$item->quantity*$quantity;
        $itemReturn->unit= $item->unit;
        $itemReturn->unit_price= $item->unit_price;
        $itemReturn->total_unit_price= $item->total_unit_price/$item->quantity*$quantity;

        $itemReturn->save();
        
        // return $itemReturn;
        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.salesReturnList', ['itemReturn' => $itemReturn,'i' =>1])->render(),

        ]);
        }

    }

    public function finalSaveSaleReturn(Request $request)
    {

        $temps=SaleReturnItemTemp::where('sale_return_no', $request->sale_return_no)->get();
        if($temps->count()<1)
        {
            return back()->with('error',"Please, Refresh the page and try again");
        }

        $sub_invoice=Carbon::now()->format('Ymd');
        $dn_no=SaleReturnTemp::whereDate('created_at', Carbon::today())->where('sale_return_no','LIKE',"%{$sub_invoice}%")->latest()->first();
        if($dn_no)
        {
            $dn_code=rtrim($dn_no->sale_return_no,'SR');

            $no=$dn_code+1;
            $dcode=$no.'SR';
        }
        else
        {
            $dcode=Carbon::now()->format('Ymd').'01'.'SR';
        }
        $invoice=Invoice::where('invoice_no',$request->invoice_no)->where('invoice_from','Warehouse Sale')->first();

        $new_return=new SaleReturn();
        $new_return->sale_return_no=$dcode;
        $new_return->invoice_id=$invoice->id;
        $new_return->date=$request->return_date;
        $new_return->approved=false;

        $new_return->save();

        foreach($temps as $temp)
        {
            $itemReturn=new SaleReturnItem();
            $quantity=$request->quantity;
            $itemReturn->sale_return_no=  $new_return->sale_return_no;
            $itemReturn->sale_return_id= $new_return->id;
            $itemReturn->item_id= $temp->item_id;
            $itemReturn->color_id= $temp->color_id;
            $itemReturn->size= $temp->size;
            $itemReturn->style_id= $temp->style_id;
            $itemReturn->barcode= $temp->barcode;
            $itemReturn->quantity=$temp->quantity;
            $itemReturn->cost_price= $temp->cost_price;
            $itemReturn->net_amount= $temp->net_amount;
            $itemReturn->vat_rate= $temp->vat_rate;
            $itemReturn->vat_amount= $temp->vat_amount;
            $itemReturn->unit= $temp->unit;
            $itemReturn->unit_price= $temp->unit_price;
            $itemReturn->total_unit_price= $temp->total_unit_price;
            $itemReturn->save();

            $stock=new StockTransection();
            $stock->transection_id=$new_return->id;
            $stock->item_id=$itemReturn->item_id;
            $stock->date= $new_return->date;
            $stock->quantity=$itemReturn->quantity;
            $stock->stock_effect = 1 ;
            $stock->tns_type_code="T";
            $stock->tns_description="Sale Return";
            $stock->save();

            // $dId=DeliveryNote::where('delivery_note_no',$temp->delivery_note_no)->first();
            $fifoInvItm=FifoInvoice::where('invoice_id',$invoice->id)->where('item_id',$itemReturn->item_id)->orderBy('fifo_id','desc')->first();
            // dd($fifoInvItm);
            $quantity=$stock->quantity;
                    while($quantity>0)
                    {
                        if($fifoInvItm)
                        {
                            // dd($quantity);
                            if($fifoInvItm->quantity>=$quantity)
                            {
                                $ff=$fifoInvItm->fifo;
                                $ff->consumed=$ff->consumed-$quantity;
                                $ff->remaining=$ff->remaining+$quantity;
                                $ff->save();
                                if($fifoInvItm->quantity-$quantity==0)
                                {
                                    $fifoInvItm->forceDelete();
                                }
                                else
                                {
                                    $fifoInvItm->quantity=$fifoInvItm->quantity-$quantity;
                                    $fifoInvItm->save();
                                }
                                $quantity=0;
                            }
                            else
                            {
                                $ff=$fifoInvItm->fifo;
                                $ff->consumed=$ff->consumed-$fifoInvItm->quantity;
                                $ff->remaining=$ff->remaining+$fifoInvItm->quantity;
                                $ff->save;
                                $ff_id=$fifoInvItm->fifo_id;
                                $rQty=$fifoInvItm->quantity;
                                $hulu =$fifoInvItm->forceDelete();
                                $quantity=$quantity-$rQty;
                                $fifoInvItm=FifoInvoice::where('fifo_id','<',$ff_id)->where('invoice_id',$invoice->id)->where('item_id',$itemReturn->item_id)->orderBy('fifo_id','DESC')->first();
                            }
                        }
                    }
                $temp->delete();
            }

          //journal
          $sub_invoice=Carbon::now()->format('Ymd');
          $j_no=Journal::whereDate('created_at', Carbon::today())->where('journal_no','LIKE',"%{$sub_invoice}%")->orderBy('id','DESC')->first();
          if($j_no)
          {
              $j_code=rtrim($j_no->journal_no,'J');
              $no=$j_code+1;
              $jcode=$no.'J';
          }
          else
          {
              $jcode=Carbon::now()->format('Ymd').'001'.'J';
          }
          $journal=new Journal();
          $journal->project_id=$new_return->inv->project_id;
          $journal->journal_no=$jcode;
          $journal->date= $new_return->date;
          $journal->invoice_no=$new_return->sale_return_no;
          $journal->pay_mode=$invoice->pay_mode;
          $journal->cost_center_id= 1;
          $p_info=PartyInfo::where('pi_code',$new_return->inv->customer_name)->first();
          $journal->party_info_id= $p_info->id;
          $journal->account_head_id= 228;
          // $journal->master_account_id= 8;
          $journal->authorized= true;
          $journal->approved= true;
          $journal->amount= number_format((float)(  $new_return->TotalAmount()), 2,'.','');
          $journal->tax_rate= $itemReturn->vat_rate;
          $journal->vat_amount= number_format((float)(  $new_return->vatAmount()), 2,'.','');
          $journal->total_amount= number_format((float)(  $new_return->taxableAmount()), 2,'.','');
          $journal->narration= "Sale return on ".$invoice->pay_mode;
          $journal->created_by= Auth::id();
            $journal->authorized_by= Auth::id();
            $journal->approved_by= Auth::id();
          $journal->save();


          //Account Receivable cr
          $journal_temps=new JournalRecord();
          $journal_temps->journal_id=$journal->id;
          $journal_temps->project_details_id=$new_return->inv->project_id;
          $journal_temps->cost_center_id=1;
          $journal_temps->party_info_id=$p_info->id;
          $journal_temps->journal_no= $journal->journal_no;
          $journal_temps->account_head_id= $new_return->inv->pay_mode=="Credit"? 19:219;
          $journal_temps->master_account_id= $new_return->inv->pay_mode=="Credit"? 3:2;
          $journal_temps->account_head= AccountHead::where('id',  $new_return->inv->pay_mode=="Credit"?19:219)->first()->fld_ac_head;
          $journal_temps->amount=$journal->amount;
          $journal_temps->transaction_type="CR";
          $journal_temps->journal_date= $journal->date;
          $journal_temps->save();


          //Vat Account dr
          $journal_temps=new JournalRecord();
          $journal_temps->journal_id=$journal->id;
          $journal_temps->project_details_id=$new_return->inv->project_id;
          $journal_temps->cost_center_id=1;
          $journal_temps->party_info_id=$p_info->id;
          $journal_temps->journal_no= $journal->journal_no;
          $journal_temps->account_head_id= 223;
          $journal_temps->master_account_id= 7;
          $journal_temps->account_head= AccountHead::where('id',223)->first()->fld_ac_head;
          $journal_temps->amount=$journal->vat_amount;
          $journal_temps->transaction_type="DR";
          $journal_temps->journal_date= $journal->date;
          $journal_temps->save();

          //Sales/Income dr
          $journal_temps=new JournalRecord();
          $journal_temps->journal_id=$journal->id;
          $journal_temps->project_details_id=$new_return->inv->project_id;
          $journal_temps->cost_center_id=1;
          $journal_temps->party_info_id=$p_info->id;
          $journal_temps->journal_no= $journal->journal_no;
          $journal_temps->account_head_id= 228;
          $journal_temps->master_account_id= 8;
          $journal_temps->account_head= AccountHead::where('id',228)->first()->fld_ac_head;
          $journal_temps->amount=$journal->total_amount;
          $journal_temps->transaction_type="DR";
          $journal_temps->journal_date= $journal->date;
          $journal_temps->save();
          //end journal

        //debit credit voucher
        $dr_cr_voucher= new DebitCreditVoucher();
        $dr_cr_voucher->journal_id      = $journal->id;
        $dr_cr_voucher->project_id      =  $journal->project_id;
        $dr_cr_voucher->cost_center_id  = 1;
        $dr_cr_voucher->party_info_id   =  $journal->party_info_id;
        $dr_cr_voucher->account_head_id = 0;
        $dr_cr_voucher->pay_mode        = $journal->pay_mode;
        $dr_cr_voucher->amount          = $journal->total_amount;
        $dr_cr_voucher->narration       = $journal->narration;
        $dr_cr_voucher->type            = $journal->pay_mode=="Credit"? "JOURNAL":"CR";
        $dr_cr_voucher->date            = $journal->date;
        $dr_cr_voucher->save();
        return redirect()->route('saleReturnPrint',$invoice=$new_return);

    }

    public function saleReturnPrint($invoiceF)
    {
        $invoice=SaleReturn::where('id',$invoiceF)->first();
        if(!$invoice)
        {
            $invoice=SaleReturn::where('sale_return_no',$invoiceF)->first();
        }

        return view('backend.pdf.saleReturnInvoice',compact('invoice'));

    }


    public function refresh_saleReturn(Request $request)
    {
        $saleReturns=  SaleReturn::distinct()->get(['invoice_no']);

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.saleReturnRight', ['saleReturns' => $saleReturns,'i' =>1])->render()
        ]);
        }

    }


    public function findItemIdSaleReturn(Request $request)
    {
        $item=InvoiceItem::where('id',$request->value)->first();
        $qty=$item->quantity;
        $r_qty=0;
        $hav_return=SaleReturn::where('invoice_id',$item->invoice_id)->get();
        foreach($hav_return as $return)
        {
            $item=SaleReturnItem::where('sale_return_id',$return->id)->where('item_id',$item->item_id)->first();
            if($item)
            {
                $r_qty=$r_qty+$item->quantity;
            }
        }

        $av_qty=$qty-$r_qty;

        $unit_price=$item->cost_price/$item->quantity;
        if ($request->ajax()) {
            return Response()->json([   'item' => $item,
                                        'av_qty' => $av_qty,
                                        'unit_price' =>number_format((float)(  $unit_price),'3','.',''),
                                        'cost_price' =>number_format((float)(  $unit_price),'2','.','')
                                        // 'price'=>number_format((float)($unit_price),2,'.',''),
                                        // 'net_amount' => number_format((float)($item->total_amount),2,'.','')
        ]);
        }

    }


    public function saleReturnQty(Request $request)
    {
        // return $request->all();
        $item=InvoiceItem::where('id',$request->item_name)->first();
        // return $item;

        $qty=$item->quantity;
        $r_qty=0;
        $hav_return=SaleReturn::where('invoice_id',$item->invoice_id)->get();
        foreach($hav_return as $return)
        {
            $item=SaleReturnItem::where('sale_return_id',$return->id)->where('item_id',$item->item_id)->first();
            if($item)
            {
                $r_qty=$r_qty+$item->quantity;
            }
        }

        $av_qty=$qty-$r_qty;



        if($av_qty < (int)$request->value)
            {
            return Response()->json(['error' =>"Invalid Entry. Invoice Quantity ". $av_qty,
                                        'fCost' => $request->fCost
                                    ]);
            }
        else
        {
            return Response()->json([
                                        'fCost' => $request->fCost
                                    ]);
        }
    }


}
