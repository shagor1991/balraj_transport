<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\JournalRecordsTemp;
use Illuminate\Http\Request;

class AccountsReportController extends Controller
{
    public function general_ledger(){
        return view('backend.accounts-report.general-ledger');
    }

    public function general_ledger_by_date_range(Request $request){
        $from=$request->from;
        $to=$request->to;
        return view('backend.accounts-report.general-ledger-by-date-range',compact('from','to'));
    }

    public function general_ledger_by_date(Request $request){
        $date=$request->date;
        return view('backend.accounts-report.general-ledger-by-date',compact('date'));
    }

    public function general_ledger_print(){
        return view('backend.accounts-report.print-general-ledger');
    }
    public function general_ledger_print_date(Request $request){
        $date=$request->date;
        return view('backend.accounts-report.print-gl-date', compact('date'));
    }

    public function general_ledger_print_date_range(){
        return view('backend.accounts-report.print-gl-date-range');
    }

    public function trial_balance(){
        return view('backend.accounts-report.trial-balance');
    }

    public function trial_balance_date(Request $request){
        $date=$request->date;
        return view('backend.accounts-report.trial-balance-date', compact('date'));  
    }

    public function trial_balance_date_range(Request $request){
        $from=$request->from;
        $to=$request->to;
        return view('backend.accounts-report.trial-balance-date-range', compact('from','to'));  
    }

    public function trial_balance_print(){
        return view('backend.accounts-report.print-trial-balance');
    }

    public function trial_balance_print_date($date){
        return view('backend.accounts-report.print-trial-balance-date',compact('date'));
    }

    public function trial_balance_print_date_range($from,$to){
        return view('backend.accounts-report.print-trial-balance-date-range',compact('from','to'));
    }

    public function ac_payable_ledger(Request $request){
        $ac_payable_id=222;
        if($request->has('date')){
            $date= $request->date;
            return view('backend.accounts-report.ac-payable-ledger', compact('ac_payable_id','date'));
        }elseif($request->hasAny(['from','to'])){
            $from= $request->from;
            $to= $request->to;
            return view('backend.accounts-report.ac-payable-ledger', compact('ac_payable_id','from','to'));
        }
        return view('backend.accounts-report.ac-payable-ledger', compact('ac_payable_id'));
    }


    public function ac_receivable_ledger(Request $request){
        $ac_payable_id=221;
        if($request->has('date')){
            $date= $request->date;
            return view('backend.accounts-report.ac-receivable-ledger', compact('ac_payable_id','date'));
        }elseif($request->hasAny(['from','to'])){
            $from= $request->from;
            $to= $request->to;
            return view('backend.accounts-report.ac-receivable-ledger', compact('ac_payable_id','from','to'));
        }
        return view('backend.accounts-report.ac-receivable-ledger', compact('ac_payable_id'));
    }


}
