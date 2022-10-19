@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        .table-bordered {
            border: 1px solid #f4f4f4;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        table {
            background-color: transparent;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }


        .tarek-container{
    width: 85%;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 88% 12%;
    background-color: #ffff;
}

.invoice-label{
    font-size: 10px !important
}

    </style>
@endpush
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">

            <div class="content-body">
                <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <h4>Tax Invoice</h4>
                                <hr>
                            </div>
                            <form action="{{ route('finalSaveInvoice') }}" method="POST" target="_blank">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card d-flex align-items-center" style="min-height: 180px">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Project</label>
                                                        <select name="branch" class="form-control" id="" required>
                                                            <option value="">Select...</option>
                                                            @foreach ($projects as $item)
                                                                <option value="{{ $item->proj_no }}">{{ $item->proj_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Date</label>
                                                        <input type="date"
                                                            value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                                            class="form-control" name="date" id="date" readonly>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Tax Invoice No</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $invoice->invoice_no }}" name="invoice_no"
                                                            id="invoice_no" readonly>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Customer Name</label>
                                                        <select name="customer_name" id="customer_name"
                                                            class="form-control party-info" data-target="" required>
                                                            <option value="">Select...</option>
                                                            @foreach ($customers as $customer)
                                                                <option value="{{ $customer->cc_code }}" >
                                                                    {{ $customer->con_person }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label for="">TRN</label>
                                                        <input type="text" class="form-control" name="trn_no" id="trn_no"
                                                            class="form-control" readonly>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Pay Mode</label>
                                                        <select name="pay_mode" id="" class="form-control" required>
                                                            <option value="">Select...</option>
                                                            @foreach ($modes as $item)
                                                                <option value="{{ $item->title }}">{{ $item->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Pay Terms</label>
                                                        <select name="pay_terms" id="pay_terms" class="form-control"
                                                            required>
                                                            <option value="">Select...</option>
                                                            @foreach ($terms as $item)
                                                                <option value="{{ $item->value }}">{{ $item->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Due Date</label>
                                                        <input type="date" class="form-control" name="due_date"
                                                            id="due_date" readonly>
                                                    </div>

                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Contact Number</label>
                                                        <input type="text" class="form-control" name="contact_no"
                                                            id="contact_no" readonly>
                                                    </div>

                                                    <div class="col-sm-3 form-group">
                                                        <label for="">Shipping Address</label>
                                                        <input type="text" class="form-control" name="address"
                                                            id="address" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pb-1">
                                    <div class="col-12">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label class="invoice-label" for="">Barcode</label>
                                                        <input type="text" class="form-control item-select-by-term"
                                                            placeholder="Barcode"  name="barcode" id="barcode">
                                                            {{-- list="browsers" --}}
                                                        {{-- <datalist id="browsers" >
                                                            @foreach($itms  as $item)
                                                            <option value="{{ $item->barcode }}">{{ $item->item_name }}</option>                                                            @endforeach
                                                        </datalist> --}}
                                                    </div>

                                                    <div class="col-sm-7 search-item">
                                                        <label class="invoice-label" for="">Item Name</label>
                                                        <select name="item_name" id="item_name" class="form-control">
                                                            <option value="">Select</option>
                                                            @foreach ($itms as $item)
                                                            <option value="{{ $item->barcode }}">{{ $item->item_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <label class="invoice-label" for="">QTY</label>
                                                        <input type="number" class="form-control" name="quantity"
                                                            id="quantity" >
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label class="invoice-label" for="">Unit Price</label>
                                                        <input type="text" class="form-control" name="unit_price"
                                                            id="unit_price" readonly>
                                                    </div>



                                                    <div class="col-sm-2">
                                                        <label class="invoice-label" for="">Net Amount</label>
                                                        <input type="text" class="form-control" name="net_amount"
                                                            id="net_amount" readonly>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label class="invoice-label" for="">Cost Price</label>
                                                        <input type="text" class="form-control" name="cost_price"
                                                            id="cost_price" readonly>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <label for=""></label>
                                                        <div class="row">
                                                            <input type="button" name="temp_invoice" class="btn btn-warning" value="Add" id="temp_invoice">
                                                            {{-- <span class="btn btn-warning" id="temp_invoice">Add</span> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Barcode</th>
                                                <th>Item Name</th>
                                                <th>QTY</th>
                                                <th>Unit</th>
                                                <th>Unit Price</th>
                                                <th>Vat Rate</th>
                                                <th>Discount</th>
                                                <th>Net Amount</th>
                                                <th>Cost Price </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="all-data-area">


                                        </tbody>



                                    </table>
                                </div>

                            <div class="row d-flex justify-content-end pt-1">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label for="" class="col-5 d-flex align-items-center">Tax Sup:</label>
                                        <input type="text" class="form-control col-7" name="tax_sup" id="tarek"
                                                min="0" step="any" class="form-control" value="0.00" readonly>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label for="" class="col-5 d-flex align-items-center">VAT (TK):</label>
                                        <input type="number" placeholder="VAT" min="0" step="any"
                                        class="form-control col-7" value="0.00" name="total_vat"
                                        id="total_vat" readonly>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label for="" class="col-5 d-flex align-items-center">Total Gross:</label>
                                        <input type="number" name="total_gross" placeholder="Final Discount"
                                        min="0" step="any" class="form-control col-7" value="0.00"
                                        id="total_gross" readonly>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                                        class="btn btn-sm final-save-btn only-save-btn  btn-primary " id="invoice_save">
                                                        Save</button>
                                                        <a  class="btn btn-sm btn-warning" onClick="refreshPage()">Refresh</a>
                                </div>
                            </div>
                        </form>
                        </div>
                        <div class="col-md-1">
                            <div class="row">
                                <h4>Invoicess <span ></span></h4>
                                <i class="bx bx-refresh btn btn-sm" id="refresh_invoice">Refresh</i>
                                <div class="invoice-items">
                                    <ul>
                                        @foreach ($invoicess as $invoice)
                                        <li><a href="{{ route('invoiceEdit', $invoice) }}">{{ $invoice->invoice_no }}</a></li>

                                        @endforeach
                                    </ul>



                                </div>
                                <hr>
                            </div>

                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
    <script>

function refreshPage(){
    window.location.reload();
}
        $(document).ready(function() {


            $('#customer_name').change(function() {
                // alert(1);
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('partyInfoInvoice') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },

                        success: function(response) {
                            // alert('ok');



                            console.log(response);
                            $("#trn_no").val(response.trn_no);
                            $("#contact_no").val(response.con_no);
                            $("#address").val(response.address);

                        }

                    })
                }
            });


            $('#pay_terms').change(function() {
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('findDate') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },

                        success: function(response) {
                            // console.log(response.date);
                            // alert(1);
                            $("#due_date").val(response);



                        }

                    })
                }
            });

            $(document).on("keyup", "#barcode", function(e) {
                // alert($(this).val().length);
                if ($(this).val().length == 4) {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('findItem') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },

                        success: function(response) {
                            console.log(response);
                            var qty = 0;
                            // alert(response.item_name);
                            $("#item_name").val(response.item_name);
                            $("div.search-item select").val(response.barcode);
                            $("#unit_price").val(response.sell_price);
                            $("#cost_price").val(response.sell_price);
                            $("#net_amount").val(response.sell_price);
                            $("#quantity").focus().val(qty);
                            // $('#qua').focus()

                        }

                    })
                }
            });
            $(document).on("keyup", "#quantity", function(e) {
                // alert(1);
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    var c = $('#net_amount').val();
                    var cost = c * value;
                    $("#cost_price").val(cost);
                    $("#temp_invoice").focus();
                }
            });
            $('#temp_invoice').click(function() {
                // alert(1);

                var barcode = $('#barcode').val();
                var quantity = $('#quantity').val();
                var unit_price = $('#unit_price').val();
                var cost_price = $('#cost_price').val();
                var invoice_no = $('#invoice_no').val();
                var item_name = $('#item_name').val();
                var net_amount = $('#net_amount').val();

                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('tempInvoice') }}",

                    method: "GET",
                    data: {
                        barcode: barcode,
                        quantity: quantity,
                        unit_price: unit_price,
                        cost_price: cost_price,
                        invoice_no: invoice_no,
                        item_name: item_name,
                        net_amount: net_amount,

                        barcode: barcode,
                        _token: _token,
                    },

                    success: function(response) {
                        // alert(response.total_vat_amount);
                        var vat = response.total_cost_price - response.total_unit_price;
                        $(".all-data-area").empty().append(response.page);
                        $("#total_gross").val(response.total_cost_price);
                        $("#tarek").val(response.total_unit_price);
                        $("#total_vat").val(response.total_vat_amount);
                        $("#item_name").val('');
                            $("div.search-item select").val('');
                            $("#unit_price").val('');
                            $("#cost_price").val('');
                            $("#net_amount").val('');
                            $("#quantity").val('');
                            $("#barcode").focus().val('');
                            // $("#item_name").val(response.item_name);

                    }

                })

            });

            var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();

            $(document).on("click", '.invoice-item-delete', function(event) {
                event.preventDefault();
                var that = $(this);
                var urls = that.attr("data_target");
                var _token = $('input[name="_token"]').val();
                var invoice_no = $('#invoice_no').val();
                // alert(invoice_no);
                $.ajax({
                    url: urls,
                    method: "GET",
                    invoice_no: invoice_no,
                    _token: _token,

                    success: function(response) {
                        // alert("hukka");
                        console.log(response);
                        $(".all-data-area").empty().append(response.page);
                        $("#total_gross").val(response.total_cost_price);
                        $("#tarek").val(response.total_unit_price);
                        $("#total_vat").val(response.total_vat_amount);

                    },
                    error: function() {
                        //   alert('no');
                    }
                });

            });

            $('#refresh_invoice').click(function() {
                // alert(1);

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('refresh_invoice') }}",
                    method: "GET",
                    data: {
                        _token: _token,
                    },
                    success: function(response) {
                        $(".invoice-items").empty().append(response.page);
                    }
                })
            });

            $('#item_name').change(function() {
                // alert(1);
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('findItem') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },

                        success: function(response) {
                            console.log(response);
                            var qty = 1;
                            // alert(response.item_name);
                            $("#barcode").val(response.barcode);
                            $("div.search-item select").val(response.barcode);
                            $("#unit_price").val(response.sell_price);
                            $("#cost_price").val(response.sell_price);
                            $("#net_amount").val(response.sell_price);
                            $("#quantity").focus().val(qty);
                            // $('#qua').focus()

                        }

                    })
                }
            });





            $("#quantity").enterKey(function () {
    alert('Enter!');
})



            $('#reservationdatetime').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                }
            });


        });
    </script>
@endpush
