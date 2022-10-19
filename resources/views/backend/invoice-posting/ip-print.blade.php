@extends('layouts.pdf.app')
@section('css')
    <style>
    .form-control {
        border: 0;
    }
    </style>
@endsection
@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1>Invoice Posting</h1>
                        </div>
                    </div>
                    <div class="card-body content-padding">
                        <div class="row">
                            <div class="col-sm-4 col-12">
                                <label>Invoice Posting No</label>
                                <p>{{$invoice_posting->invoice_posting_no}}</p>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label>Goods Received No</label>
                                <p>{{$invoice_posting->goods_received_no}}</p>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label>PO No</label>
                                <p>{{$invoice_posting->po_no}}</p>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label>PR No</label>
                                <p>{{$invoice_posting->pr_no}}</p>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label for="project_id">Branch Name</label>
                                <p>{{$invoice_posting->projectInfo->proj_name}}</p>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label>Supplier Name</label>
                                <p>{{$invoice_posting->partInfo->pi_name}}</p>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label>Delivery Note Number</label>
                                <p>{{$invoice_posting->delivery_note}}</p>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th scope="col">Item Name</th>
                                <th scope="col">Color</th>
                                <th scope="col">Size</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Purchase Rate</th>
                            </tr>
                        </thead>
                        <tbody id="tempLists"  class="user-table-body">
                            @foreach ($details as $item)
                            <tr class="data-row">
                                <td> {{$item->itemName->barcode}} </td>
                                <td> {{$item->itemName->item_name}} </td>
                                <td> {{$item->itemName->brandName->name}} </td>
                                <td> {{$item->itemName->group_name}} </td>
                                <td> {{$item->quantity}} </td>
                                <td> {{$item->purchase_rate}} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row mt-5 pt-5">
                        <div class="col-12">
                            <div class="mt-5">
                                <div class="d-flex text-center" style="justify-content: space-between">
                                    <div>
                                        <h6>Prepared By:</h5>
                                        <h5>Mahidul Islam Bappi</h5>
                                    </div>
                                    <div>
                                        <h5>Endorsed By:</h5>
                                        <h5>Mohammad Habibur Rahman</h5>
                                    </div>
                                    <div>
                                        <h5>Authorized By:</h5>
                                        <h5>Md. Akhter Hossain</h5>
                                    </div>
                                    <div>
                                        <h5>Approved By:</h5>
                                        <h5>Salim Osman</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
