<section class="invoice-view-wrapper student_profle-print">
    <div class="row">
        <!-- invoice view page -->
        <div class="col-xl-12 col-md-12 col-12">
            <div class="invoice-print-area mb-2">
                <div class="card-body pb-0 mx-25" >
                    <!-- logo and title -->
                    <div class="d-flex justify-content-center">
                        <div class="id-card-land mr-2">
                            <img src="{{ asset('assets/backend/app-assets')}}/beps-logo.png"  class="img-fluid" alt="" width="100">
                        </div>
                        <div>
                            <h3 class="text-primary">
                                @php
                                    $school_name = App\Setting::where('config_name', 'school_name')->first();
                                    $school_address = App\Setting::where('config_name', 'school_address')->first();
                                    $school_tele = App\Setting::where('config_name', 'school_tele')->first();
                                    $school_email = App\Setting::where('config_name', 'school_email')->first();
                                @endphp
                                <span class="h1">مدرسة بنجلاديش الإنجليزية الخاصة</span><br>
                                {{$school_name->config_value}}
                            </h3>
                        </div>
                    </div>
                    <div class="text-center">                                        
                        <p style="margin-bottom:0px; font-size: 12px;">
                            RECOGNISED BY MINISTRY OF EDUCTION -U.A.E - (AFFILIATED OT BISE, DHAKA SCHOOL CODE:9601, COLLEGE CODE 9526, EIIN:133909)
                        </p>
                        <p style="font-size: 12px;">
                            <span>Tel: {{$school_tele->config_value}}.</span>
                            <span class="mx-1">Email: {{$school_email->config_value}} </span>
                            <span>P.O.Box: 12275 - </span>
                            <span>{{$school_address->config_value}}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>