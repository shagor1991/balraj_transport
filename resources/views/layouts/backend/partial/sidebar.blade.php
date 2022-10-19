<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ asset('assets/backend')}}/html/ltr/vertical-menu-boxicons-template/index.html">

                    @php
                        $settings= \App\Setting::where('config_name', 'company_name')->first()
                    @endphp

                    <p style="font-size: 14px; color: #fff;">{{ $settings->config_value}}</p>
                </a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
            
            @if (Auth::user()->hasPermissionAny(['app.mapping.index','app.project.index','app.bank_details.index']))
            <li class="nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="File">File</span></a>
                <ul class="menu-content">

                    @if (Auth::user()->hasPermission('app.mapping.index'))
                        <li class="{{ Request::is('mapping*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('mapping.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Maping">Maping</span>
                            </a>
                        </li> 
                    @endif

                    @if (Auth::user()->hasPermission('app.project.index'))
                    <li class="{{ Request::is('project-details*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('projectDetails') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Maping">Business Details</span>
                        </a>
                    </li>
                    @endif

                    @if (Auth::user()->hasPermission('app.bank_details.index'))
                    <li class="{{ Request::is('bank-details*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('bankDetails') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Bank details">Bank details</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (Auth::user()->hasPermissionAny(['app.master_account.index','app.account_head.index','app.cost_center.index', 'app.profit_center.index', 'app.party_info.index', 'app.journal_entry', 'app.acreport.gl', 'app.acreport.tb', 'app.acreport.is', 'app.acreport.bs', 'app.acreport.cfs', 'app.document']))

            <li class="navigation-header text-truncate"><span data-i18n="UI Elements">Accounting</span> </li>


            @if (Auth::user()->hasPermissionAny(['app.master_account.index','app.account_head.index']))
            <li class="nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Chart of Accounts">Chart of Accounts</span></a>
                <ul class="menu-content">
                    @if (Auth::user()->hasPermission('app.master_account.index'))
                    <li class="{{ Request::is('master-accounts-details*') || Request::is('master-details/*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('masteAccDetails') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Master Account">Master Account</span>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user()->hasPermission('app.account_head.index'))
                    <li class="{{ Request::is('accounts-head-details') || Request::is('accounts-head-details/*') || Request::is('accounts-head-details/*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('accHeadDetails') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Account Head">Account Head</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (Auth::user()->hasPermission('app.cost_center.index'))
            <li class="nav-item {{ Request::is('cost-center-details') || Request::is('cost-center/edit/*') || Request::is('cost-center-details/*') ? 'active' : ''}}">
                <a href="{{ route('costCenterDetails') }}">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Cost Center">Cost Center</span>
                </a>
            </li>
            @endif


            @if (Auth::user()->hasPermission('app.profit_center.index'))
            <li class="nav-item {{ Request::is('profit-details') || Request::is('student/*') || Request::is('profit-center/edit/*') ? 'active' : ''}}">
                <a href="{{ route('profitCenterDetails') }}">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Profit Center">Profit Center</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->hasPermission('app.party_info.index'))
            <li class="nav-item {{ Request::is('party-info') || Request::is('party-info/*')  ? 'active' : ''}}">
                <a href="{{ route('partyInfoDetails') }}">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Party Info">Party Info</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->hasPermission('app.document'))
            <li class="nav-item {{ Request::is('document') || Request::is('document/*')  ? 'active' : ''}}">
                <a href="{{ route('document.index') }}">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Document">Document</span>
                </a>
            </li>
            @endif

            
            <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Journal">Vouchers</span></a>
                <ul class="menu-content">
                    {{-- @if(auth()->user()->hasPermission('app.journal_entry')) --}}
                    <li  class="{{ Request::is('debit-voucher*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('debit-voucher') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Entry">Debit Voucher</span>
                        </a>
                    </li>
                    {{-- @endif --}}
                    {{-- @if(auth()->user()->hasPermission('app.journal_authorize')) --}}
                    <li class="{{ Request::is('credit-voucher*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('credit-voucher') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Authorization">Credit Voucher</span>
                        </a>
                    </li>
                    {{-- @endif --}}

                    {{-- @if(auth()->user()->hasPermission('app.journal_authorize')) --}}
                    <li class="{{ Request::is('journal-voucher*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('journal-voucher') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Authorization">Journal Voucher</span>
                        </a>
                    </li>
                    {{-- @endif --}}

                </ul>
            </li>

            <li class="nav-item {{ Request::is('document') || Request::is('document/*')  ? 'active' : ''}}">
                <a href="{{ route('form-receipt-voucher') }}">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Document">Receipt Voucher</span>
                </a>
            </li>

            {{-- @if (Auth::user()->hasPermissionAny(['app.journal_entry','app.journal_authorize','app.journal_approval'])) --}}
            <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Journal">Journal</span></a>
                <ul class="menu-content">
                    {{-- @if(auth()->user()->hasPermission('app.journal_entry')) --}}
                    <li  class="{{ Request::is('journal-entry*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('journalEntry') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Entry">Entry</span>
                        </a>
                    </li>
                    {{-- @endif
                    @if(auth()->user()->hasPermission('app.journal_authorize')) --}}
                    <li class="{{ Request::is('journal-authorize*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('journalAuthorize') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Authorization">Authorization</span>
                        </a>
                    </li>
                    {{-- @endif
                    @if (auth()->user()->hasPermission('app.journal_approval')) --}}
                    <li class="{{ Request::is('journal-approval*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('journalApproval') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Authorization">Approval</span>
                        </a>
                    </li>
                    {{-- @endif --}}

                    <li class="{{ Request::is('journal-approved*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('Journals') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Authorization">Journals</span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- @endif --}}

            @if (Auth::user()->hasPermissionAny(['app.acreport.gl','app.acreport.tb','app.acreport.is', 'app.acreport.bs', 'app.acreport.cfs']))
            <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Accounts Report">Accounts Report</span></a>
                <ul class="menu-content">
                    @if (auth()->user()->hasPermission('app.acreport.gl'))
                    <li class="{{ Request::is('attendance/*') || Request::is('attendance') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('general-ledger') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="General Ledger">General Ledger</span>
                        </a>
                    </li>

                    <li class="{{ Request::is('attendance/*') || Request::is('attendance') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('partyLedger') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="General Ledger">Party Ledger</span>
                        </a>
                    </li>

                    @endif
                    @if (auth()->user()->hasPermission('app.acreport.tb'))
                    <li class="{{ Request::is('employee-attendance/*') || Request::is('employee-attendance') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('trial-balance') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Trial Balance">Trial Balance</span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->hasPermission('app.acreport.is'))
                    <li class="{{ Request::is('student-leave*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('under-const') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Income Statement / Profit Loss">Income Statement / Profit Loss</span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->hasPermission('app.acreport.bs'))
                    <li class="{{ Request::is('student-leave*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('under-const') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Balance Sheet">Balance Sheet</span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->hasPermission('app.acreport.cfs'))
                    <li class="{{ Request::is('student-leave*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('under-const') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Cash Flow Statement">Cash Flow Statement</span>
                        </a>
                    </li>
                    @endif

                    @if (auth()->user()->hasPermission('app.acreport.cfs'))
                    <li class="{{ Request::is('accounts-payable-ledger*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('ac-payable-ledger') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Accounts Payable Ledger">Accounts Payable Ledger</span>
                        </a>
                    </li>
                    @endif

                    @if (auth()->user()->hasPermission('app.acreport.cfs'))
                    <li class="{{ Request::is('accounts-receivable-ledger*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('ac-receivable-ledger') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Accounts Receivable Ledger">Accounts Receivable Ledger</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @endif
            {{-- If Clause end - Accounting --}}

            
            @if (Auth::user()->hasPermissionAny(['app.daily_sales', 'app.monthly_sales','app.delivery_summery']))
            <li class=" navigation-header text-truncate"><span data-i18n="Report">Report</span> </li>
                @if (auth()->user()->hasPermission('app.daily_sales'))
                <li class="nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Daily Sales">Daily Sales</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('daily-sale-report/*') || Request::is('daily-sale-report') || Request::is('received/*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{ route('dailySaleReport') }}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Product Wise">Product Wise</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('invoice-wise-sale-summary*') || Request::is('invoice-wise-sales') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{ route('InvoiceWiseSaleSummary') }}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Invoice Wise">Invoice Wise</span>
                            </a>
                        </li>                        
                    </ul>
                </li>
                @endif
                @if (auth()->user()->hasPermission('app.monthly_sales'))
                <li class=" nav-item"><a href="{{ route('monthly-sale-report') }}"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Monthly Sales">Monthly Sales</span></a></li>
                @endif
                @if (auth()->user()->hasPermission('app.delivery_summery'))
                <li class=" nav-item"><a href="{{ route('under-const') }}"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Delivery Summery">Delivery Summary</span></a></li>
                @endif
                {{-- @if (auth()->user()->hasPermission('app.monthly_sales')) --}}
                <li class=" nav-item"><a href="{{ route('businessSummery') }}"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Business Summary Report">Business Summary Report</span></a></li>
                {{-- @endif --}}
            @endif
            {{-- end of Report --}}

            

            @if (Auth::user()->hasPermissionAny(['app.access_control.user', 'app.access_control.role', 'app.access_control.settings']))

                <li class=" navigation-header text-truncate"><span data-i18n="Support">Access Control </span> </li>
                @if (Auth::user()->hasPermission('app.access_control.user'))                        
                <li class=" nav-item {{ Request::is('user*') ? 'active' : ''}}">
                    <a class="d-flex align-items-center" href="{{ route('user.index')}}">
                        <i class="bx bx-right-arrow-alt"></i>
                        <span class="menu-item text-truncate" data-i18n="User Management">User Management</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->hasPermission('app.access_control.role'))
                <li class=" nav-item {{ Request::is('role*') ? 'active' : ''}}"><a href="{{route('role.index')}} "><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Roles">Roles</span></a></li>
                @endif
                @if (Auth::user()->hasPermission('app.access_control.settings'))
                <li class=" nav-item {{ Request::is('settings*') ? 'active' : ''}}"><a href="{{route('settings.index')}} "><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Roles">Settings</span></a></li>
                @endif

            @endif
            {{-- end of Access Control  --}}
                {{-- <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Users">Users</span></a></li> --}}

                <li class=" navigation-header text-truncate"><span data-i18n="Support">Logout</span> </li>

            <li class=" nav-item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i class="bx bx-log-out-circle"></i><span class="menu-title text-truncate" data-i18n="Logout">Logout</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
