<?php

namespace App\Http\Controllers\backend;

use App\Branch;
use App\DebitCreditVoucher;
use App\DeliveryItem;
use App\DeliveryNote;
use App\DeliveryNoteSale;
use App\Fifo;
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
use App\PayMode;
use App\PayTerm;
use App\ProjectDetail;
use App\SaleOrder;
use App\SaleOrderItem;
use App\StockTransection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryNoteController extends Controller
{
    public function deliveryNote()
    {
        $sales=SaleOrder::latest()->paginate(60);
        return view('backend.deliveryNote.deliveryNote', compact('sales'));
    }

    public function saleOrderDetails($sale, Request $request)
    {
        $invoice=SaleOrder::find($sale);
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $customers=PartyInfo::get();
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $notes=DeliveryNote::all();
        $i=0;
        // return 1;
        if(!$invoice)
        {
            return back()->with('error', "Not Found");
        }

        $sub_invoice=Carbon::now()->format('Ymd');
        $dn_no=DeliveryNote::whereDate('created_at', Carbon::today())->where('delivery_note_no','LIKE',"%{$sub_invoice}%")->latest()->first();
        if($dn_no)
        {
            $dn_code=rtrim($dn_no->delivery_note_no,'DN');
            $no=$dn_code+1;
            $dcode=$no.'DN';
        }
        else
        {
            $dcode=Carbon::now()->format('Ymd').'01'.'DN';
        }

        $no=$dcode;
        // return $no;
        if ($request->ajax()) {
            // return 1;
            return Response()->json(['page' => view('backend.ajax.deliveryNoteDetails', ['invoice' => $invoice,'i' =>1, 'modes'=>$modes, 'terms'=> $terms, 'customers'=>$customers,'projects'=>$projects,'itms'=>$itms,'notes'=>$notes,'no' => $dcode])->render()
        ]);
        }

    }


    public function asignDeliveryNote(Request $request, $sale)
    {
        $request->validate([
            // 'proj_no' => 'required',
            'note_no'        => 'required',
        ],
        [
            'note_no.required' => 'Required',

        ]
    );
        $delivery_no=DeliveryNote::where('delivery_note_no', $request->note_no)->first();
        $deliverySale=DeliveryNoteSale::where('sale_order_id', $sale)->first();
        $invoice=SaleOrder::find($sale);
        if(!$delivery_no)
        {
            $delivery_no=new DeliveryNote();
            $delivery_no->delivery_note_no=$request->note_no;
            $delivery_no->save();
        }

        if(!$deliverySale)
        {
            $deliverySale=new DeliveryNoteSale();

                    // **************************Stock transection in delivery note**************************
                    //     $items=SaleOrderItem::where('sale_order_no',$invoice->sale_order_no)->get();
                    //     foreach($items as $item)
                    //    {
                    //     $stock=StockTransection::where('transection_id',$invoice->id)->where('item_id',$invoice->id)->first();
                    //     $latestStock=StockTransection::latest()->first();
                    //     if(!$stock)
                    //     {
                    //         $stock=new StockTransection();
                    //         $stock->transection_id=$invoice->id;
                    //         $stock->item_id=$item->item_id;
                    //     }
                    //     $stock->quantity=$item->quantity;
                    //     $stock->stock_effect = -1 ;
                    //     $stock->tns_type_code="S";
                    //     $stock->tns_description="Sales";
                    //     $stock->save();
                    //    }
                    // *********************************************************************
        }

        $deliverySale->sale_order_id=$sale;
        $deliverySale->delivery_note_id=$delivery_no->id;
        $deliverySale->save();
        return redirect()->route('updateNote', $invoice)->with('success','Delivery Note Stored');

    }

    public function updateNote($invoice)
    {
        $sales=SaleOrder::latest()->paginate(60);
        $invoice=SaleOrder::find($invoice);
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $customers=PartyInfo::get();
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $notes=DeliveryNote::all();
        $i=0;
        $sub_invoice=Carbon::now()->format('Ymd');
        $dn_no=DeliveryNote::whereDate('created_at', Carbon::today())->where('delivery_note_no','LIKE',"%{$sub_invoice}%")->latest()->first();
        if($dn_no)
        {
            $dn_code=rtrim($dn_no->delivery_note_no,'DN');
            $no=$dn_no->dn_code+1;
            $dcode=$no.'DN';
        }
        else
        {
            $dcode=Carbon::now()->format('Ymd').'01'.'DN';
        }
        $no=$dcode;

        return view('backend.deliveryNote.deliveryNote', compact('sales','invoice','modes','terms','customers','projects','itms','notes', 'i','no'));
    }

    public function deliveryNotePrint($invoice)
    {
        // dd($invoice);
        $dnote=DeliveryNote::find($invoice);
        $invoice=$dnote->deliverySale->saleOrder;
        return view('backend.pdf.deliveryNote',compact('dnote','invoice'));

    }

    // public function generateDeliveryNote(Request $request, $sale)
    // {
    //     $project=ProjectDetail::where('id',$request->branch)->first();
    //     $invoice=SaleOrder::find($sale);
    //     foreach($request->items as $key => $value)
    //     {
    //         $itm=ItemList::where('id',$request->items[$key]['item_id'])->first();
    //         $checkStock= $project->stockCheck($project->id,$request->items[$key]['item_id']);
    //                 if($checkStock < $request->items[$key]['quantity'])
    //                 {
    //                     return redirect()->route('updateNote', $invoice)->with('error','Not in Stock '.$itm->barcode.'. Available Quantity '.$checkStock);
    //                 }

    //     }
    //     $sub_invoice=Carbon::now()->format('Ymd');
    //     $dn_no=DeliveryNote::whereDate('created_at', Carbon::today())->where('delivery_note_no','LIKE',"%{$sub_invoice}%")->latest()->first();
    //     if($dn_no)
    //     {
    //         $dn_code=rtrim($dn_no->delivery_note_no,'DN');
    //         $no=$dn_code+1;
    //         $dcode=$no.'DN';
    //     }
    //     else
    //     {
    //         $dcode=Carbon::now()->format('Ymd').'01'.'DN';
    //     }
    //         $delivery_no=new DeliveryNote();
    //         $delivery_no->delivery_note_no=$dcode;
    //         $delivery_no->date=$request->date;
    //         $delivery_no->save();
    //         $deliverySale=new DeliveryNoteSale();
    //         $deliverySale->sale_order_id=$sale;
    //     $deliverySale->delivery_note_id=$delivery_no->id;
    //     $deliverySale->save();
    //     foreach($request->items as $key => $value)
    //     {
    //         $total_cost = 0;
    //         if($request->items[$key]['quantity'] != 0)
    //         {
    //             $fifo = Fifo::where('item_id', $request->items[$key]['id'])->where('remaining', '>', 0)->orderBy('id', 'ASC')->first();
    //             $quantityD = $request->items[$key]['quantity'];
    //             $qty=$quantityD;
    //             while ($quantityD > 0) {

    //                 if ($fifo) {
    //                     if ($fifo->remaining >= $quantityD) {
    //                         $cost = $fifo->unit_cost_price * $quantityD;
    //                         $total_cost = $total_cost + $cost;
    //                         $quantityD = 0;
    //                     } else {
    //                         $cost = $fifo->unit_cost_price * $fifo->remaining;
    //                         $total_cost = $total_cost + $cost;
    //                         $quantityD = $quantityD - $fifo->remaining;
    //                         $fifo = Fifo::where('id', '>', $fifo->id)->where('item_id', $request->items[$key]['id'])->where('remaining', '>', 0)->orderBy('id', 'ASC')->first();
    //                     }
    //                 }
    //             }
    //         $cost_price = $total_cost / $qty;
    //         $deliveries=new DeliveryItem;
    //         $deliveries->delivery_note_id=$delivery_no->id;;
    //         $deliveries->sale_order_id=$sale;
    //         $deliveries->item_id=$request->items[$key]['item_id'];
    //         $deliveries->sale_order_item_id= $request->items[$key]['id'];
    //         $deliveries->style_id= $request->items[$key]['style_id'];
    //         $deliveries->size=$request->items[$key]['size'];
    //         $deliveries->color_id=$request->items[$key]['color_id'];
    //         $deliveries->quantity=$request->items[$key]['quantity']==null? 0:$request->items[$key]['quantity'];
    //         $deliveries->purchase_price=$cost_price;
    //         $deliveries->save();
    //         }
    //         $stock=StockTransection::where('transection_id', $delivery_no->delivery_note_no)->where('item_id',$request->items[$key]['item_id'])->where('tns_type_code','S')->first();
    //         $latestStock=StockTransection::latest()->first();
    //         if(!$stock)
    //         {
    //             $stock=new StockTransection();
    //             $stock->transection_id=$delivery_no->delivery_note_no;
    //             $stock->item_id=$request->items[$key]['item_id'];

    //         }
    //         $stock->quantity=$request->items[$key]['quantity']==null? 0:$request->items[$key]['quantity'];
    //         $stock->stock_effect = -1 ;
    //         $stock->tns_type_code="S";
    //         $stock->tns_description="Sales";
    //         $stock->date=$request->date;
    //         $stock->save();
    //         $fifo = Fifo::where('item_id', $request->items[$key]['item_id'])->where('remaining', '>', 0)->orderBy('id', 'ASC')->first();
    //         $quantity = $request->items[$key]['quantity'];
    //         while ($quantity > 0) {
    //             if ($fifo) {
    //                 if ($fifo->remaining >= $quantity) {
    //                     $fifo->consumed = $fifo->consumed + $quantity;
    //                     $fifo->remaining = $fifo->remaining - $quantity;

    //                     $fifo->save();
    //                     $fifo_invoice = new FifoInvoice();
    //                     $fifo_invoice->fifo_id = $fifo->id;
    //                     $fifo_invoice->delivery_note_id =  $delivery_no->id;
    //                     $fifo_invoice->item_id = $request->items[$key]['item_id'];
    //                     $fifo_invoice->delivery_note_id = $delivery_no->id;
    //                     $fifo_invoice->quantity =$quantity;
    //                     $fifo_invoice->save();
    //                     $quantity = 0;
    //                 } else {
    //                     $fifo->consumed = $fifo->consumed + $fifo->remaining;
    //                     $quantity = $quantity - $fifo->remaining;
    //                     $inQty=$fifo->remaining;
    //                     $fifo->remaining = 0;
    //                     $fifo->save();
    //                     $fifo_invoice = new FifoInvoice();
    //                     $fifo_invoice->fifo_id = $fifo->id;
    //                     $fifo_invoice->delivery_note_id = $delivery_no->id;
    //                     $fifo_invoice->item_id = $request->items[$key]['item_id'];
    //                     $fifo_invoice->delivery_note_id = 0;
    //                     $fifo_invoice->quantity =$inQty;
    //                     $fifo_invoice->save();
    //                     $fifo = Fifo::where('id', '>', $fifo->id)->where('item_id', $request->items[$key]['item_id'])->where('remaining', '>', 0)->orderBy('id', 'ASC')->first();
    //                 }
    //             }
    //         }

    //     }
    //     return redirect()->route('updateNote', $invoice)->with('success','Delivery Note Generated');
    // }




    public function generateDeliveryNote(Request $request, $sale)
    {
        // return $request;
        $project=ProjectDetail::where('id',$request->branch)->first();
        $saleOrder=SaleOrder::find($sale);
        foreach($request->items as $key => $value)
        {
            $itm=ItemList::where('id',$request->items[$key]['item_id'])->first();
            $check=Fifo::where('item_id',$request->items[$key]['item_id'])->sum('remaining');

            $checkStock= $project->stockCheck($project->id,$request->items[$key]['item_id']);
                    if($checkStock < $request->items[$key]['quantity'])
                    {
                        return redirect()->route('updateNote', $saleOrder)->with('error','Not in Stock '.$itm->barcode.'. Available Quantity '.$checkStock);
                    }
                    if($check < $request->items[$key]['quantity'])
                    {
                        return redirect()->route('updateNote', $saleOrder)->with('error','Not in Stock '.$itm->barcode.'. Available Quantity '.$check);
                    }
        }
        $sub_dn_no=Carbon::now()->format('Ymd');
        $dn_no=DeliveryNote::whereDate('created_at', Carbon::today())->where('delivery_note_no','LIKE',"%{$sub_dn_no}%")->latest()->first();
        if($dn_no)
        {
            $dn_code=rtrim($dn_no->delivery_note_no,'DN');
            $no=$dn_code+1;
            $dcode=$no.'DN';
        }
        else
        {
            $dcode=Carbon::now()->format('Ymd').'01'.'DN';
        }
            $delivery_no=new DeliveryNote();
            $delivery_no->delivery_note_no=$dcode;
            $delivery_no->sale_order_id=$sale;
            $delivery_no->date=$request->date;
            $delivery_no->save();
            $sub_invoice = Carbon::now()->format('Ymd');
            $latest_invoice_no = Invoice::whereDate('created_at', Carbon::today())->where('invoice_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id','DESC')->first();
            if ($latest_invoice_no) {
                $invoice_no = $latest_invoice_no->invoice_no + 1;
            } else {
                $invoice_no = Carbon::now()->format('Ymd') . '001';
            }
                $invoiceNew = new Invoice();
                $invoiceNew->invoice_no = $invoice_no;
                $invoiceNew->delivery_note_id = $delivery_no->id;
                $invoiceNew->sale_order_id = $saleOrder->id;
                $invoiceNew->date = $saleOrder->date;
                $invoiceNew->project_id = $saleOrder->project_id;
                $invoiceNew->customer_name = $saleOrder->customer_name;
                $invoiceNew->trn_no = $saleOrder->trn_no;
                $invoiceNew->pay_mode = $saleOrder->pay_mode;
                $invoiceNew->pay_terms = $saleOrder->pay_terms;
                $invoiceNew->due_date = $saleOrder->due_date;
                $invoiceNew->contact_no = $saleOrder->contact_no;
                $invoiceNew->address = $saleOrder->address;
                $invoiceNew->gl_code = $saleOrder->gl_code;
                $invoiceNew->invoice_from = "Warehouse Sale";
                $invoiceNew->date =  $delivery_no->date;
                $invoiceNew->save();
            foreach($request->items as $key => $value)
            {
                $total_purchase_price = 0;
                if($request->items[$key]['quantity'] != 0)
                {
                $fifo = Fifo::where('item_id', $request->items[$key]['item_id'])->where('remaining', '>', 0)->orderBy('id', 'ASC')->first();
                $quantityD = $request->items[$key]['quantity'];
                $qty=$quantityD;
                while ($quantityD > 0) {
                    if ($fifo) {
                        if ($fifo->remaining >= $quantityD) {
                            $cost = $fifo->unit_cost_price * $quantityD;
                            $total_purchase_price = $total_purchase_price + $cost;
                            $quantityD = 0;
                        } else {
                            $cost = $fifo->unit_cost_price * $fifo->remaining;
                            $total_purchase_price = $total_purchase_price + $cost;
                            $quantityD = $quantityD - $fifo->remaining;
                            $fifo = Fifo::where('id', '>', $fifo->id)->where('item_id', $request->items[$key]['item_id'])->where('remaining', '>', 0)->orderBy('id', 'ASC')->first();
                        }
                    }
                }
                    $unit_purchase_price = $total_purchase_price / $qty;
                    $deliveries=new DeliveryItem;
                    $deliveries->delivery_note_id=$delivery_no->id;;
                    $deliveries->sale_order_id=$sale;
                    $deliveries->item_id=$request->items[$key]['item_id'];
                    $deliveries->sale_order_item_id= $request->items[$key]['id'];
                    $deliveries->style_id= $request->items[$key]['style_id'];
                    $deliveries->size=$request->items[$key]['size'];
                    $deliveries->color_id=$request->items[$key]['color_id'];
                    $deliveries->quantity=$request->items[$key]['quantity']==null? 0:$request->items[$key]['quantity'];
                    $deliveries->save();
                    $invoice_item = new InvoiceItem();
                    $invoice_item->invoice_no = $invoiceNew->invoice_no;
                    $invoice_item->invoice_id = $invoiceNew->id;
                    $invoice_item->barcode = $deliveries->saleItem->barcode;
                    $invoice_item->item_id = $deliveries->saleItem->item_id;
                    $invoice_item->style_id = $deliveries->style_id;
                    $invoice_item->size = $deliveries->size;
                    $invoice_item->color_id = $deliveries->color_id;
                    $invoice_item->net_amount = 1;
                    $invoice_item->quantity = $deliveries->quantity;
                    $invoice_item->vat_rate = $deliveries->saleItem->vat_rate;
                    $invoice_item->unit = $deliveries->saleItem->unit;
                    $invoice_item->total_unit_price = $deliveries->saleItem->total_unit_price / $deliveries->saleItem->quantity * $deliveries->quantity;
                    $invoice_item->cost_price = $deliveries->saleItem->cost_price / $deliveries->saleItem->quantity * $deliveries->quantity;
                    $invoice_item->unit_price = $deliveries->saleItem->unit_price;
                    $invoice_item->vat_amount = $invoice_item->total_unit_price * ($deliveries->saleItem->vat_rate / 100);
                    $invoice_item->purchase_price = $unit_purchase_price;
                    $invoice_item->save();

                    $stock = new StockTransection();
                    $stock->transection_id = $invoiceNew->id;
                    $stock->item_id = $invoice_item->item_id;
                    $stock->date = $request->date;
                    $stock->quantity =$invoice_item->quantity;
                    $stock->stock_effect = -1;
                    $stock->tns_type_code = "S";
                    $stock->tns_description = "Sales";
                    $stock->cost_price = $invoice_item->cost_price;
                    $stock->purchase_rate = $invoice_item->purchase_price;
                    $stock->save();
                    $fifo = Fifo::where('item_id', $invoice_item->item_id)->where('remaining', '>', 0)->orderBy('id', 'ASC')->first();
                    $quantity =  $stock->quantity;
                    while ($quantity > 0) {
                        if ($fifo) {
                            if ($fifo->remaining >= $quantity) {
                                $fifo->consumed = $fifo->consumed + $quantity;
                                $fifo->remaining = $fifo->remaining - $quantity;

                                $fifo->save();
                                $fifo_invoice = new FifoInvoice();
                                $fifo_invoice->fifo_id = $fifo->id;
                                $fifo_invoice->invoice_id =  $invoiceNew->id;
                                $fifo_invoice->item_id = $invoice_item->item_id;
                                $fifo_invoice->delivery_note_id = 0;
                                $fifo_invoice->quantity =$quantity;
                                $fifo_invoice->save();
                                $quantity = 0;
                            } else {
                                $fifo->consumed = $fifo->consumed + $fifo->remaining;
                                $quantity = $quantity - $fifo->remaining;
                                $inQty=$fifo->remaining;
                                $fifo->remaining = 0;
                                $fifo->save();
                                $fifo_invoice = new FifoInvoice();
                                $fifo_invoice->fifo_id = $fifo->id;
                                $fifo_invoice->invoice_id = $invoiceNew->id;
                                $fifo_invoice->item_id = $invoice_item->item_id;
                                $fifo_invoice->delivery_note_id = 0;
                                $fifo_invoice->quantity =$inQty;
                                $fifo_invoice->save();
                                $fifo = Fifo::where('id', '>', $fifo->id)->where('item_id', $invoice_item->item_id)->where('remaining', '>', 0)->orderBy('id', 'ASC')->first();
                            }
                        }
                    }
                        }
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
                    $journal->project_id=$project->id;
                    $journal->journal_no=$jcode;
                    $journal->date= $invoiceNew->date;
                    $journal->invoice_no=$invoiceNew->invoice_no;
                    $journal->cost_center_id= 1;
                    $p_info=PartyInfo::where('pi_code',$invoiceNew->customer_name)->first();
                    $journal->party_info_id= $p_info->id;
                    $journal->account_head_id= 228;
                    $journal->pay_mode=$invoiceNew->pay_mode;
                    $journal->authorized= true;
                    $journal->approved= true;
                    $journal->amount= number_format((float)(  $invoiceNew->TotalAmount()), 2,'.','');
                    $journal->tax_rate= $invoice_item->vat_rate;
                    $journal->vat_amount= number_format((float)(  $invoiceNew->vatAmount()), 2,'.','');
                    $journal->total_amount= number_format((float)(  $invoiceNew->taxableAmount()), 2,'.','');
                    $journal->narration="Good sales on ". $invoiceNew->pay_mode ;
                    $journal->created_by= Auth::id();
                    $journal->authorized_by= Auth::id();
                    $journal->approved_by= Auth::id();
                    $journal->save();
                    //Account Receivable Dr
                    $journal_temps=new JournalRecord();
                    $journal_temps->journal_id=$journal->id;
                    $journal_temps->project_details_id=$project->id;
                    $journal_temps->cost_center_id=1;
                    $journal_temps->party_info_id=$p_info->id;
                    $journal_temps->journal_no= $journal->journal_no;
                    $journal_temps->account_head_id= $invoiceNew->pay_mode=="Credit"? 19:219;
                    $journal_temps->master_account_id= $invoiceNew->pay_mode=="Credit"? 3:2;
                    $journal_temps->account_head= AccountHead::where('id',  $invoiceNew->pay_mode=="Credit"?19:219)->first()->fld_ac_head;
                    $journal_temps->amount=$journal->amount;
                    $journal_temps->transaction_type="DR";
                    $journal_temps->journal_date= $journal->date;
                    $journal_temps->save();

                    //Vat Account Cr
                    $journal_temps=new JournalRecord();
                    $journal_temps->journal_id=$journal->id;
                    $journal_temps->project_details_id=$project->id;
                    $journal_temps->cost_center_id=1;
                    $journal_temps->party_info_id=$p_info->id;
                    $journal_temps->journal_no= $journal->journal_no;
                    $journal_temps->account_head_id= 223;
                    $journal_temps->master_account_id= 7;
                    $journal_temps->account_head= AccountHead::where('id',223)->first()->fld_ac_head;
                    $journal_temps->amount=$journal->vat_amount;
                    $journal_temps->transaction_type="CR";
                    $journal_temps->journal_date= $journal->date;
                    $journal_temps->save();

                    //Sales/Income Cr
                    $journal_temps=new JournalRecord();
                    $journal_temps->journal_id=$journal->id;
                    $journal_temps->project_details_id=$project->id;
                    $journal_temps->cost_center_id=1;
                    $journal_temps->party_info_id=$p_info->id;
                    $journal_temps->journal_no= $journal->journal_no;
                    $journal_temps->account_head_id= 228;
                    $journal_temps->master_account_id= 8;
                    $journal_temps->account_head= AccountHead::where('id',228)->first()->fld_ac_head;
                    $journal_temps->amount=$journal->total_amount;
                    $journal_temps->transaction_type="CR";
                    $journal_temps->journal_date= $journal->date;
                    $journal_temps->save();
                    //end journal

                    //debit credit voucher
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
                    return redirect()->route('updateNote', $saleOrder)->with('success','Delivery Note & Invoice Succesfully Generated');
    }



    public function searchSODNo(Request $request)
    {
        $so=SaleOrder::where('sale_order_no', 'LIKE', "%{$request->value}%")->get();
        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.saleOrderDeliveryNotSearch', ['sales' => $so,'i' =>1])->render()
        ]);
        }
    }


    public function searchSODNoMonth(Request $request)
    {
        $year=substr($request->value, 0, 4);
        $month=substr($request->value, 5, 8);
        $so=SaleOrder::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->get();
        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.saleOrderDeliveryNotSearch', ['sales' => $so,'i' =>1])->render()
        ]);
        }
    }

    public function searchSODNoDate(Request $request)
    {
        $so=SaleOrder::whereDate('created_at', $request->value)->get();
        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.saleOrderDeliveryNotSearch', ['sales' => $so,'i' =>1])->render()
        ]);
        }
    }

    public function searchSODNoDateRange(Request $request)
    {
        $so=SaleOrder::whereBetween('created_at', [$request->from, $request->to])->get();
        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.saleOrderDeliveryNotSearch', ['sales' => $so,'i' =>1])->render()
        ]);
        }

    }

    public function dnList()
    {
        $dNotes=DeliveryNote::latest()->paginate(60);
        return view('backend.deliveryNote.deliveryNoteList',compact('dNotes'));
    }

    public function deliveryNoteDetails(Request $request,$dnote)
    {
        $deliveryNote=DeliveryNote::find($dnote);
        $saleOrder=$deliveryNote->saleOrder;

        $modes=PayMode::get();
        $terms=PayTerm::get();
        $customers=PartyInfo::get();
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $notes=DeliveryNote::all();
        $i=0;

        if(!$saleOrder)
        {
            return back()->with('error', "Not Found");
        }

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.deliveryNoteDetailsView', ['invoice' => $saleOrder,'i' =>1, 'modes'=>$modes, 'terms'=> $terms, 'customers'=>$customers,'projects'=>$projects,'itms'=>$itms,'notes'=>$notes, 'dnote' =>$dnote,'deliveryNote'=> $deliveryNote])->render(),
                                        'dnote'=>$dnote
        ]);
        }
    }




    public function findDNo(Request $request)
    {
        $dNotes=DeliveryNote::where('delivery_note_no', 'LIKE', "%{$request->value}%")->get();
        // $dNotes=DeliveryNoteSale::where('delivery_note_no', 'LIKE', "%{$request->value}%")->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotFind', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }


    public function findDNoMonth(Request $request)
    {
        $year=substr($request->value, 0, 4);
        $month=substr($request->value, 5, 8);
        $dNotes=DeliveryNote::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotFind', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }


    public function findDNoDate(Request $request)
    {

        $dNotes=DeliveryNote::whereDate('created_at', $request->value)->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotFind', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }


    public function findDNoDateRange(Request $request)
    {

        $dNotes=DeliveryNote::whereBetween('created_at', [$request->from, $request->to])->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotFind', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }

    public function deliverySummery()
    {
        $dNotes=DeliveryNote::latest()->paginate(60);
        return view('backend.deliveryNote.deliverySummary', compact('dNotes'));

    }

    public function deliveryNotesummery($dnote, Request $request)
    {

        $deliveryNote=DeliveryNote::find($dnote);
        // return $deliveryNote;
        $saleOrder=$deliveryNote->deliverySale->saleOrder;
        // return $saleOrder;
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $customers=PartyInfo::get();
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $notes=DeliveryNote::all();
        $i=0;
        if(!$saleOrder)
        {
            return back()->with('error', "Not Found");
        }

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.deliverySummery', ['invoice' => $saleOrder,'i' =>1, 'modes'=>$modes, 'terms'=> $terms, 'customers'=>$customers,'projects'=>$projects,'itms'=>$itms,'notes'=>$notes, 'dnote' =>$dnote])->render(),
                                        'dnote'=>$dnote
        ]);
        }

    }

    public function searchDNo(Request $request)
    {
        $dNotes=DeliveryNote::where('delivery_note_no', 'LIKE', "%{$request->value}%")->get();
        // $dNotes=DeliveryNoteSale::where('delivery_note_no', 'LIKE', "%{$request->value}%")->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotSearchS', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }

    public function searchDNoMonth(Request $request)
    {
        $year=substr($request->value, 0, 4);
        $month=substr($request->value, 5, 8);
        $dNotes=DeliveryNote::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotSearchS', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }

    public function searchDNoDate(Request $request)
    {

        $dNotes=DeliveryNote::whereDate('created_at', $request->value)->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotSearchS', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }

    public function searchDNoDateRange(Request $request)
    {

        $dNotes=DeliveryNote::whereBetween('created_at', [$request->from, $request->to])->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotSearchS', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }

    public function deliveryNoteView($dnote )
    {

        $deliveryNote=DeliveryNote::find($dnote);
        $invoice=$deliveryNote->saleOrder;

        $modes=PayMode::get();
        $terms=PayTerm::get();
        $customers=PartyInfo::get();
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $notes=DeliveryNote::all();
        $i=1;
        $dNotes=DeliveryNote::latest()->paginate(60);
        // dd($dNote);
        if(!$invoice)
        {
            return back()->with('error', "Not Found");
        }

        return view('backend.deliveryNote.deliveryNoteView', compact('invoice','i','modes','terms','customers','projects','itms','notes','dNotes','deliveryNote','dnote'));

    }


    public function deliveryNoteInvoice($invoice)
    {
        // dd($invoice);
        $invoice = Invoice::where('id', $invoice)->first();
        $modes = PayMode::get();
        $terms = PayTerm::get();
        $branches = Branch::get();
        $customers = PartyInfo::get();
        $invoicess = Invoice::where('invoice_from','Counter Sale')->orderBy('id','DESC')->paginate(25);
        $projects = ProjectDetail::get();
        $itms = ItemList::get();
        $i = 0;
        $invoicess = Invoice::where('invoice_from','Warehouse Sale')->orderBy('id','DESC')->paginate(25);
        return view('backend.taxInvoice.salesTaxtInvoiceIssue', compact('invoicess','invoice', 'modes', 'terms', 'branches', 'customers',  'projects', 'itms', 'i'));
    }


}
