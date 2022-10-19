<?php

namespace App\Http\Controllers\backend;

use App\Brand;
use App\Expense;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\JournalRecord;
use App\Models\AccountHead;
use App\Models\MasterAccount;
use App\PartyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function business_summary_report(){
        $brands=Brand::latest()->get();
        return view('backend.business-summary-report.summary-report', compact('brands'));
    }

    public function business_summary_report_print(){
        return view('backend.business-summary-report.business-summary-print');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expenses= Expense::paginate(15);
        
        $master_accounts=MasterAccount::all();
        $parties= PartyInfo::whereIn('pi_type', ['Supplier','Employee','Government Body'])->get();
        return view('backend.expense.create', compact('master_accounts', 'parties','expenses'));
    }

    public function get_account_heads(Request $request){
        $account_head= AccountHead::where('ma_code', $request->ac_code)->get();
        return response()->json($account_head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $this->validate($request, [
                'master_acount'     => 'required',
                'account_head'      => 'required',
                'party_name'        => 'required',
                'date'     => 'required',
                'taxable_amount'    => 'required',
                'vat_amount'        => 'required',
                'total_amount'      => 'required',
                'voucher_copy'      => 'required',
                'voucher_copy.*'    => 'mimes:doc,pdf,docx,jpg,png,jpeg'
        ]);

            
        $name= $request->voucher_copy->getClientOriginalName();
        $name = pathinfo($name, PATHINFO_FILENAME);
        $ext= $request->voucher_copy->getClientOriginalExtension();
        $voucher_file_name= $name.time().'.'.$ext;
        
        $file_transfer= $request->voucher_copy->storeAs( 'public/upload/expense_voucher', $voucher_file_name);

        if($file_transfer){
            $expense= new Expense();
            $expense->master_acount_id      = $request->master_acount;
            $expense->account_head_id       = $request->account_head;
            $expense->party_info_id         = $request->party_name;
            $expense->taxable_amount        = $request->taxable_amount;
            $expense->vat_amount            = $request->vat_amount;
            $expense->total_amount          = $request->total_amount;
            $expense->date                  = $request->date;
            $expense->voucher_copy          = $voucher_file_name;
            $expense->status                =1;
            $expense->save();

        }

        

        $notification= array(
            'message'       => 'Expense saved successfully!',
            'alert-type'    => 'success'
        );

        return redirect('expense/create')->with($notification);
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
        $edit_expense= Expense::find($id);
        $expenses= Expense::paginate(15);
        $account_heads= AccountHead::where('ma_code', $edit_expense->master_acount_id)->get();
        $master_accounts=MasterAccount::all();
        $parties= PartyInfo::where('pi_type', 'Supplier')->get();
        return view('backend.expense.edit', compact('master_accounts', 'parties','expenses','edit_expense','account_heads'));
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

//work by tarek
public function businessSummery(Request $request)
{
    $date=isset($request->date)?$request->date:null;
    $from=isset($request->from)?$request->from:null;
    $to=isset($request->to)?$request->to:null;
   if($request->from)
   {
    $sales=Invoice::where('pay_mode',"Cash")->whereDate('date','>=',$request->from)->whereDate('date','<=',$request->to)->get();
    $bankSales=Invoice::where('pay_mode',"Card")->whereDate('date','>=',$request->from)->whereDate('date','<=',$request->to)->get();
    $creditSales=Invoice::where('pay_mode',"Credit")->whereDate('date','>=',$request->from)->whereDate('date','<=',$request->to)->get();
    $gdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Cash")
    ->whereDate('goods_receiveds.date','>=',$request->from)
     ->whereDate('goods_receiveds.date','<=',$request->to)
    ->get();
    $bankGdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Card")
    ->whereDate('goods_receiveds.date','>=',$request->from)
     ->whereDate('goods_receiveds.date','<=',$request->to)
    ->get();
    $creditGdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Credit")
    ->whereDate('goods_receiveds.date','>=',$request->from)
     ->whereDate('goods_receiveds.date','<=',$request->to)
    ->get();
    $total_cash_collected=JournalRecord::where('account_head_id',219)->where('transaction_type','DR')->whereDate('journal_date','>=',$request->from)->whereDate('journal_date','<=',$request->to)->sum('amount');
    $total_payment=JournalRecord::where('account_head_id',219)->where('transaction_type','CR')->whereDate('journal_date','>=',$request->from)->whereDate('journal_date','<=',$request->to)->sum('amount');
    $total_bank_deposit=JournalRecord::where('account_head_id',230)->where('transaction_type','DR')->whereDate('journal_date','>=',$request->from)->whereDate('journal_date','<=',$request->to)->sum('amount');
    $total_withd_pay=JournalRecord::where('account_head_id',230)->where('transaction_type','CR')->whereDate('journal_date','>=',$request->from)->whereDate('journal_date','<=',$request->to)->sum('amount');
    $inputVates=JournalRecord::where('account_head_id',223)->where('transaction_type','DR')->whereDate('journal_date','>=',$request->from)->whereDate('journal_date','<=',$request->to)->get();
    $outputVates=JournalRecord::where('account_head_id',223)->where('transaction_type','CR')->whereDate('journal_date','>=',$request->from)->whereDate('journal_date','<=',$request->to)->get();
    $inputVatesSum=JournalRecord::where('account_head_id',223)->where('transaction_type','DR')->whereDate('journal_date','>=',$request->from)->whereDate('journal_date','<=',$request->to)->sum('amount');
    $outputVatesSum=JournalRecord::where('account_head_id',223)->where('transaction_type','CR')->whereDate('journal_date','>=',$request->from)->whereDate('journal_date','<=',$request->to)->sum('amount');
   }
   else
   {
    $sales=Invoice::where('pay_mode',"Cash")->whereDate('date',isset($request->date)? $request->date:date('Y-m-d'))->get();
    $bankSales=Invoice::where('pay_mode',"Card")->whereDate('date',isset($request->date)? $request->date:date('Y-m-d'))->get();
    $creditSales=Invoice::where('pay_mode',"Credit")->whereDate('date',isset($request->date)? $request->date:date('Y-m-d'))->get();
    $gdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Cash")
    ->whereDate('goods_receiveds.date','=',isset($request->date)? $request->date:date('Y-m-d'))
    ->get();
    $bankGdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Card")
    ->whereDate('goods_receiveds.date','=',isset($request->date)? $request->date:date('Y-m-d'))
    ->get();
    $creditGdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Credit")
    ->whereDate('goods_receiveds.date','=',isset($request->date)? $request->date:date('Y-m-d'))
    ->get();
    $total_cash_collected=JournalRecord::where('account_head_id',219)->where('transaction_type','DR')->whereDate('journal_date',isset($request->date)? $request->date:date('Y-m-d'))->sum('amount');
    $total_payment=JournalRecord::where('account_head_id',219)->where('transaction_type','CR')->whereDate('journal_date',isset($request->date)? $request->date:date('Y-m-d'))->sum('amount');
    $inputVates=JournalRecord::where('account_head_id',223)->where('transaction_type','DR')->whereDate('journal_date',isset($request->date)? $request->date:date('Y-m-d'))->get();
    $outputVates=JournalRecord::where('account_head_id',223)->where('transaction_type','CR')->whereDate('journal_date',isset($request->date)? $request->date:date('Y-m-d'))->get();
    $inputVatesSum=JournalRecord::where('account_head_id',223)->where('transaction_type','DR')->whereDate('journal_date',$date==null?date('Y-m-d'):$date)->sum('amount');
    $outputVatesSum=JournalRecord::where('account_head_id',223)->where('transaction_type','CR')->whereDate('journal_date',$date==null?date('Y-m-d'):$date)->sum('amount');
    $total_bank_deposit=JournalRecord::where('account_head_id',230)->where('transaction_type','DR')->whereDate('journal_date',isset($request->date)? $request->date:date('Y-m-d'))->sum('amount');
    $total_withd_pay=JournalRecord::where('account_head_id',230)->where('transaction_type','CR')->whereDate('journal_date',isset($request->date)? $request->date:date('Y-m-d'))->sum('amount');
}
    return view('backend.business-summary-report.businessSumery',compact('total_bank_deposit','total_withd_pay','total_cash_collected','total_payment','sales','bankSales','creditSales','gdRcvs','bankGdRcvs','creditGdRcvs','inputVates','outputVates','date','from','to','inputVatesSum','outputVatesSum'));
}


public function businessSummaryprint($date=null,$to=null)
{
    if($to!=null)
    {
    $sales=Invoice::where('pay_mode',"Cash")->whereDate('date','>=',$date)->whereDate('date','<=',$to)->get();
    $bankSales=Invoice::where('pay_mode',"Card")->whereDate('date','>=',$date)->whereDate('date','<=',$to)->get();
    $creditSales=Invoice::where('pay_mode',"Credit")->whereDate('date','>=',$date)->whereDate('date','<=',$to)->get();
    $gdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Cash")
    ->whereDate('goods_receiveds.date','>=',$date)
     ->whereDate('goods_receiveds.date','<=',$to)
    ->get();
    $bankGdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Card")
    ->whereDate('goods_receiveds.date','>=',$date)
     ->whereDate('goods_receiveds.date','<=',$to)
    ->get();
    $creditGdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Credit")
    ->whereDate('goods_receiveds.date','>=',$date)
     ->whereDate('goods_receiveds.date','<=',$to)
    ->get();
    $total_cash_collected=JournalRecord::where('account_head_id',219)->where('transaction_type','DR')->whereDate('journal_date','>=',$date)->whereDate('journal_date','<=',$to)->sum('amount');
    $total_payment=JournalRecord::where('account_head_id',219)->where('transaction_type','CR')->whereDate('journal_date','>=',$date)->whereDate('journal_date','<=',$to)->sum('amount');
    $total_bank_deposit=JournalRecord::where('account_head_id',230)->where('transaction_type','DR')->whereDate('journal_date','>=',$date)->whereDate('journal_date','<=',$to)->sum('amount');
    $total_withd_pay=JournalRecord::where('account_head_id',230)->where('transaction_type','CR')->whereDate('journal_date','>=',$date)->whereDate('journal_date','<=',$to)->sum('amount');

    $inputVates=JournalRecord::where('account_head_id',223)->where('transaction_type','DR')->whereDate('journal_date','>=',$date)->whereDate('journal_date','<=',$to)->get();
    $outputVates=JournalRecord::where('account_head_id',223)->where('transaction_type','CR')->whereDate('journal_date','>=',$date)->whereDate('journal_date','<=',$to)->get();
    $inputVatesSum=JournalRecord::where('account_head_id',223)->where('transaction_type','DR')->whereDate('journal_date','>=',$date)->whereDate('journal_date','<=',$to)->sum('amount');
    $outputVatesSum=JournalRecord::where('account_head_id',223)->where('transaction_type','CR')->whereDate('journal_date','>=',$date)->whereDate('journal_date','<=',$to)->sum('amount');
    }
    else
    {
    $sales=Invoice::where('pay_mode',"Cash")->whereDate('date',$date==null?date('Y-m-d'):$date)->get();
    $bankSales=Invoice::where('pay_mode',"Card")->whereDate('date',$date==null?date('Y-m-d'):$date)->get();
    $creditSales=Invoice::where('pay_mode',"Credit")->whereDate('date',$date==null?date('Y-m-d'):$date)->get();
    $gdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Cash")
    ->whereDate('goods_receiveds.date','=',$date==null?date('Y-m-d'):$date)
    ->get();
    $bankGdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Card")
    ->whereDate('goods_receiveds.date','=',$date==null?date('Y-m-d'):$date)
    ->get();
    $creditGdRcvs=DB::table('goods_receiveds')
    ->leftJoin('purchases', 'purchases.purchase_no', '=', 'goods_receiveds.po_no')
    ->where('purchases.pay_mode','=',"Credit")
    ->whereDate('goods_receiveds.date','=',$date==null?date('Y-m-d'):$date)
    ->get();
    $inputVates=JournalRecord::where('account_head_id',223)->where('transaction_type','DR')->whereDate('journal_date',$date==null?date('Y-m-d'):$date)->get();
    $inputVatesSum=JournalRecord::where('account_head_id',223)->where('transaction_type','DR')->whereDate('journal_date',$date==null?date('Y-m-d'):$date)->sum('amount');
    $outputVates=JournalRecord::where('account_head_id',223)->where('transaction_type','CR')->whereDate('journal_date',$date==null?date('Y-m-d'):$date)->get();
    $outputVatesSum=JournalRecord::where('account_head_id',223)->where('transaction_type','CR')->whereDate('journal_date',$date==null?date('Y-m-d'):$date)->sum('amount');
    $total_cash_collected=JournalRecord::where('account_head_id',219)->where('transaction_type','DR')->whereDate('journal_date', $date==null? date('Y-m-d'): $date)->sum('amount');
    $total_payment=JournalRecord::where('account_head_id',219)->where('transaction_type','CR')->whereDate('journal_date', $date==null? date('Y-m-d'): $date)->sum('amount');
    $total_bank_deposit=JournalRecord::where('account_head_id',230)->where('transaction_type','DR')->whereDate('journal_date', $date==null? date('Y-m-d'): $date)->sum('amount');
    $total_withd_pay=JournalRecord::where('account_head_id',230)->where('transaction_type','CR')->whereDate('journal_date', $date==null? date('Y-m-d'): $date)->sum('amount');

}
    return view('backend.business-summary-report.businessSumeryPrint',compact('total_cash_collected','total_payment','total_withd_pay','total_bank_deposit','sales','bankSales','creditSales','gdRcvs','bankGdRcvs','creditGdRcvs','inputVates','outputVates','date','to','inputVatesSum','outputVatesSum'));
}
}
