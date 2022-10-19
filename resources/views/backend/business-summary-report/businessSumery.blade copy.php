@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        td{
            text-align: center !important;
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
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>{{ isset($date)?$date:(isset($from)?$from.' to '.$to:date('d M Y')) }} Business Summary Report</h4>
                           </div>
                           <div class="col-md-2 text-right">
                            <form action="#" method="GET">
                               <div class="row form-group">
                                <input type="text" class="form-control col-9" name="date"  placeholder="Select Date" onfocus="(this.type='date')" id="date" required>
                                <button class="bx bx-search col-3 btn-warning btn-block" type="submit"></button>
                               </div>
                            </form>
                           </div>
                           <div class="col-md-4  col-left-padding">
                            <form action="#" method="GET">
                                {{-- @csrf --}}
                                <div class="row form-group">
                                    <div class="col-5 col-right-padding">
                                        <input type="text" class="form-control" name="from"
                                        placeholder="From"  value="{{ isset($searchDatefrom)? $searchDatefrom:"" }}"  onfocus="(this.type='date')"  id="from" required>

                                    </div>
                                    <div class="col-5  col-left-padding col-right-padding">
                                        <input type="text" class="form-control" name="to"
                                        placeholder="To" value="{{ isset($searchDateto)? $searchDateto:"" }}" onfocus="(this.type='date')" id="to" required>
                                    </div>
                                    <button class="bx bx-search col-2 btn-warning btn-block" type="submit"></button>
                                </div>
                            </form>

                            <input type="hidden" name="hidden_date_from" value="{{ isset($from)? $from:"" }}" id="hidden_date_from">
                            <input type="hidden" name="hidden_date_to" value="{{ isset($to)? $to:"" }}" id="hidden_date_to">
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-md-12">
                            <a href="{{ ($date!=null)?route('businessSummaryprint',$date):(($from!=null)?route('businessSummaryprint',[$from,$to]):route('businessSummaryprint')) }}" class="btn btn-sm btn-info float-right"
                            target="_blank">Print </a>
                            <button class="btn  btn-info btn-sm float-right mr-1"
                        onclick="exportTableToCSV('business-summary-report-{{ date('d M Y') }}.csv')">Export To CSV</button>
                        </div>
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
    </div>
    <!-- END: Content-->
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
    <script>
        // $(document).ready(function() {
        // Page Script
        // alert("Alhamdulillah");
        // });
    </script>


@endpush
