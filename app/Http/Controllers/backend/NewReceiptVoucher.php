<?php

namespace App\Http\Controllers\backend;

use App\DebitCreditVoucher;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Journal;
use App\JournalRecord;
use App\JournalRecordsTemp;
use App\JournalTemp;
use App\Models\AccountHead;
use App\Models\CostCenter;
use App\PartyInfo;
use App\PayMode;
use App\ProjectDetail;
use App\ReceiptVoucherDetailTemp;
use App\ReceiptVoucherTemp;
use App\TaxInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NewReceiptVoucher extends Controller
{
    public function receipt_voucher_form(){
        $projects = ProjectDetail::all();
        $cCenters = CostCenter::all();
        $pInfos = PartyInfo::where('pi_type','Customer')->get();
        $modes = PayMode::all();
        $invoices= TaxInvoice::where('pay_mode', 'Credit')->orWhere('due_amount','>',0)->get();
        // return $invoices;

        // $terms = PayTerm::all();
        // $sub_invoice = Carbon::now()->format('Ymd');
        // $txnTypes = TxnType::all();
        // $acHeads = AccountHead::all();
        // $vats = VatRate::all();

        $receipt_vouchers= ReceiptVoucherTemp::all();

        return view('backend.new-receipt-voucher.create', compact('projects', 'cCenters', 'pInfos','modes','invoices','receipt_vouchers'));
    }


    public function get_invoice_details(Request $request){
        
        $invoice_data= TaxInvoice::where('invoice_no', $request->invoice_no)->first();
        $due_amount= ReceiptVoucherDetailTemp::where('invoice_no',$request->invoice_no)->sum('paid_amount');
        
        return Response()->json([
            'invoice_amount' => ($invoice_data->amount+$invoice_data->vat_amount),
            'due_amount' => ($invoice_data->due_amount >0 ? $invoice_data->due_amount: ($invoice_data->amount+$invoice_data->vat_amount-$due_amount)),
        ]);
    }

    public function receipt_voucher_store(Request $request){
        // return $request;    TotalAmount     

        $journal_amount=0;
        if($request->voucher_type=='advance'){

            $ac_head_cr= AccountHead::find(36); // Accrued liability

            $receipt_voucher                = new ReceiptVoucherTemp;
            $receipt_voucher->type          = $request->voucher_type;
            $receipt_voucher->cost_center_id= $request->cost_center_name;
            $receipt_voucher->party_info_id = $request->party_info;
            $receipt_voucher->amount        = $request->total_amount;
            $receipt_voucher->payment_date  = $request->date;
            $receipt_voucher->pay_mode      = $request->pay_mode;
            $receipt_voucher->narration     = $request->remark;
            $receipt_voucher->save();
            
            $journal_amount = $request->total_amount;


        }elseif($request->voucher_type=='due'){
            $ac_head_cr= AccountHead::find(33); // Accounts Receivable

            $amount=0;
            foreach($request->input('group-a') as $each_inv){
                $amount= $amount+ $each_inv['payment_amount'];
            }
            $journal_amount = $amount;            

            $receipt_voucher                = new ReceiptVoucherTemp;
            $receipt_voucher->type          = $request->voucher_type;
            $receipt_voucher->cost_center_id= $request->cost_center_name;
            $receipt_voucher->party_info_id = $request->party_info;
            $receipt_voucher->amount        = $amount;
            $receipt_voucher->payment_date  = $request->date;
            $receipt_voucher->pay_mode      = $request->pay_mode;
            $receipt_voucher->narration     = $request->remark;
            $receipt_voucher->save();

            foreach($request->input('group-a') as $each_inv){
                $inv_details= TaxInvoice::where('invoice_no', $each_inv['invoice_no'])->first();

                $rv_details= new ReceiptVoucherDetailTemp();
                $rv_details->receipt_voucher_temp_id    = $receipt_voucher->id; 
                $rv_details->invoice_id     = $inv_details->id;
                $rv_details->invoice_no     = $each_inv['invoice_no'];
                $rv_details->cost_center_id = $request->cost_center_name;
                $rv_details->party_info_id  = $request->party_info;
                $rv_details->invoice_amount = $inv_details->amount+$inv_details->vat_amount;
                $rv_details->paid_amount    = $each_inv['payment_amount'];
                $rv_details->payment_date   = $request->date;
                $rv_details->pay_mode       = $request->pay_mode;
                $rv_details->save();

                $current_paid_ammount       = $inv_details->paid_amount + $each_inv['payment_amount'];
                $inv_details->paid_amount   = $current_paid_ammount;
                $inv_details->due_amount    = ($inv_details->amount + $inv_details->vat_amount) -$current_paid_ammount;
                $inv_details->save();

            }
        }

        // journal entry 

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
        $journal->invoice_no        = 0;
        $journal->cost_center_id    = $request->cost_center_name;
        $journal->party_info_id     = $request->party_info;
        $journal->account_head_id   = 0;
        $journal->amount            = $journal_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->total_amount      = $journal_amount;
        $journal->narration         = $request->remark;
        $journal->pay_mode          = $request->pay_mode;
        $journal->voucher_type      = 'CR';
        $journal->save();
        
        if($request->pay_mode=='Cash'){
            $ac_head_dr= AccountHead::find(1); // Cash Operating Account           
        }elseif($request->pay_mode=='Card'){
            $ac_head_dr= AccountHead::find(37); // Bank Account
        }

        $jl_record= new JournalRecord();
        $jl_record->journal_id          = $journal->id;
        $jl_record->project_details_id  = $request->project;
        $jl_record->cost_center_id      = $request->cost_center_name;
        $jl_record->party_info_id       = $request->party_info;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $ac_head_dr->id;
        $jl_record->master_account_id   = $ac_head_dr->master_account_id;
        $jl_record->account_head        = $ac_head_dr->fld_ac_head;
        $jl_record->amount              = $journal_amount;
        $jl_record->transaction_type    = 'DR';
        $jl_record->journal_date        = $request->date;
        $jl_record->save();

        $jl_record= new JournalRecord();
        $jl_record->journal_id          = $journal->id;
        $jl_record->project_details_id  = $request->project;
        $jl_record->cost_center_id      = $request->cost_center_name;
        $jl_record->party_info_id       = $request->party_info;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $ac_head_cr->id;
        $jl_record->master_account_id   = $ac_head_cr->master_account_id;
        $jl_record->account_head        = $ac_head_cr->fld_ac_head;
        $jl_record->amount              = $journal_amount;
        $jl_record->transaction_type    = 'CR';
        $jl_record->journal_date        = $request->date;
        $jl_record->save();

        $dr_cr_voucher= new DebitCreditVoucher();
        $dr_cr_voucher->journal_id      = $journal->id;
        $dr_cr_voucher->project_id      =  $journal->project_id;
        $dr_cr_voucher->cost_center_id  = 1;
        $dr_cr_voucher->party_info_id   =  $journal->party_info_id;
        $dr_cr_voucher->account_head_id = 0;
        $dr_cr_voucher->pay_mode        = $journal->pay_mode;
        $dr_cr_voucher->amount          = $journal->total_amount;
        $dr_cr_voucher->narration       = $journal->narration;
        $dr_cr_voucher->type            = 'CR';
        $dr_cr_voucher->date            = $journal->date;
        $dr_cr_voucher->save();

        return back()->with('success',"Successfully Added");
    }

    public function receipt_voucher_print($id){
        $receipt_voucher= ReceiptVoucherTemp::find($id);

        $words= $this->convert_number($receipt_voucher->amount);

        return view('backend.new-receipt-voucher.print', compact('receipt_voucher','words'));
    }

    function convert_number($number) 
    {
        if (($number < 0) || ($number > 999999999)) 
        {
            throw new Exception("Number is out of range");
        }
        $giga = floor($number / 1000000);
        // Millions (giga)
        $number -= $giga * 1000000;
        $kilo = floor($number / 1000);
        // Thousands (kilo)
        $number -= $kilo * 1000;
        $hecto = floor($number / 100);
        // Hundreds (hecto)
        $number -= $hecto * 100;
        $deca = floor($number / 10);
        // Tens (deca)
        $n = $number % 10;
        // Ones
        $result = "";
        if ($giga) 
        {
            $result .= $this->convert_number($giga) .  "Million";
        }
        if ($kilo) 
        {
            $result .= (empty($result) ? "" : " ") .$this->convert_number($kilo) . " Thousand";
        }
        if ($hecto) 
        {
            $result .= (empty($result) ? "" : " ") .$this->convert_number($hecto) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($deca || $n) {
            if (!empty($result)) 
            {
                $result .= " and ";
            }
            if ($deca < 2) 
            {
                $result .= $ones[$deca * 10 + $n];
            } else {
                $result .= $tens[$deca];
                if ($n) 
                {
                    $result .= "-" . $ones[$n];
                }
            }
        }
        if (empty($result)) 
        {
            $result = "zero";
        }
        return $result;
    }

    

}
