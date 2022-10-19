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
                <a href="{{route("truck.index")}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Truck List&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                </a>
                <a href="{{route('truck-service')}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;Service&nbsp;&nbsp;&nbsp;</div>
                </a>
                <a href="{{route("supplier-invoice")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>Supplier</div>
                </a>
                <a href="{{route("customer-invoice")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>Invoicing</div>
                </a>
                <a href="{{route("invoice-list")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
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
                                        <h4 class="flex-grow-1">Truck List</h4>
                                        <div>
                                            <button type="button" class="btn btn-primary btn_create formButton mr-1" title="Add" data-toggle="modal" data-target="#newTruckAddModal">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Add New</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form  method="get">
                                                <div class="form-group">
                                                    <input type="text" class="inputFieldHeight form-control " name="search" placeholder="Search by Truck Number">
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
                                                        <th>Vehicle Number</th>
                                                        <th>Brand</th>
                                                        <th>Model</th>
                                                        <th>Origin</th>
                                                        <th>Engine Capacity</th>
                                                        <th>Number of Tyres </th>
                                                        <th>Owner</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-sm">
                                                    @foreach ($trucks as $truck)
                                                    <tr class="trFontSize">
                                                        <td>{{$truck->vehicle_number}}</td>
                                                        <td>{{$truck->brand}}</td>
                                                        <td>{{$truck->model}}</td>
                                                        <td>{{$truck->origin}}</td>
                                                        <td>{{$truck->engine_capacity}}</td>
                                                        <td>{{$truck->no_of_tyres}}</td>
                                                        <td>{{$truck->party->pi_name}}</td>
                                                        <td class="">
                                                            <a href="#" class="btn truckInfoEdit" title="Edit" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;">
                                                                <img src="{{asset('assets/backend/app-assets/icon/edit-icon.png')}}" style=" height: 30px; width: 30px;">
                                                            </a>
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

{{-- modal --}}
    <div class="modal fade bd-example-modal-lg" id="newTruckAddModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <section class="print-hideen border-bottom">
                <div class="d-flex flex-row-reverse">
                    <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                    <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div>
                </div>
            </section>
            <div class="content-body">
                <form class="form form-vertical" action="{{ route('truck.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <section id="basic-vertical-layouts">
                        <div class="row match-height">
                            <div class="col-md-12 col-12">
                                <div class="cardStyleChange">
                                    <div class="card-body">
                                        <div class="form-body">
                                            <h4>Truck Information</h4>
                                            <div class="row">
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Vehicle Number</label>
                                                    <input type="text" name="vehicle_number" class="inputFieldHeight form-control" placeholder="Vehicle Number">
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Brand</label>
                                                    <input type="text" name="brand" class="inputFieldHeight form-control" placeholder="Brand">
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Model </label>
                                                    <input type="text" name="model" class="inputFieldHeight form-control" placeholder="Model">
                                                </div>
                                                <div class="col-md-4 col-12 commonSelect2Style">
                                                    <label for="">Origin</label>
                                                    <select name="origin" id="" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Country</option>
                                                        @foreach ($countries as $country)
                                                        <option value="{{$country->name}}">{{$country->name}}</option>
                                                        @endforeach
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Engine Capacity</label>
                                                    <input type="text" name="engine_capacity" class="inputFieldHeight form-control" placeholder="Engine Capacity">
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Number of Tyres</label>
                                                    <input type="text" name="number_of_tyres" class="inputFieldHeight form-control" placeholder="Number of Tyres">
                                                </div>
                                                <div class="col-md-4 col-12 commonSelect2Style">
                                                    <label for="">Owner</label>
                                                    <select name="owner" id="" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Owner</option>
                                                        @foreach ($parties as $party)
                                                        <option value="{{$party->id}}">{{$party->pi_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-12 ">
                                                    
                                                </div>
                                                <div class="col-md-2 d-flex justify-content-end mt-2 mb-2" >
                                                    <button type="submit" class="btn btn-primary formButton" title="Searching">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset="" width="20">
                                                            </div>
                                                            <div><span> Save</span></div>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="truckInfoEditModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <section class="print-hideen border-bottom">
                <div class="d-flex flex-row-reverse">
                    <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                    <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div>
                </div>
            </section>
            <div class="content-body">
                <form class="form form-vertical" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <section id="basic-vertical-layouts">
                        <div class="row match-height">
                            <div class="col-md-12 col-12">
                                <div class="cardStyleChange">
                                    <div class="card-body">
                                        <div class="form-body">
                                            <h4>Truck Information Update</h4>
                                            <div class="row">
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Vehicle Number</label>
                                                    <input type="text" name="vehicle_number" class="inputFieldHeight form-control" placeholder="Vehicle Number">
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Brand</label>
                                                    <input type="text" name="brand" class="inputFieldHeight form-control" placeholder="Brand">
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Model </label>
                                                    <input type="text" name="model" class="inputFieldHeight form-control" placeholder="Model">
                                                </div>
                                                <div class="col-md-4 col-12 commonSelect2Style">
                                                    <label for="">Origin</label>
                                                    <select name="origin" id="" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Country</option>
                                                        <option value="">Bangladesh</option>
                                                        <option value="">India</option>
                                                        <option value="">Pakisthan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Engine Capacity</label>
                                                    <input type="text" name="engine_capacity" class="inputFieldHeight form-control" placeholder="Engine Capacity">
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Number of Tyres</label>
                                                    <input type="text" name="number_of_Tyres" class="inputFieldHeight form-control" placeholder="Number of Tyres">
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                    <label for="">Owner</label>
                                                    <input type="text" name="woner" class="inputFieldHeight form-control" placeholder="Owner">
                                                </div>
                                                <div class="col-md-6 col-12 ">
                                                    
                                                </div>
                                                <div class="col-md-2 d-flex justify-content-end mt-2 mb-2" >
                                                    <button type="submit" class="btn btn-primary formButton" title="Searching">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset="" width="20">
                                                            </div>
                                                            <div><span>Update</span></div>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
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
