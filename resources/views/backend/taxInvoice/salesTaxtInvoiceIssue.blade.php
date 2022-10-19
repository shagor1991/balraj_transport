@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>

    </style>
@endpush
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">

            <div class="content-body">
                <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Tax Invoice</h4>

                                </div>
                                <div class="col-md-5 text-right">
                                    {{-- <a href="{{ route('taxInvoIssue') }}" class="btn btn-primary btn-block  mb-1 text-center">Tax Invoice <small>Without SO</small></a> --}}

                                </div>
                                 {{-- <div class="col-md-3 text-right">
                                    <a href="{{ route('taxInvoiceList') }}" class="btn btn-info btn-block mx-1 mb-1 text-center active-sale">
                                        Tax Invoice <small>(List)</small>
                                       </a>
                                </div> --}}
                                <hr>
                            </div>


                          <div class="row details-view">

                            @if (isset($invoice))
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('finalSaveInvoice') }}" method="POST" target="_blank">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card d-flex align-items-center" style="min-height: 180px">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Branch</label>
                                                                <select name="branch" class="form-control" id="" readonly disabled>
                                                                    <option value="">Select...</option>
                                                                    @foreach ($projects as $item)
                                                                        <option value="{{ $item->proj_no }}" {{ $invoice->project_id==$item->id? "selected":"" }}>{{ $item->proj_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-3 form-group">
                                                                <label for="">GL Code</label>
                                                               <input type="text" name="gl_code" id="gl_code" value="{{ $invoice->gl_code }}" class="form-control" disabled>

                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Date</label>
                                                                <input type="date"
                                                                    value="{{ $invoice->date }}"
                                                                    class="form-control" name="date" id="date" readonly disabled>
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Tax Invoice No</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $invoice->invoice_no }}" name="invoice_no"
                                                                    id="invoice_no" readonly >
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Customer Name</label>
                                                                <select name="customer_name" id="customer_name"
                                                                    class="form-control party-info" data-target="" readonly disabled>
                                                                    <option value="">Select...</option>
                                                                    @foreach ($customers as $customer)
                                                                        <option value="{{ $customer->cc_code }}" {{ $invoice->customer_name==$customer->pi_code? "selected":"" }}>
                                                                            {{ $customer->pi_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label for="">TRN</label>
                                                                <input type="text" class="form-control" value="{{  $invoice->trn_no }}" name="trn_no" id="trn_no"
                                                                    class="form-control" readonly disabled>
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Payment Mode</label>
                                                                <select name="pay_mode" id="" class="form-control" readonly disabled>
                                                                    <option value="">Select...</option>
                                                                    @foreach ($modes as $item)
                                                                        <option value="{{ $item->title }}" {{ $invoice->pay_mode==$item->title? "selected":"" }}>{{ $item->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Payment Terms </label>
                                                                <select name="pay_terms" id="pay_terms" class="form-control"
                                                                    readonly disabled>
                                                                    <option value="">Select...</option>

                                                                    @foreach ($terms as $item)
                                                                        <option value="{{ $item->value }}" {{ $invoice->pay_terms==$item->value? "selected":"" }}>{{ $item->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Due Date</label>
                                                                <input type="date" value="{{ $invoice->due_date }}" class="form-control" name="due_date"
                                                                    id="due_date" readonly disabled>
                                                            </div>

                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Contact Number</label>
                                                                <input type="text" value="{{ $invoice->contact_no }}" class="form-control" name="contact_no"
                                                                    id="contact_no" readonly disabled>
                                                            </div>

                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Shipping Address</label>
                                                                <input type="text" value="{{ $invoice->address }}" class="form-control" name="address"
                                                                    id="address" readonly disabled>
                                                            </div>

                                                            <div class="col-sm-3 form-group">
                                                                <label for="">Delivery Note No</label>
                                                                <input type="text" value="{{ $invoice->deliveryNote->delivery_note_no }}" class="form-control" name="address"
                                                                    id="address" readonly disabled>
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
                                                        <th>Vat</th>
                                                        <th>Discount</th>
                                                        {{-- <th>Net Amount</th> --}}
                                                        <th>Total Price </th>
                                                        {{-- <th>Action</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody class="all-data-area">
                                                    @foreach (App\InvoiceItem::where('invoice_id',$invoice->id)->get() as $item)
                                                    <tr class="data-row">
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $item->barcode }}</td>
                                                        <td>{{$item->item->item_name }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ $item->unit }}</td>
                                                        <td>{{ $item->unit_price }}</td>
                                                        <td>{{number_format((float)( $item->vat_amount),'2','.','')}}</td>
                                                        <td></td>
                                                        <td>{{number_format((float)( $item->cost_price),'2','.','') }}</td>
                                                        <td>{{-- {{ $item->cost_price }} --}}</td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>



                                            </table>
                                        </div>

                                        <div class="row d-flex justify-content-end pt-1">
                                            <div class="col-md-5">
                                                <div class="form-group row">
                                                    <label for="" class="col-7 d-flex align-items-center">TAXABLE SUPPLIES (AED):</label>
                                                    <input type="text" class="form-control col-5" name="tax_sup" id="tarek"
                                                            min="0" step="any" class="form-control" value="{{number_format((float)( App\InvoiceItem::where('invoice_id',$invoice->id)->sum('total_unit_price')), 2,'.','')    }}" disabled>
                                                </div>

                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label for="" class="col-5 d-flex align-items-center">VAT (AED):</label>
                                                    <input type="number" placeholder="VAT" min="0" step="any"
                                                    class="form-control col-7" value="{{number_format((float)(  App\InvoiceItem::where('invoice_id',$invoice->id)->sum('vat_amount')), 2,'.','')   }}" name="total_vat"
                                                    id="total_vat" disabled>
                                                </div>

                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="" class="col-7 d-flex align-items-center">Total Amount (AED):</label>
                                                    <input type="number" name="total_gross" placeholder="Final Discount"
                                                    min="0" step="any" class="form-control col-5" value="{{number_format((float)(App\InvoiceItem::where('invoice_id',$invoice->id)->sum('cost_price')), 2,'.','')   }}"
                                                    id="total_gross" disabled>
                                                </div>

                                            </div>
                                        </div>
                                        @if ($invoice->delivery_note_id==null)
                                        <div class="row d-flex justify-content-end pt-1">
                                            <div class="col-md-2">
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group row">
                                                    <label for="" class="col-8 d-flex align-items-center">Amount From Customer ( <small>AED</small> ):</label>
                                                    <input type="number" name="amount_from" placeholder="Amount"
                                                    min="0" step="any" value="{{$invoice->invoiceAmount ? number_format((float)($invoice->invoiceAmount->amount_from), 2,'.',''): '0.00'   }}" class="form-control col-4"
                                                    id="amount_from" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group row">
                                                    <label for="" class="col-8 d-flex align-items-center">Amount To Customer ( <small>AED</small> ):</label>
                                                    <input type="number" name="to" placeholder="Amount"
                                                    min="0" step="any" class="form-control col-4"
                                                    id="amount_to" value="{{  $invoice->invoiceAmount ? ($invoice->invoiceAmount->amount_to>0?  number_format((float)($invoice->invoiceAmount->amount_to), 2,'.',''): '0.00'): '0.00'   }}" disabled>
                                                </div>

                                            </div>
                                        </div>
                                        @endif
                                    <div class="row">
                                        <div class="col-12 text-center">
                                         <a  class="btn btn-sm btn-warning" href="{{ route('taxInvoIssue') }}">New</a>
                                         @if($invoice->delivery_note_id==null)
                                           {{-- <a href="{{ route('invoiceEdit',$invoice) }}" class="btn btn-sm btn-primary">Edit</a> --}}
                                           @endif
                                           <a href="{{ route('invoicePrint', $invoice) }}" class="btn btn-sm btn-secondary" id="invoice_print" target="_blank">print</a>

                                        </div>
                                    </div>
                                    </form>

                                </div>
                            </div>
                            @endif

                          </div>
                        </div>




                        <div class="col-md-2">
                            <div class="row">
                                <h5 style="white-space: nowrap;">Invoices </h5>
                                <input type="text" class="form-control w-100" placeholder="Serach Invoice No" name="invoice_no" id="sale_tax_invoice_s">
                                <i class="bx bx-refresh btn btn-sm" id="refresh_invoice">Refresh</i>
                                <div class="invoice-items">
                                    <ul>
                                        @foreach ($invoicess as $invoice)

                                            <div class="col-md-12 btn btn-light mb-1 text-center"
                                                id="sale-invoice-details"
                                                data_target="{{ route('saleinvoiceView', $invoice) }}">
                                                {{ $invoice->invoice_no }}
                                               </small>
                                            </div>
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

    <script>
        $(document).ready(function() {

            var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();

            $(document).on("click", "#sale-invoice-details", function(e) {
                e.preventDefault();
                $(this).addClass('active-button-sale').siblings('div').removeClass('active-button-sale');
                var that = $(this);
                var urls = that.attr("data_target");
                // alert(urls);
                delay(function() {
                    $.ajax({
                        url: urls,
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        success: function(response) {
                            //   alert('ok');
                            console.log(response);
                            $(".details-view").empty().append(response.page);

                        },
                        error: function() {
                              alert('Problem Found');
                        }
                    });
                }, 999);
            });

            $(document).on("keyup", "#sale_tax_invoice_s", function(e) {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('searchSaleInvoice') }}",
                        method: "GET",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(response) {
                            // console.log(response);
                            $(".invoice-items").empty().append(response.page);
                        }
                    })
            });
            $(document).on("keyup", "#dNo", function(e) {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('searchDNo') }}",
                    method: "GET",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        // console.log(response);
                        $(".delivery-note").empty().append(response.page);
                    }
                })
            });
            $('#month').change(function() {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('searchDNoMonth') }}",
                    method: "GET",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        // console.log(response);
                        $(".delivery-note").empty().append(response.page);
                    }
                })
            });

            $('#date').change(function() {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('searchDNoDate') }}",
                    method: "GET",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        // console.log(response);
                        $(".delivery-note").empty().append(response.page);
                    }
                })
            });

            $('#to').change(function() {
                var to = $(this).val();
                var from = $('#from').val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('searchDNoDateRange') }}",
                    method: "GET",
                    data: {
                        from: from,
                        to:to,
                        _token: _token,
                    },
                    success: function(response) {
                        // console.log(response);
                        $(".delivery-note").empty().append(response.page);
                    }
                })
            });

            $(document).on("click", "#sale-order-details", function(e) {
                e.preventDefault();
                $(this).addClass('active-button-sale').siblings('div').removeClass('active-button-sale');

                var that = $(this);
                var urls = that.attr("data_target");
                // alert(urls);
                delay(function() {
                    $.ajax({
                        url: urls,
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        success: function(response) {
                            //   alert('ok');
                            console.log(response);
                            $(".details-view").empty().append(response.page);
                        },
                        error: function() {
                            alert('Problem Found');
                        }
                    });
                }, 999);
            });

            $(document).on("click", "#searchSO", function(e) {
                var value = $(this).val();
                if (value == 'hide') {
                    $("#searchSO").val('show');
                    $(".search-class").show();

                } else {
                    $("#searchSO").val('hide');
                    $(".search-class").hide();
                }

            });


        });

    </script>
@endpush
