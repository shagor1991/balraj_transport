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
                    <a href="{{route('vehicle-service')}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab">
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
                    <a href="{{route("supplier-invoice-list")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
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
                                <div class="card cardStyleChange">
                                    <div class="row" id="table-bordered">
                                        <div class="col-12">
                                            <div class="cardStyleChange p-2">
                                                <div class="d-flex">
                                                    <h4 class="flex-grow-1">Weigh Bridge List</h4>
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
                                                                <input type="text" class="inputFieldHeight form-control " name="search" placeholder="Search by ">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="table-responsive" style="min-height: 300px">
                                                    <table class="table mb-0 table-sm table-hover">
                                                        <thead  class="thead-light">
                                                            <tr style="height: 50px;">
                                                                <th>Date</th>
                                                                <th>Truck</th>
                                                                <th>Material</th>
                                                                <th>Crusher/Site</th>
                                                                <th>DSTN</th>
                                                                <th>Serial</th>
                                                                <th>WGT</th>
                                                                {{-- <th>Third Party</th> --}}
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-sm">
                                                            @foreach ($records as $t_record)
                                                            <tr class="trFontSize">
                                                                <td>{{$t_record->date}}</td>
                                                                <td>{{$t_record->truck->vehicle_number}}</td>
                                                                <td>{{$t_record->material}}</td>
                                                                <td>{{$t_record->crusher}}</td>
                                                                <td>{{$t_record->destination}}</td>
                                                                <td>{{$t_record->serial_no}}</td>
                                                                <td>{{$t_record->weight}}</td>
                                                                {{-- <td>{{$t_record->supplier->pi_name}}</td> --}}
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
                <form class="form form-vertical" id="save-form" action="{{ route('save-truck-service')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <section id="basic-vertical-layouts">
                        <div class="row match-height">
                            <div class="col-md-12 col-12">
                                <div class="cardStyleChange">
                                    <div class="card-body">
                                        <div class="form-body">
                                            <h4> WEIGH BRIDGE / DELIVERY NOTE</h4>
                                            <div class="row">
                                                <div class="col-md-1 col-12 ">
                                                    <label for="">Party</label>
                                                </div>
                                                <div class="col-md-6 col-12 commonSelect2Style">
                                                    <select name="party_id" id="fld_customer" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Name</option>
                                                        @foreach ($customers as $customer)
                                                        <option value="{{$customer->id}}" >{{ $customer->pi_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-5"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">Date</label>
                                                    <input type="date" name="date" id="fld_date" class="inputFieldHeight form-control" placeholder="Date">
                                                </div>
                                                <div class="col-md-3 col-12 commonSelect2Style">
                                                    <label for="">Truck</label>
                                                    <select name="truck_id" id="fld_truck" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Name</option>
                                                        @foreach ($trucks as $truck)
                                                        <option value="{{$truck->id}}" vehicle-no="{{$truck->vehicle_number}}" >{{ $truck->vehicle_number}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">Material</label>
                                                    <input type="text" name="material" id="fld_material" class="inputFieldHeight form-control" placeholder="Material">
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">Crusher/Site </label>
                                                    <input type="text" name="crusher" id="fld_crusher" class="inputFieldHeight form-control" placeholder="Crusher/Site">
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">DSTN</label>
                                                    <input type="text" name="dstm" id="fld_dstn" class="inputFieldHeight form-control" placeholder="DSTN">
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">Serial</label>
                                                    <input type="text" name="serial" id="fld_serial" class="inputFieldHeight form-control" placeholder="Serial">
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">WGT</label>
                                                    <input type="text" name="wgt" id="fld_wight" class="inputFieldHeight form-control" placeholder="WGT">
                                                </div>
                                                <div class="col-md-3 col-12 commonSelect2Style">
                                                    <label for="">Third Party</label>
                                                    <select name="third_party_id" id="fld_truck_owner" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Name</option>
                                                        @foreach ($suppliers as $supplier)
                                                        <option value="{{$supplier->id}}" party-name="{{$supplier->pi_name}}">{{ $supplier->pi_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12 d-flex justify-content-end mt-2 mb-2" >
                                                    <button type="button" class="btn btn-primary formButton add_item" title="Searching">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" alt="" srcset="" width="20">
                                                            </div>
                                                            <div><span>Add</span></div>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <table class="table mb-0 table-sm table-hover">
                                            <thead  class="thead-light">
                                                <tr style="height: 50px;">
                                                    <th>Date</th>
                                                    <th>Truck</th>
                                                    <th>Material</th>
                                                    <th>Crusher/Site</th>
                                                    <th>DSTN</th>
                                                    <th>Serial</th>
                                                    <th>WGT</th>
                                                    <th>Third Party</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-sm" id="items_cart">
                                                <tr class="trFontSize">
                                                    <td>10-Oct-2022</td>
                                                    <td>19383-RAK</td>
                                                    <td>60-95MM</td>
                                                    <td>Power INT.</td>
                                                    <td>Rak Port</td>
                                                    <td>109667</td>
                                                    <td>82.88</td>
                                                    <td>Al Anood Transport Raghbir</td>
                                                    <td class="">
                                                        <a href="#" class="btn truckInfoEdit" onclick="delete_item(0)" title="Delete" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;">
                                                            <img src="{{asset('assets/backend/app-assets/icon/delete-icon.png')}}" style=" height: 30px; width: 30px;">
                                                        </a>
                                                    </td>
                                                </tr>
                                                
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end mt-2 mb-2" >
                                        <button type="button" class="btn btn-primary formButton" id="record-submit" title="Searching">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset="" width="20">
                                                </div>
                                                <div><span>save</span></div>
                                            </div>
                                        </button>
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
                                            <h4> WEIGH BRIDGE / DELIVERY NOTE</h4>
                                            <div class="row">
                                                <div class="col-md-1 col-12 ">
                                                    <label for="">Party</label>
                                                </div>
                                                <div class="col-md-6 col-12 commonSelect2Style">
                                                    <select name="party_id" id="" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Name</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-5"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">Date</label>
                                                    <input type="date" name="date" class="inputFieldHeight form-control" placeholder="Date">
                                                </div>
                                                <div class="col-md-3 col-12 commonSelect2Style">
                                                    <label for="">Truck</label>
                                                    <select name="truck_id" id="" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Truck</option>
                                                        <option value="">75838-RAK</option>
                                                        <option value="">19838-RAK</option>
                                                        <option value="">75838-RAK</option>
                                                        <option value="">43838-RAK</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">Material</label>
                                                    <input type="text" name="material" class="inputFieldHeight form-control" placeholder="Material">
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">Crusher/Site </label>
                                                    <input type="text" name="crusher" class="inputFieldHeight form-control" placeholder="Crusher/Site">
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">DSTN</label>
                                                    <input type="text" name="dstm" class="inputFieldHeight form-control" placeholder="DSTN">
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">Serial</label>
                                                    <input type="text" name="serial" class="inputFieldHeight form-control" placeholder="Serial">
                                                </div>
                                                <div class="col-md-3 col-12 ">
                                                    <label for="">WGT</label>
                                                    <input type="text" name="wgt" class="inputFieldHeight form-control" placeholder="WGT">
                                                </div>
                                                <div class="col-md-3 col-12 commonSelect2Style">
                                                    <label for="">Third Party</label>
                                                    <select name="third_party_id" id="" class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Name</option>
                                                        <option value="">Al Anood Transport Raghbir</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 d-flex justify-content-end mt-2 mb-2" >
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
    // $(document).on("click", ".truckInfoEdit", function(e){
    //     e.preventDefault();
    //     $("#truckInfoEditModal").modal('show');
    // });

    // var items= @json(session('items'));
    @if(session('items'))
    var items=@json(session('items'));            
    @else
    var items=[];
    @endif

    function delete_item(id){
        // alert('Deleted');
        var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('remove-session-item') }}",
                        method: "POST",
                        data: {
                            data_id:   id,
                            _token: _token,
                        },
                        success: function(response) {
                            console.log(response);
                            create_service_table();
                            
                        }
                    });
    }

    $('.add_item').click(function(){
        var fld_date        = $('#fld_date').val();
        var fld_truck       = $('#fld_truck').val();
        var vehicle_no      = $('#fld_truck').find(':selected').attr('vehicle-no');
        var fld_material    = $('#fld_material').val();
        var fld_crusher     = $('#fld_crusher').val();
        var fld_dstn        = $('#fld_dstn').val();
        var fld_serial      = $('#fld_serial').val();
        var fld_wight       = $('#fld_wight').val();
        var fld_truck_owner = $('#fld_truck_owner').val();
        var truck_owner_name= $('#fld_truck_owner').find(':selected').attr('party-name');
        var fld_customer    = $('#fld_customer').val();

        // alert(vehicle_no);

        if(fld_date ==''){
            alert('Date is Required');
        }else if(fld_customer ==''){
            alert('Customer field is Required');
        }else if(fld_truck ==''){
            alert('Truck field is Required');
        }else if(fld_material ==''){
            alert('Material field is Required');
        }else if(fld_crusher ==''){
            alert('Crusher field is Required');
        }else if(fld_dstn ==''){
            alert('DSTN Field is Required');
        }else if(fld_serial ==''){
            alert('Serial Field is Required');
        }else if(fld_wight ==''){
            alert('Weight is Required');
        }else if(fld_truck_owner ==''){
            alert('Truck field is Required');
        }else{
                    // var value = $(this).val();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('add-to-session') }}",
                        method: "POST",
                        data: {
                            fld_customer:   fld_customer,
                            fld_date:       fld_date,
                            fld_truck:      fld_truck,
                            vehicle_no:     vehicle_no,
                            fld_material:   fld_material,
                            fld_crusher:    fld_crusher,
                            fld_dstn:       fld_dstn,
                            fld_serial:     fld_serial,
                            fld_wight:      fld_wight,
                            fld_truck_owner:fld_truck_owner,
                            truck_owner_name:truck_owner_name,
                            _token: _token,
                        },
                        success: function(response) {
                            // console.log(response);
                            items= response;

                            create_service_table();
                            
                        }
                    });
        }
    });

    function create_service_table(){
        console.log(items);
        console.log(items.length);
        var html='';
        if(items.length>0){
            console.log('if true');
            var img_url= "{{asset('assets/backend/app-assets/icon/delete-icon.png')}}";
            for (var i= 0; i < items.length; i++) {
                var item = items[i];
                
                html+= 
                '<tr class="trFontSize">' +
                    '<td>'+item.date+'</td>' +
                    '<td>'+item.vehicle_no+'</td>' +
                    '<td>'+item.material+'</td>' +
                    '<td>'+item.crusher+'</td>' +
                    '<td>'+item.dstn+'</td>'+
                    '<td>'+item.serial+'</td>'+
                    '<td>'+item.wight+'</td>'+
                    '<td>'+item.truck_owner_name+'</td>'+
                    '<td class="">'+
                        '<a href="#" class="btn truckInfoEdit" onclick="delete_item('+i+')" title="Delete" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;">'+
                            '<img src="'+img_url+'" style=" height: 30px; width: 30px;">'+
                        '</a>'+
                    '</td>'+
                '</tr>';

            }
        }else{
            console.log('if else');
            // console.log(html);
            html= '<tr > <td colspan="9" te> <p class="text-center"> No record! </p> </td> </tr>';
        }

        $('#items_cart').html(html);
    }

    $('.btn_create').click(function(){
        create_service_table();
    });


    $('#record-submit').click(function(){
        // alert('Alhamdulillah');
        if(items.length>0){
            $('#save-form').submit();
        }else{
            alert('No record to submit!');
        }
        
    });
        

</script>
@endpush
