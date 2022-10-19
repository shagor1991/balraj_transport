@extends('layouts.pdf.app')
@push('css')
<style>
    th td{
        color: black !important;
        text-align: center !important;
    }



@media print {
     body {
           margin-top: 0mm;
           margin-left: 20mm;
           margin-bottom: 20mm;
           margin-right: 20mm
     }
     * {
                color: inherit !important;
                background-color: transparent !important;
                background-image: none !important;
            }
            table {
                width: 100%;
                border: 1pt solid #000000;
                border-collapse: collapse;
                font-size: 11pt;
            }
            #space { height: 750px; }
            th td{
        color: black !important;
        text-align: center !important;
    }
}


</style>
@endpush

@php
    $total_sale_value=0;
    $total_purcchase_value=0;
    $total_cash_sale_value=0;
    $total_bank_sale_value=0;
    $total_credit_sale_value=0;
    $total_cash_purchase_value=0;
    $total_bank_purchase_value=0;
    $total_credit_purchase_value=0;
@endphp
  @section('content')

  {{-- $style->styleSTockPositionCheck($style) --}}


  <div class="container py-2  page-break">
    <!-- BEGIN: Content-->
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <!-- Widgets Statistics start -->
            <section id="widgets-Statistics">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4> Business Summary Report</h4>
                        {{-- {{ dd($to) }} --}}
                        <p> {{ isset($to)?$date.' to '.$to :(isset($date)?$date:date('d M Y')) }}</p>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12  text-center" style="background-color: gray;color:black">
                        <span>Sale</span>
                    </div>
                    @if($sales->count()>0)
                    <div class="col-12">
                        <strong>::BY CASH::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">

                            <tr>
                                <th>INVOICE NUMBER</th>
                                <th>ACCOUNT</th>
                                <th>NARRATION</th>
                                <th>AMOUNT</th>
                            </tr>
                            @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_no }}</td>
                                {{-- <td>{{ $sale->journal }}</td> --}}
                                <td>{{ isset($sale->journal->accHead)? $sale->journal->accHead->fld_ac_head:"" }}</td>
                                <td></td>
                                <td>{{$sale_value =isset($sale->journal)?$sale->journal->amount:0 }}</td>
                            </tr>

                            @php
                            $total_cash_sale_value= $total_cash_sale_value+$sale_value;
                                $total_sale_value=$total_sale_value+$sale_value;
                            @endphp
                        @endforeach

                        </table>
                    </div>
                    @endif
                    @if($bankSales->count()>0)
                    <div class="col-12">
                        <strong>::BY BANK::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">

                            <tr>
                                <th>INVOICE NUMBER</th>
                                <th>ACCOUNT</th>
                                <th>NARRATION</th>
                                <th>AMOUNT</th>
                            </tr>
                            @foreach ($bankSales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_no }}</td>
                                <td>{{ isset($sale->journal->accHead)? $sale->journal->accHead->fld_ac_head:"" }}</td>
                                <td></td>
                                <td>{{$sale_value =isset($sale->journal)?$sale->journal->amount:0 }}</td>
                            </tr>
                                @php
                                    $total_bank_sale_value= $total_bank_sale_value+$sale_value;

                                    $total_sale_value=$total_sale_value+$sale_value;
                                @endphp
                        @endforeach

                        </table>
                    </div>
                    @endif
                    @if($creditSales->count()>0)
                    <div class="col-12">
                        <strong>::CREDIT::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">

                            <tr>
                                <th>INVOICE NUMBER</th>
                                <th>ACCOUNT</th>
                                <th>NARRATION</th>
                                <th>AMOUNT</th>
                            </tr>
                            @foreach ($creditSales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_no }}</td>
                                <td>{{ isset($sale->journal->accHead)? $sale->journal->accHead->fld_ac_head:"" }}</td>
                                <td></td>
                                <td>{{$sale_value =isset($sale->journal)?$sale->journal->amount:0 }}</td>
                            </tr>
                                @php
                                  $total_credit_sale_value= $total_credit_sale_value+$sale_value;

                                    $total_sale_value=$total_sale_value+$sale_value;
                                @endphp
                        @endforeach

                        </table>
                    </div>
                    @endif
                </div>


                <div class="row">
                    <div class="col-12  text-center" style="background-color: gray;color:black">
                        <span>Purchase</span>
                    </div>
                    @if($gdRcvs->count()>0)
                    <div class="col-12">
                        <strong>::BY CASH::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">

                            <tr>
                                <th>PURCHASE NUMBER</th>
                                <th>ACCOUNT</th>
                                <th>NARRATION</th>
                                <th>AMOUNT</th>
                            </tr>
                            @foreach ($gdRcvs as $rcv)
                                <tr>
                                    <td>{{ $rcv->purchase_no }}</td>
                                    <td>{{ isset(App\GoodsReceived::journal($rcv->goods_received_no)->accHead)? App\GoodsReceived::journal($rcv->goods_received_no)->accHead->fld_ac_head:0 }}</td>
                                    <td></td>
                                    <td>{{ $purchaseValue=(App\GoodsReceived::journal($rcv->goods_received_no)!=null)?App\GoodsReceived::journal($rcv->goods_received_no)->amount:0 }}</td>
                                </tr>
                                @php
                                    $total_cash_purchase_value= $total_cash_purchase_value+$purchaseValue;

                                    $total_purcchase_value=$total_purcchase_value+$purchaseValue;
                                @endphp

                            @endforeach

                        </table>
                    </div>
                    @endif
                    @if($bankGdRcvs->count()>0)
                    <div class="col-12">
                        <strong>::BY BANK::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">

                            <tr>
                                <th>PURCHASE NUMBER</th>
                                <th>ACCOUNT</th>
                                <th>NARRATION</th>
                                <th>AMOUNT</th>
                            </tr>
                            @foreach ($bankGdRcvs as $rcv)
                            <tr>
                                <td>{{ $rcv->purchase_no }}</td>
                                <td>{{ isset(App\GoodsReceived::journal($rcv->goods_received_no)->accHead)? App\GoodsReceived::journal($rcv->goods_received_no)->accHead->fld_ac_head:0 }}</td>
                                <td></td>
                                <td>{{ $purchaseValue=(App\GoodsReceived::journal($rcv->goods_received_no)!=null)?App\GoodsReceived::journal($rcv->goods_received_no)->amount:0 }}</td>
                            </tr>
                                    @php
                                        $total_bank_purchase_value= $total_bank_purchase_value+$purchaseValue;

                                        $total_purcchase_value=$total_purcchase_value+$purchaseValue;
                                    @endphp

                                @endforeach

                        </table>
                    </div>
                    @endif
                    @if($creditGdRcvs->count()>0)
                    <div class="col-12">
                        <strong>::CREDIT::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">

                            <tr>
                                <th>PURCHASE NUMBER</th>
                                <th>ACCOUNT</th>
                                <th>NARRATION</th>
                                <th>AMOUNT</th>
                            </tr>
                            @foreach ($creditGdRcvs as $rcv)
                            <tr>
                                <td>{{ $rcv->purchase_no }}</td>
                                <td>{{ isset(App\GoodsReceived::journal($rcv->goods_received_no)->accHead)? App\GoodsReceived::journal($rcv->goods_received_no)->accHead->fld_ac_head:0 }}</td>
                                <td></td>
                                <td>{{ $purchaseValue=(App\GoodsReceived::journal($rcv->goods_received_no)!=null)?App\GoodsReceived::journal($rcv->goods_received_no)->amount:0 }}</td>
                            </tr>
                                    @php
                                        $total_credit_purchase_value= $total_credit_purchase_value+$purchaseValue;

                                        $total_purcchase_value=$total_purcchase_value+$purchaseValue;
                                    @endphp

                                @endforeach

                        </table>
                    </div>
                    @endif

                    <div class="col-12">
                        <strong>::CASH MANAGEMENT::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>TOTAL CASH COLLECTED</th>
                                <td style="width:250px; text-align:center !important">{{ $total_cash_collected }}</td>
                            </tr>
                            <tr>
                                <th>TOTAL PAYMENT</th>
                                <td style="width:250px; text-align:center !important">{{ $total_payment }}</td>
                            </tr>

                            <tr>
                                <th>CASH IN HAND</th>
                                <td style="width:250px; text-align:center !important">{{ $total_cash_collected - $total_payment }}</td>
                            </tr>
                        </table>
                    </div>


                    <div class="col-12">
                        <strong>::Bank MANAGEMENT::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>TOTAL Bank Deposit</th>
                                <td style="width:250px; text-align:center !important">{{ $total_bank_deposit }}</td>
                            </tr>
                            <tr>
                                <th>Bank Withdrow/Payment</th>
                                <td style="width:250px; text-align:center !important">{{ $total_withd_pay }}</td>
                            </tr>

                            <tr>
                                <th>Balance</th>
                                <td style="width:250px; text-align:center !important">{{ $total_bank_deposit - $total_withd_pay }}</td>
                            </tr>
                        </table>
                    </div>




                    <div class="col-12">
                        <strong>::VAT::</strong>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th colspan="3" style="background-color: gray;color:black">INPUT VAT</th>
                            </tr>
                            <tr>
                                <td>JOURNAL ENTRY NUMBER</td>
                                <td>TAXABLE SUPPLIES</td>
                                <td>VAT AMOUNT</td>
                            </tr>
                            @php
                                $inputTaxable=0;
                            @endphp
                            @foreach ($inputVates as $item)
                            <tr>
                                <td>{{ $item->journaltemp==null? "":$item->journaltemp->journal_no }} </td>
                                <td>{{$inTaxable=$item->journaltemp==null? 0:$item->journaltemp->total_amount}}</td>
                                <td>{{ $item->amount }}</td>
                            </tr>
                            @php
                                $inputTaxable=$inputTaxable+$inTaxable;
                            @endphp

                            @endforeach
                            <tr>
                                <th >Total</th>
                                <th>{{ number_format($inputTaxable,2) }}</th>
                                <th>{{ number_format($inputVatesSum,2) }}</th>
                            </tr>

                        </table>

                        <table class="table table-sm table-bordered">
                            <tr>
                                <th colspan="3" style="background-color: gray;color:black">OUTPUT VAT</th>
                            </tr>
                            <tr>
                                <td>JOURNAL ENTRY NUMBER</td>
                                <td>TAXABLE SUPPLIES</td>
                                <td>VAT AMOUNT</td>
                            </tr>
                            @php
                                $outTaxable=0;
                            @endphp
                            @foreach ($outputVates as $item)
                            <tr>
                                <td>{{ $item->journaltemp==null? "":$item->journaltemp->journal_no }} </td>
                                <td>{{$outTaxabble=$item->journaltemp==null? 0:$item->journaltemp->total_amount}}</td>
                                <td>{{ $item->amount }}</td>
                            </tr>
                            @php
                                 $outTaxable= $outTaxable+$outTaxabble;
                            @endphp

                            @endforeach
                            <tr>
                                <th>Total</th>
                                <th>{{ number_format($outTaxable,2) }}</th>
                                <th>{{ number_format($outputVatesSum,2) }}</th>
                            </tr>
                        </table>
                    </div>
                </div>

            </section>
            <!-- Widgets Statistics End -->



        </div>
    </div>
    <div class="row pt-3">
        <table class="table table-sm table-bordered" >
            <tr>
                <th>Prepared By</th>
                <th>Checked By</th>
                <th>Endorsed By</th>
                <th>Authorized By</th>
                <th>Authorized By</th>
                <th>Approved By</th>
            </tr>

            <tr>
                <td>Mahidul Islam Bappy</td>
                <td>Ridwanuzzaman</td>
                <td>Habibur Rahaman</td>
                <td>Md. Akhter Hosain</td>
                <td>S.M Arifen</td>
                <td>Salim Osman</td>


            </tr>

        </table>
 </div>
</div>


  @endsection
