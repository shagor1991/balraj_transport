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
                                    <div class="col-md-4">
                                        {{-- <form  method="GET" id="search_form" action="{{ route('customer-invoice')}}"> --}}
                                            {{-- @csrf --}}
                                            <div class="form-group">
                                                {{-- <input type="text" class="inputFieldHeight form-control " name="search" placeholder="Search by "> --}}
                                                    <select name="customer_id" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Name</option>
                                                        @foreach ($customers as $customer)
                                                        <option value="{{$customer->id}}" {{isset($customer_id) && $customer_id== $customer->id ? 'selected' : ''}} >{{ $customer->pi_name}}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        {{-- </form> --}}
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="date" >
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    
                                        
                                    @isset($records)
                                    <table class="table mb-0 table-sm table-hover">
                                        <thead  class="thead-light">
                                            <tr style="height: 50px;">
                                                <th>SL No.</th>
                                                <th>Date</th>
                                                <th>Truck</th>
                                                <th>Description</th>
                                                <th>QTY</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th>Vat%</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-sm">
                                            
                                            @foreach ($records as $t_record)

                                            <tr class="trFontSize t-row">
                                                <td>1
                                                    <input type="hidden" name="record_id[]" value="{{$t_record->id}}">
                                                </td>
                                                <td>{{$t_record->date}}</td>
                                                <td>{{$t_record->truck->vehicle_number}}</td>
                                                <td>{{$t_record->material}}</td>
                                                <td>{{$t_record->weight}}
                                                    <input type="hidden" name="" value="{{$t_record->weight}}" class="r-weight">
                                                </td>
                                                <td><input type="text" class="r-rate" name="rate[]" placeholder="Rate"></td>
                                                <td><input type="text" class="r-amount" placeholder="Amount"></td>
                                                <td><input type="text" placeholder="Vat" name="v_rate" class="v-rate" value="5"></td>
                                                
                                            </tr>
                                            @endforeach
                                            <tr class="trFontSize">
                                                <td colspan="6" align="right">Total Vat</td>
                                                <td><input type="text" name="total_vat" id="total_vat" readonly></td>
                                                <td></td>
                                            </tr>
                                            <tr class="trFontSize">
                                                <td colspan="6" align="right">Total</td>
                                                <td><input type="text" name="total_amount" id="total_amount" readonly></td>
                                                <td></td>
                                            </tr>
                                            <tr class="trFontSize">
                                                <td colspan="6" align="right">Payment Applied</td>
                                                <td><input type="text" name="payment_amount" id="payment_amount"></td>
                                                <td></td>
                                            </tr>
                                            <tr class="trFontSize">
                                                <td colspan="6" align="right">Balance Due</td>
                                                <td><input type="text" name="due_amount" id="due_amount" readonly></td>
                                                <td></td>
                                            </tr>
                                            
                                            
                                        </tbody>
                                    </table>
                                    <p class="text-right"><button class="btn btn-info mt-1" type="submit">Procced</button></p>
                                    
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
