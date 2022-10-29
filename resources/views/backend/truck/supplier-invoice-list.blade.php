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

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
                <a href="{{route("vehicle.index")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/vehicle-icon.png')}}" alt="" srcset="" class="img-fluid" width="100">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Vehicle List&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                </a>
                <a href="{{route('vehicle-service')}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/service-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;Service&nbsp;&nbsp;&nbsp;</div>
                </a>
                <a href="{{route("supplier-invoice")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/supplier-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>Supplier</div>
                </a>
                <a href="{{route("supplier-invoice-list")}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>Supplier Invoices</div>
                </a>
                <a href="{{route("customer-invoice")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/invoice-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>Invoicing</div>
                </a>
                <a href="{{route("invoice-list")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/invoice-list-icon.png')}}" alt="" srcset="" class="img-fluid" width="40">
                    </div>
                    <div>Invoice List</div>
                </a>
            </div>
            <div class="tab-content bg-white">
                <div class="tab-pane active">
                    <div class="row" id="table-bordered">
                        <div class="col-12">
                            <div class="cardStyleChange">
                                <div class="card-header">
                                    <div class="d-flex">
                                        <h4 class="flex-grow-1">Invoice List</h4>
                                        <div>
                                            {{-- <button type="button" class="btn btn-primary btn_create formButton mr-1" title="Add" data-toggle="modal" data-target="#newTruckAddModal">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Add New</span></div>
                                                </div>
                                            </button> --}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form  method="get">
                                                <div class="form-group">
                                                    {{-- <input type="text" class="inputFieldHeight form-control " name="search" placeholder="Search by Truck Number"> --}}
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive" style="min-height: 300px">
                                        <div class="table-responsive">
                                            <table class="table mb-0 table-sm table-hover">
                                                <thead  class="thead-light">
                                                    <tr style="height: 50px;">
                                                        <th>SL No</th>
                                                        <th>Supplier</th>
                                                        <th>Invoice No</th>
                                                        <th>Date</th>
                                                        <th>Pay Mode</th>
                                                        <th>Amount </th>
                                                        <th class="text-right pr-3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-sm">
                                                    @foreach ($invoices as $invoice)
                                                    <tr class="trFontSize">
                                                        <td>{{$loop->index+1}}</td>
                                                        <td>{{$invoice->supplier->pi_name}}</td>
                                                        <td>{{$invoice->invoice_no}}</td>
                                                        <td>{{$invoice->date}}</td>
                                                        <td>{{$invoice->pay_mode}}</td>
                                                        <td>{{$invoice->amount}}</td>
                                                        <td class="text-right pr-1">
                                                            <a href="{{ route('supplier-invoice-sumview', $invoice->id)}}" class="btn " target="_blank" title="Edit" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;">
                                                                <img src="{{asset('assets/backend/app-assets/icon/invoice-icon.png')}}" style=" height: 30px; width: 30px;">
                                                            </a>
                                                            <a href="{{ route('supplier-invoice-view', $invoice->id)}}" class="btn " title="Edit" target="_blank" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;">
                                                                <img src="{{asset('assets/backend/app-assets/icon/view-icon.png')}}" style=" height: 30px; width: 30px;">
                                                            </a>
                                                            {{-- <a href="{{ route('supplier-invoice-sumview', $invoice->id)}}" class="btn " title="Edit" target="_blank" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;">
                                                                <img src="{{asset('assets/backend/app-assets/icon/payment-icon.png')}}" style=" height: 30px; width: 30px;">
                                                            </a> --}}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    $(document).on("click", ".truckInfoEdit", function(e){
        e.preventDefault();
        $("#truckInfoEditModal").modal('show');
    });
</script>
@endpush
