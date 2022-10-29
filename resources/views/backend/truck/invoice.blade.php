@extends('layouts.pdf.appInvoice')
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
@endphp
@push('css')
<style>
    td{
        text-align: center !important;
    }

    th, td {
    border: 1px solid #000 !important;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #000;
}
p{
    color: black !important;
}


</style>
@endpush
@section('content')
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4 text-center pt-3">
                            <h1>TAX INVOICE</h1>
                        </div>
                        <div class="col-md-4 text-right pr-4">
                            @php
                                if($invoice->due_amount==0){
                                    // paid
                                    $paid_img='paid-icon.png';
                                }elseif($invoice->paid_amount==0){
                                    // Unpaid
                                    $paid_img='unpaid-icon.png';
                                }else{
                                    // Partial Paid
                                    $paid_img='partial-paid-icon.png';
                                }
                            @endphp
                            <img src="{{asset('assets/backend/app-assets/payment/')}}/{{$paid_img}}" height="150" alt="">
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-left mb-1">
                            <span><strong style="color: #000">CUSTOMER NAME : {{ $invoice->customer->pi_name }}</strong></span>
                        </div>
                        <div class="col-md-4">
                            <div class="row">

                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>INVOICE NO</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>{{ $invoice->invoice_no }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>SHIP ADDRESS</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->address == null? "NA":$invoice->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>TRN</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->trn_no }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>CONTACT NO:</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->contact_no }}</p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>PAYMODE:</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->pay_mode }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>DATE:</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p> {{ date('m/d/Y',strtotime($invoice->date)) }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="row pt-2">
                        @php
                            $invoice_total= $invoice->amount+$invoice->vat_amount;
                        @endphp
                        @if ($invoice_total<10000)
                        <table   class="table table-sm ">
                            <tr>
                                <th class="text-center" >SL No.</th>
                                <th class="text-center" >Particular Description</th>
                                <th class="text-center" >Quantity</th>
                                <th class="text-center" >Rate</th>
                                <th class="text-center" >Total Amount</th>
                            </tr>
                                @php
                                 $taxable_amount=0;
                                 $vat=0;
                                 $total_amount=0;   
                                @endphp
                                @foreach ($invoice->items as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{$item->rate}}</td>
                                    <td>{{$item->amount + $item->vat_amount}}</td>
                                </tr>
                                    @php
                                        $taxable_amount= $taxable_amount+ $item->amount;
                                        $vat = $vat+ $item->vat_amount;
                                        $total_amount = $total_amount+ ($item->amount+ $item->vat_amount);
                                    @endphp
                                @endforeach
                                
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="2" ></th>
                                <th class="text-center" colspan="2"  >TAXABLE AMOUNT <small>(AED)</small></th>
                                <th class="text-center"   >{{$taxable_amount}}</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="2" ></th>
                                <th class="text-center" colspan="2"  >VAT <small>(5%)</small></th>
                                <th class="text-center"   > {{$vat}}</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="2" ></th>
                                <th class="text-center" colspan="2"  >Total Amount <small>(AED)</small></th>
                                <th class="text-center"   > {{$total_amount}}</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="2" ></th>
                                <th class="text-center" colspan="2"  >Paid Amount <small>(AED)</small></th>
                                <th class="text-center"   > {{$invoice->paid_amount}}</th>
                            </tr> 
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="2" ></th>
                                <th class="text-center" colspan="2"  >Due Amount <small>(AED)</small></th>
                                <th class="text-center"   > {{$invoice->due_amount}}</th>
                            </tr>                                    
                        </table> 
                        @else
                        <table   class="table table-sm ">
                            <tr>
                                <th class="text-center" >SL No.</th>
                                <th class="text-center" >Particular Description</th>
                                <th class="text-center" >Quantity</th>
                                <th class="text-center" >Rate</th>
                                <th class="text-center" >Amount</th>
                                <th class="text-center" >VAT</th>
                                <th class="text-center" >Total Amount</th>
                            </tr>
                                @php
                                 $taxable_amount=0;
                                 $vat=0;
                                 $total_amount=0;   
                                @endphp
                                @foreach ($invoice->items as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{$item->rate}}</td>
                                    <td>{{$item->amount}}</td>
                                    <td>{{$item->vat_amount}}</td>
                                    <td>{{$item->amount + $item->vat_amount}}</td>
                                </tr>
                                    @php
                                        $taxable_amount= $taxable_amount+ $item->amount;
                                        $vat = $vat+ $item->vat_amount;
                                        $total_amount = $total_amount+ ($item->amount+ $item->vat_amount);
                                    @endphp
                                @endforeach
                                
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="3"></th>
                                <th class="text-center" colspan="2"  >TAXABLE AMOUNT <small>(AED)</small></th>
                                <th class="text-center" colspan="2"  >{{$taxable_amount}}</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="3"></th>
                                <th class="text-center" colspan="2"  >VAT <small>(5%)</small></th>
                                <th class="text-center" colspan="2"  > {{$vat}}</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="3"></th>
                                <th class="text-center" colspan="2"  >Total Amount <small>(AED)</small></th>
                                <th class="text-center" colspan="2"  > {{$total_amount}}</th>
                            </tr> 
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="3" ></th>
                                <th class="text-center" colspan="2"  >Paid Amount <small>(AED)</small></th>
                                <th class="text-center" colspan="2"  > {{$invoice->paid_amount}}</th>
                            </tr> 
                            <tr>
                                <th class="text-center" style="border: none !important" colspan="3" ></th>
                                <th class="text-center" colspan="2"  >Due Amount <small>(AED)</small></th>
                                <th class="text-center" colspan="2"  > {{$invoice->due_amount}}</th>
                            </tr>                                    
                        </table> 
                        @endif
                        

                    </div>

                    <div class="row pt-5 mt-5">

                        <div class="col-6">
                            <div class="row">

                                <div class="col-12 pt-5">
                                    <p>Customer Signature</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="row">

                                <div class="col-12 pt-5 text-right">
                                    <p>Authorised Signature</p>
                                    <span>Name: {{ Auth::user()->name }}</span>
                                        <br>
                                    <span class="text-left">User ID: {{ Auth::id() }}</span>
                                </div>
                            </div>
                        </div>
                        

                    </div>


                </section>
            </div>
        </div>
    </div>

@endsection
