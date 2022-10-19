@extends('layouts.backend.app')
@section('content')
@include('layouts.backend.partial.style')
<style>
    .table td{
        border-bottom: none;
    }
    .commonSelect2Style span{
        width: 100% !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
</style>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="card cardStyleChange">
                    <div class="row" id="table-bordered">
                        <div class="col-12">
                            <div class="cardStyleChange p-2">
                                <div class="d-flex">
                                    <h4 class="flex-grow-1">Tax Invoice</h4>
                                    {{-- <div>
                                        <button type="button" class="btn btn-primary btn_create formButton mr-1" title="Add" data-toggle="modal" data-target="#newTruckAddModal">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Add New</span></div>
                                            </div>
                                        </button>
                                    </div> --}}
                                </div>
                                <form action="{{ route('save-customer-invoice')}}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Customer Name</label>
                                                <input type="text" class="inputFieldHeight form-control" value="{{$invoice->customer->pi_name}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Project Name</label>
                                                <input type="text" class="inputFieldHeight form-control" value="{{$invoice->project->proj_name}}" readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Cost Center</label>
                                                <input type="text" class="inputFieldHeight form-control" value="{{$invoice->customer->pi_name}}"readonly>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Payment Mode</label>
                                                <input type="text" class="inputFieldHeight form-control" value="{{$invoice->pay_mode}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Date</label>
                                                <input type="date" class="inputFieldHeight form-control" name="date" value="{{$invoice->date}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        @isset($invoice)
                                        <table class="table mb-0 table-sm table-hover">
                                            <thead  class="thead-light">
                                                <tr style="height: 50px;">
                                                    <th>SL No.</th>
                                                    {{-- <th>Date</th>
                                                    <th>Truck</th> --}}
                                                    <th>Description</th>
                                                    <th>QTY</th>
                                                    <th>Rate</th>
                                                    <th>Amount</th>
                                                    <th>Vat%</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-sm">
                                                
                                                @foreach ($invoice_items as $item)

                                                <tr class="trFontSize t-row">
                                                    <td>{{$loop->index + 1}}</td>
                                                    {{-- <td>{{$item->date}}</td>
                                                    <td>{{$item->truck->vehicle_number}}</td> --}}
                                                    <td>From {{$item->crusher}} To {{$item->destination}}</td>
                                                    <td>{{$item->total_qty}}</td>
                                                    <td>{{$item->rate}}</td>
                                                    <td>{{$item->rate * $item->total_qty}}</td>
                                                    <td>{{$item->vat_rate}}</td>
                                                                                                        
                                                    
                                                </tr>
                                                @endforeach
                                                <tr class="trFontSize">
                                                    <td colspan="4" align="right">Total Vat</td>
                                                    <td align="right">{{ $invoice->vat_amount}}</td>
                                                    <td></td>
                                                </tr>
                                                <tr class="trFontSize">
                                                    <td colspan="4" align="right">Total</td>
                                                    <td align="right">{{ $invoice->vat_amount+$invoice->amount}}</td>
                                                    <td></td>
                                                </tr>
                                                <tr class="trFontSize">
                                                    <td colspan="4" align="right">Payment Applied</td>
                                                    <td align="right">{{ $invoice->paid_amount}}</td>
                                                    <td></td>
                                                </tr>
                                                <tr class="trFontSize">
                                                    <td colspan="4" align="right">Balance Due</td>
                                                    <td align="right">{{ $invoice->due_amount}}</td>
                                                    <td></td>
                                                </tr>
                                                
                                                
                                            </tbody>
                                        </table>
                                        
                                        @endisset
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('js')
<script>
    // $(document).on("click", ".truckInfoEdit", function(e){
    //     e.preventDefault();
    //     $("#truckInfoEditModal").modal('show');
    // });

        $('.r-rate').keyup(function(){
            var qty= ($(this).closest('.t-row').find('.r-weight').val());
            var v_rate= ($(this).closest('.t-row').find('.v-rate').val());
            var rate= ($(this).val());
            var amount= qty*rate;
            $(this).closest('.t-row').find('.r-amount').val(amount);
            total_vat(v_rate);

        });

        function total_vat(vat_rate){
            var total_amount=0;
                $('.r-amount').each(function() {                    
                    var this_amount= $(this).val();
                    this_amount = (this_amount === '') ? 0 : this_amount;
                    this_amount= parseInt(this_amount);
                    total_amount = total_amount+this_amount;
                });
                var total_vat= total_amount * vat_rate / 100;
                
                $('#total_vat').val(total_vat);
                $('#total_amount').val(total_vat+total_amount);
        }

        $('#payment_amount').keyup(function(){
            var payment_amount= $(this).val();
            var total_amount= $('#total_amount').val();
            $('#due_amount').val(total_amount-payment_amount);
        });






</script>
@endpush
