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

                    <table   class="table table-sm table-bordered">
                        <tr>
                            <th class="text-center" colspan="4">Sale</th>
                        </tr>

                        @if($sales->count()>0)
                        <tr>
                            <th class="text-center" >By Cash</th>
                            <th class="text-center">A/C Head</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Amount</th>
                        </tr>

                        @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_no }}</td>
                            <td>{{ isset($sale->journal->accHead)? $sale->journal->accHead->fld_ac_head:"" }}</td>
                            <td></td>
                            <td>{{$sale_value =isset($sale->journal)?$sale->journal->amount:0 }}</td>
                        </tr>

                        @php
                        $total_cash_sale_value= $total_cash_sale_value+$sale_value;
                            $total_sale_value=$total_sale_value+$sale_value;
                        @endphp
                        @endforeach
                        @endif


                        @if($bankSales->count()>0)
                        <tr>
                            <th class="text-center" >By Bank</th>
                            <th class="text-center">A/C Head</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Amount</th>
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
                        @endif

                        @if($creditSales->count()>0)
                            <tr>
                                <th class="text-center" >By Credit</th>
                                <th class="text-center">A/C Head</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Amount</th>
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
                        @endif
                        <tr>
                            <td colspan="3" class="text-right">Total Sale</td>
                            <td>{{ $total_sale_value }}</td>
                        </tr>




                    </table>


                    <table   class="table table-sm table-bordered">
                        <tr>
                            <th class="text-center" colspan="4">Purchase</th>
                        </tr>
                        @if($gdRcvs->count()>0)
                            <tr>
                                <th class="text-center" >By Cash</th>
                                <th class="text-center">A/C Head</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Amount</th>
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
                        @endif

                        @if ($bankGdRcvs->count()>0)
                        <tr>
                            <th class="text-center" >By Bank</th>
                            <th class="text-center">A/C Head</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Amount</th>
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

                        @endif

                        @if ($creditGdRcvs->count()>0)
                        <tr>
                            <th class="text-center" >By Credit</th>
                            <th class="text-center">A/C Head</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Amount</th>
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

                        @endif
                        <tr>
                            <td colspan="3" class="text-right">Total Purchase</td>
                            <td>{{ number_format($total_purcchase_value,2) }}</td>
                        </tr>

                    </table>
                    <table   class="table table-sm table-bordered">
                        <tr>
                            <th class="text-center">Cash Management</th>
                            <th class="text-center">Total Cash Collected</th>
                            <th>{{ $total_cash_sale_value-$total_cash_purchase_value }}</th>
                        </tr>

                        <tr>
                            <th class="text-center">Bank Deposit</th>
                            <th class="text-center">Total Bank Deposit</th>
                            <th>{{ $total_bank_sale_value-$total_bank_purchase_value }}</th>
                        </tr>
                        <tr>
                            <th class="text-center">Creit</th>
                            <th class="text-center">Total Credit Amount</th>
                            <th>{{ $total_bank_sale_value-$total_bank_purchase_value }}</th>
                        </tr>

                    </table>

                    <table   class="table table-sm table-bordered">
                        <tr>
                            <th class="text-center" colspan="4">Vat Tax</th>
                        </tr>
                        <tr>
                            <td rowspan="{{ $inputVates->count()+1 }}">Input Tax</td>
                            <td>Journal Entry Number</td>
                            <td>Taxable Supplies </td>
                            <td>Vat AMount</td>
                        </tr>
                        @foreach ($inputVates as $item)
                        <tr>
                            <td>{{ $item->journaltemp==null? "":$item->journaltemp->journal_no }} </td>
                            <td>{{$item->journaltemp==null? "":$item->journaltemp->total_amount}}</td>
                            <td>{{ $item->amount }}</td>
                        </tr>

                        @endforeach

                        <tr>
                            <td rowspan="{{ $outputVates->count()+1 }}">Output Tax</td>
                            <td>Journal Entry Number</td>
                            <td>Taxable Supplies </td>
                            <td>Vat AMount</td>
                        </tr>
                        @foreach ($outputVates as $item)
                        <tr>
                            <td>{{ $item->journaltemp==null? "":$item->journaltemp->journal_no }} </td>
                            <td>{{$item->journaltemp==null? "":$item->journaltemp->total_amount}}</td>
                            <td>{{ $item->amount }}</td>
                        </tr>

                        @endforeach

                    </table>

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
