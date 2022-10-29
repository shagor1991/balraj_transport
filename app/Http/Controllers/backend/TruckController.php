<?php

namespace App\Http\Controllers\backend;

use App\DebitCreditVoucher;
use App\Http\Controllers\Controller;
use App\InvoiceItem;
use App\Journal;
use App\JournalRecord;
use App\Models\AccountHead;
use App\Models\CostCenter;
use App\PartyInfo;
use App\PayMode;
use App\ProjectDetail;
use App\SupplierInvoice;
use App\SupplierInvoiceItem;
use App\TaxInvoice;
use App\TaxInvoiceItem;
use App\Truck;
use App\TruckRecords;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries= DB::table('countries')->get();
        $parties= PartyInfo::where('pi_type','Supplier')->get();
        $trucks= Truck::all();
        
        return view('backend.truck.truck-entry',compact('countries','parties','trucks'));
    }


    public function truck_service(){
        $trucks= Truck::all();
        $customers= PartyInfo::where('pi_type','Customer')->get();
        $suppliers= PartyInfo::where('pi_type','Supplier')->get();
        $records= TruckRecords::orderBy('id','desc')->paginate(15)->withQueryString();
        // $students= $students->paginate(20)->withQueryString();
        // return $records;
        return view('backend.truck.weigh-bridge-index', compact('customers', 'trucks','suppliers','records'));
    }

    public function add_to_session(Request $request){
        $data['party']      =$request->fld_customer;
        $data['date']      =$request->fld_date;
        $data['truck']      =$request->fld_truck;
        $data['vehicle_no'] =$request->vehicle_no;
        $data['material']   =$request->fld_material;
        $data['crusher']    =$request->fld_crusher;
        $data['dstn']       =$request->fld_dstn;
        $data['serial']     =$request->fld_serial;
        $data['wight']      =$request->fld_wight;
        $data['truck_owner']=$request->fld_truck_owner;
        $data['truck_owner_name']=$request->truck_owner_name;

        
        Session::push('items', $data);
        return $request->session()->get('items');
    }

    public function remove_item_from_session(Request $request){
        // $request->session()->forget('items'); return 'success';
        $items = Session::get('items'); //step 1
        unset($items[$request->data_id]);//step 2
        Session::put('items', $items);  //step 3
        return $request->session()->get('items');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        $request->validate(
            [
                'vehicle_number'        => 'required',
                'brand'                 => 'required',
                'model'                 => 'required',
                'origin'                => 'required',
                'engine_capacity'       => 'required',
                'number_of_tyres'       => 'required',
                'owner'                 => 'required',
            ],
            [
                'vehicle_number.required'   => 'Vehicle number is required',
                'brand.required'            => 'Brand is required',
                'model.required'            => 'Model is required',
                'origin.required'           => 'Origin  is required',
                'engine_capacity.required'  => 'Engine Capacity is required',
                'number_of_tyres.required'  => 'Number of tyres is required',
                'owner.required'            => 'Owner is required',

            ]
        );

        
        $truck= new Truck();
        $truck->vehicle_number      = $request->vehicle_number;
        $truck->brand               = $request->brand;
        $truck->model               = $request->model;
        $truck->origin              = $request->origin;
        $truck->engine_capacity     = $request->engine_capacity;
        $truck->no_of_tyres         = $request->number_of_tyres;
        $truck->owner               = $request->owner;
        $truck->save();

        $notification= array(
            'message'       => 'Truck added successfully!',
            'alert-type'    => 'success'
        );

        return back()->with($notification);
    }

    public function save_truck_service(Request $request){
        // return $request;
        
        $records = $request->session()->get('items');
        // return $records;
        if ($request->session()->has('items')) {
            foreach($request->session()->get('items') as $record){
                // return $record['truck'];
                $t_record= new TruckRecords;
                $t_record->truck_id= $record['truck'];
                $t_record->customer_id= $record['party'];
                $t_record->material= $record['material'];
                $t_record->crusher= $record['crusher'];
                $t_record->destination= $record['dstn'];
                $t_record->serial_no= $record['serial'];
                $t_record->weight= $record['wight'];
                $t_record->date= $record['date'];
                $t_record->truck_owner= $record['truck_owner'];
                $t_record->save();
            }
            $request->session()->forget('items');
        }

        $notification= array(
            'message'       => 'Truck records added!',
            'alert-type'    => 'success'
        );

        return back()->with($notification);
    }

    public function customer_invoice(Request $request){
        // return $request;
        $trucks= Truck::all();
        $customers= PartyInfo::where('pi_type','Customer')->get();
        $suppliers= PartyInfo::where('pi_type','Supplier')->get();
        
        if($request->has('customer_id')){
            $customer_id=$request->customer_id;
            
            if($request->from !='' && $request->to !=''){
                $records= TruckRecords::where('customer_id',$request->customer_id)->where('is_invoiced', 0)->whereBetween('date',[$request->from,$request->to])->orderBy('id','desc')->paginate(15)->withQueryString();
            }else{
                $records= TruckRecords::where('customer_id',$request->customer_id)->where('is_invoiced', 0)->orderBy('id','desc')->paginate(15)->withQueryString();
            }
                        
            return view('backend.truck.customer-invoice', compact('customers', 'trucks','suppliers','records','customer_id'));
        }
        

        return view('backend.truck.customer-invoice', compact('customers', 'trucks','suppliers'));
    }

    public function supplier_invoice(Request $request){
        // return $request;
        $trucks= Truck::all();
        $customers= PartyInfo::where('pi_type','Customer')->get();
        $suppliers= PartyInfo::where('pi_type','Supplier')->get();
        
        if($request->has('supplier_id')){
            $supplier_id=$request->supplier_id;
            
            if($request->from !='' && $request->to !=''){
                $records= TaxInvoiceItem::where('supplier_id',$request->supplier_id)->where('is_invoiced', 0)->whereBetween('date',[$request->from,$request->to])->orderBy('id','desc')->paginate(15)->withQueryString();
            }else{
                $records= TaxInvoiceItem::where('supplier_id',$request->supplier_id)->where('is_invoiced', 0)->orderBy('id','desc')->paginate(15)->withQueryString();
            }
            // return $records;
                        
            return view('backend.truck.supplier-invoice', compact('customers', 'trucks','suppliers','records','supplier_id'));
        }
        

        return view('backend.truck.supplier-invoice', compact('customers', 'trucks','suppliers'));
    }

    public function customer_invoice_process(Request $request){
        // return $request;
        $customer_id= $request->customer_id;
        $records=$request->records;
        $records= TruckRecords::whereIn('id',$records)->get();
        $customers= PartyInfo::where('pi_type','Customer')->get();
        $projects= ProjectDetail::all();
        $cost_centers= CostCenter::all();
        $pay_modes= PayMode::all();
        // return $projects;
        return view('backend.truck.invoice-process', compact('customers','records','customer_id','projects','cost_centers','pay_modes'));
    }

    public function supplier_invoice_process(Request $request){
        // return $request;
        $supplier_id= $request->supplier_id;
        $records=$request->records;
        $records= TaxInvoiceItem::whereIn('id',$records)->get();
        $suppliers= PartyInfo::where('pi_type','Supplier')->get();
        $projects= ProjectDetail::all();
        $cost_centers= CostCenter::all();
        $pay_modes= PayMode::all();
        // return $projects;
        return view('backend.truck.supplier-invoice-process', compact('suppliers','records','supplier_id','projects','cost_centers','pay_modes'));
    }

    public function save_customer_invoice(Request $request){
                
        $latest_inv_no = TaxInvoice::latest()->first();
        if ($latest_inv_no) {
            $invoice_no= $latest_inv_no->invoice_no+1;
        } else {
            $invoice_no = Carbon::now()->format('Ym') . '000001';
        }        
        
        $tax_invoice                = new TaxInvoice;
        $tax_invoice->invoice_no    = $invoice_no;
        $tax_invoice->customer_id   = $request->customer_id;
        $tax_invoice->project_id    = $request->project;
        $tax_invoice->date          = $request->date;
        $tax_invoice->pay_mode      = $request->pay_mode;
        $tax_invoice->amount        = $request->total_amount;
        $tax_invoice->vat_amount    = $request->total_vat;
        $tax_invoice->paid_amount   = $request->payment_amount;
        $tax_invoice->due_amount    = $request->due_amount;
        $tax_invoice->save();

        
        $i=0;
        $total_amount= 0;
        $total_vat=0;
        foreach($request->record_id as $record){
            $record_data= TruckRecords::find($record);
            // return $record_data->destination;
            $rate= $request->rate[$i];
            $amount= $record_data->weight*$rate;
            $v_amount= $amount * $request->v_rate / 100;
            $total_amount= $total_amount+ $amount;
            $total_vat= $total_vat + $v_amount;

            $desc= $record_data->crusher.' To '.$record_data->destination.'('.$record_data->material.') '.$record_data->serial_no; 

            $inv_item                   = new TaxInvoiceItem();
            $inv_item->invoice_id       = $tax_invoice->id;
            $inv_item->invoice_no       = $tax_invoice->invoice_no;
            $inv_item->item_id          = $record;
            $inv_item->truck_id         = $record_data->truck_id;
            $inv_item->customer_id      = $request->customer_id;
            $inv_item->description      = $desc;
            $inv_item->crusher          = $record_data->crusher;
            $inv_item->destination      = $record_data->destination;
            $inv_item->qty              = $record_data->weight;
            $inv_item->rate             = $rate;
            $inv_item->amount           = $amount;
            $inv_item->vat_rate         = $request->v_rate;
            $inv_item->vat_amount       = $v_amount;
            $inv_item->record_date      = $record_data->date;
            $inv_item->supplier_id      = $record_data->truck_owner;
            $inv_item->date             = $request->date;
            $inv_item->save();

            // update record as is_invoiced=1
            $record_data->is_invoiced   =1;
            $record_data->save();

            $i++;
        }

        $tax_invoice->amount        = $total_amount;
        $tax_invoice->vat_amount    = $total_vat;
        $tax_invoice->paid_amount   = $request->payment_amount;
        $tax_invoice->due_amount    = $total_amount+ $total_vat - $request->payment_amount;
        $tax_invoice->save();

        // Journal Entry 
        $sub_invoice = Carbon::now()->format('Ymd');

        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->latest()->first();
        
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no,0,-1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }
        $journal= new Journal();
        $journal->project_id        = $request->project;
        $journal->journal_no        = $journal_no;
        $journal->date              = $request->date;
        $journal->pay_mode          = $request->pay_mode;
        $journal->invoice_no        = $tax_invoice->invoice_no;
        $journal->cost_center_id    = $request->cost_center;
        $journal->party_info_id     = $request->customer_id;
        $journal->account_head_id   = 123;
        $journal->amount            = $total_amount;
        $journal->tax_rate          = $request->v_rate;
        $journal->vat_amount        = $total_vat;
        $journal->total_amount      = $total_amount+ $total_vat;
        $journal->narration         = 'Transport service provided by '. $request->pay_mode;
        $journal->created_by        = Auth::id();
        $journal->voucher_type      = 'default';
        $journal->save();

        // Main Entry 
        $acc_head= AccountHead::find(31);
        $jl_record= new JournalRecord();
        $jl_record->journal_id          = $journal->id;
        $jl_record->project_details_id  = $request->project;
        $jl_record->cost_center_id      = $request->cost_center;
        $jl_record->party_info_id       = $request->customer_id;
        $jl_record->journal_no          = $journal->journal_no;
        $jl_record->account_head_id     = $acc_head->id;
        $jl_record->master_account_id   = $acc_head->master_account_id;
        $jl_record->account_head        = $acc_head->fld_ac_head;
        $jl_record->amount              = $total_amount;
        $jl_record->transaction_type    = 'CR';
        $jl_record->journal_date        = $request->date;
        $jl_record->save();

        // vat entry to journal
        if($total_vat>0){
            $vat_ac_head= AccountHead::find(32); // vat account
            $jl_record= new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $request->project;
            $jl_record->cost_center_id      = $request->cost_center;
            $jl_record->party_info_id       = $request->customer_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $vat_ac_head->id;
            $jl_record->master_account_id   = $vat_ac_head->master_account_id;
            $jl_record->account_head        = $vat_ac_head->fld_ac_head;
            $jl_record->amount              = $total_vat;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $request->date;
            $jl_record->save();
        }

        // Opposit entry of journal
        if($request->pay_mode=='Cash' || $request->pay_mode=='Card'){
            $ac_head= AccountHead::find(1);
            
            $jl_record= new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $request->project;
            $jl_record->cost_center_id      = $request->cost_center;
            $jl_record->party_info_id       = $request->customer_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $total_amount+ $total_vat;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $request->date;
            $jl_record->save();

        }elseif($request->pay_mode=='Credit'){
            $acc_head= AccountHead::find(33);
            $jl_record= new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $request->project;
            $jl_record->cost_center_id      = $request->cost_center;
            $jl_record->party_info_id       = $request->customer_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $acc_head->id;
            $jl_record->master_account_id   = $acc_head->master_account_id;
            $jl_record->account_head        = $acc_head->fld_ac_head;
            $jl_record->amount              = $total_amount+ $total_vat;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $request->date;
            $jl_record->save();
            
        }

        //Debit Voucher Or Credit Voucher 
        $voucher_type="DR";
        if( $request->pay_mode == 'Cash' || $request->pay_mode == 'Card'){
            // if it is income, liability or equity
            $voucher_type = 'CR';
        }elseif($request->pay_mode == 'Credit'){
            $voucher_type            = 'JOURNAL';
        }

        $journal->voucher_type          = $voucher_type;
        $journal->save();

        $dr_cr_voucher= new DebitCreditVoucher();
        $dr_cr_voucher->journal_id      = $journal->id;
        $dr_cr_voucher->project_id      = $request->project;
        $dr_cr_voucher->cost_center_id  = $request->cost_center;
        $dr_cr_voucher->party_info_id   = $request->customer_id;
        $dr_cr_voucher->account_head_id = 0;
        $dr_cr_voucher->pay_mode        = $request->pay_mode;
        $dr_cr_voucher->amount          = $total_amount+ $total_vat;
        $dr_cr_voucher->narration       = 'Transport service provided by '. $request->pay_mode;
        $dr_cr_voucher->type            = $voucher_type;
        $dr_cr_voucher->date            = $request->date;
        $dr_cr_voucher->save();


        $notification= array(
            'message'       => 'Invoice generated successfully!',
            'alert-type'    => 'success'
        );

        return redirect('customer-invoice')->with($notification);
    }

    public function save_supplier_invoice(Request $request){
        // return $request;
        // return $record_data= TruckRecords::whereIn('id', $request->record_id)->get();
        // return $record= TruckRecords::find(8);
        
        $latest_inv_no = SupplierInvoice::latest()->first();
        if ($latest_inv_no) {
            $invoice_no= $latest_inv_no->invoice_no+1;
        } else {
            $invoice_no = Carbon::now()->format('Ym') . '000001';
        }        
        
        $tax_invoice                = new SupplierInvoice();
        $tax_invoice->invoice_no    = $invoice_no;
        $tax_invoice->supplier_id   = $request->supplier_id;
        $tax_invoice->project_id    = $request->project;
        $tax_invoice->date          = $request->date;
        $tax_invoice->pay_mode      = $request->pay_mode;
        $tax_invoice->amount        = $request->total_amount;
        $tax_invoice->vat_amount    = $request->total_vat;
        $tax_invoice->paid_amount   = $request->payment_amount;
        $tax_invoice->due_amount    = $request->due_amount;
        $tax_invoice->invoice_scan  = 'default.jpg';
        $tax_invoice->save();

        
        $i=0;
        $total_amount= 0;
        $total_vat=0;
        foreach($request->record_id as $record){
            $record_data= TaxInvoiceItem::find($record);
            // return $record_data->destination;
            $rate= $request->rate[$i];
            $amount= $record_data->qty*$rate;
            $v_amount= $amount * $request->v_rate / 100;
            $total_amount= $total_amount+ $amount;
            $total_vat= $total_vat + $v_amount;

            $desc= $record_data->crusher.' To '.$record_data->destination.'('.$record_data->record->material.') '.$record_data->record->serial_no; 

            $inv_item                   = new SupplierInvoiceItem();
            $inv_item->invoice_id       = $tax_invoice->id;
            $inv_item->invoice_no       = $tax_invoice->invoice_no;
            $inv_item->invoice_item_id  = $record;
            $inv_item->record_id        = $record_data->record->id;
            $inv_item->truck_id         = $record_data->truck_id;
            $inv_item->supplier_id      = $request->supplier_id;
            $inv_item->customer_id      = $record_data->customer_id;
            $inv_item->description      = $desc;
            $inv_item->crusher          = $record_data->crusher;
            $inv_item->destination      = $record_data->destination;
            $inv_item->qty              = $record_data->qty;
            $inv_item->rate             = $rate;
            $inv_item->amount           = $amount;
            $inv_item->vat_rate         = $request->v_rate;
            $inv_item->vat_amount       = $v_amount;
            $inv_item->date             = $request->date;
            $inv_item->save();

            // update status of invoice item: is_invoiced=1
            $record_data->is_invoiced=1;
            $record_data->save();

            $i++;
        }

        $tax_invoice->amount        = $total_amount;
        $tax_invoice->vat_amount    = $total_vat;
        $tax_invoice->paid_amount   = $request->payment_amount;
        $tax_invoice->due_amount    = $total_amount+ $total_vat - $request->payment_amount;
        $tax_invoice->save();

        // Journal Entry 
        $sub_invoice = Carbon::now()->format('Ymd');

        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->latest()->first();
        
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no,0,-1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }
        $journal= new Journal();
        $journal->project_id        = $request->project;
        $journal->journal_no        = $journal_no;
        $journal->date              = $request->date;
        $journal->pay_mode          = $request->pay_mode;
        $journal->invoice_no        = $tax_invoice->invoice_no;
        $journal->cost_center_id    = $request->cost_center;
        $journal->party_info_id     = $request->supplier_id;
        $journal->account_head_id   = 123;
        $journal->amount            = $total_amount;
        $journal->tax_rate          = $request->v_rate;
        $journal->vat_amount        = $total_vat;
        $journal->total_amount      = $total_amount+ $total_vat;
        $journal->narration         = 'Payment to 3rd party supplier by '. $request->pay_mode;
        $journal->created_by        = Auth::id();
        $journal->voucher_type      = 'default';
        $journal->save();

        // Main Entry 
        $acc_head= AccountHead::find(34); // 3rd party supplier account head
        $jl_record= new JournalRecord();
        $jl_record->journal_id          = $journal->id;
        $jl_record->project_details_id  = $request->project;
        $jl_record->cost_center_id      = $request->cost_center;
        $jl_record->party_info_id       = $request->customer_id;
        $jl_record->journal_no          = $journal->journal_no;
        $jl_record->account_head_id     = $acc_head->id;
        $jl_record->master_account_id   = $acc_head->master_account_id;
        $jl_record->account_head        = $acc_head->fld_ac_head;
        $jl_record->amount              = $total_amount;
        $jl_record->transaction_type    = 'DR';
        $jl_record->journal_date        = $request->date;
        $jl_record->save();

        // vat entry to journal
        if($total_vat>0){
            $vat_ac_head= AccountHead::find(32); // vat account
            $jl_record= new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $request->project;
            $jl_record->cost_center_id      = $request->cost_center;
            $jl_record->party_info_id       = $request->customer_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $vat_ac_head->id;
            $jl_record->master_account_id   = $vat_ac_head->master_account_id;
            $jl_record->account_head        = $vat_ac_head->fld_ac_head;
            $jl_record->amount              = $total_vat;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $request->date;
            $jl_record->save();
        }

        // Opposit entry of journal
        if($request->pay_mode=='Cash' || $request->pay_mode=='Card'){
            $ac_head= AccountHead::find(1);
            
            $jl_record= new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $request->project;
            $jl_record->cost_center_id      = $request->cost_center;
            $jl_record->party_info_id       = $request->customer_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $total_amount+ $total_vat;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $request->date;
            $jl_record->save();

        }elseif($request->pay_mode=='Credit'){
            $acc_head= AccountHead::find(35);
            $jl_record= new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $request->project;
            $jl_record->cost_center_id      = $request->cost_center;
            $jl_record->party_info_id       = $request->customer_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $acc_head->id;
            $jl_record->master_account_id   = $acc_head->master_account_id;
            $jl_record->account_head        = $acc_head->fld_ac_head;
            $jl_record->amount              = $total_amount+ $total_vat;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $request->date;
            $jl_record->save();
            
        }

        //Debit Voucher Or Credit Voucher 
        $voucher_type="DR";
        if( $request->pay_mode == 'Cash' || $request->pay_mode == 'Card'){
            // if it is expense or asset
            $voucher_type = 'DR';
        }elseif($request->pay_mode == 'Credit'){
            $voucher_type            = 'JOURNAL';
        }

        $journal->voucher_type          = $voucher_type;
        $journal->save();

        $dr_cr_voucher= new DebitCreditVoucher();
        $dr_cr_voucher->journal_id      = $journal->id;
        $dr_cr_voucher->project_id      = $request->project;
        $dr_cr_voucher->cost_center_id  = $request->cost_center;
        $dr_cr_voucher->party_info_id   = $request->supplier_id;
        $dr_cr_voucher->account_head_id = 0;
        $dr_cr_voucher->pay_mode        = $request->pay_mode;
        $dr_cr_voucher->amount          = $total_amount+ $total_vat;
        $dr_cr_voucher->narration       = 'Payment to 3rd party supplier '. $request->pay_mode;
        $dr_cr_voucher->type            = $voucher_type;
        $dr_cr_voucher->date            = $request->date;
        $dr_cr_voucher->save();


        $notification= array(
            'message'       => 'Invoice generated successfully!',
            'alert-type'    => 'success'
        );

        return redirect('supplier')->with($notification);
    }

    public function invoice_list(){
        $invoices= TaxInvoice::all();
        return view('backend.truck.invoice-list', compact('invoices'));
    }

    public function supplier_invoice_list(){
        $invoices= SupplierInvoice::all();
        return view('backend.truck.supplier-invoice-list', compact('invoices'));
    }

    public function invoice_view($id){
        $invoice= TaxInvoice::find($id);
        return view('backend.truck.invoice-view', compact('invoice'));
    }

    public function invoice_print($id){
        $invoice= TaxInvoice::find($id);
        return view('backend.truck.invoice', compact('invoice'));
    }

    public function supplier_invoice_print($id){
        $invoice= SupplierInvoice::find($id);
        return view('backend.truck.print-supplier-invoice', compact('invoice')); 
    }

    public function supplier_invoice_view($id){
        $invoice= SupplierInvoice::find($id);
        return view('backend.truck.supplier-invoice-view', compact('invoice'));
    }

    public function invoice_sumview($id){
        $invoice= TaxInvoice::find($id);
        $invoice_items= DB::table('tax_invoice_items')
        ->select('crusher', 'destination','rate','vat_rate', DB::raw('sum(qty) as total_qty'))
        ->groupBy('crusher','destination','rate','vat_rate')
        ->where('invoice_id', $id)
        ->get();
        // return $invoice_items;
        return view('backend.truck.invoice-sumview', compact('invoice','invoice_items'));
    }

    public function supplier_invoice_sumview($id){
        $invoice= SupplierInvoice::find($id);
        $invoice_items= DB::table('supplier_invoice_items')
        ->select('crusher', 'destination','rate','vat_rate', DB::raw('sum(qty) as total_qty'))
        ->groupBy('crusher','destination','rate','vat_rate')
        ->where('invoice_id', $id)
        ->get();
        // return $invoice_items;
        return view('backend.truck.supplier-invoice-sumview', compact('invoice','invoice_items'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
