@php
    // Assuming 'role_type' is the field in the users table that stores the user's role type.
    $userRoleType = auth()->user()->role_type;
@endphp

<nav class="navbar-default navbar-side" role="navigation" style="margin-top: 60px;">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li>
                <a class="active-menu" href="#"><i class="fa fa-dashboard"></i>System<span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('/') }}">Dashboard</a>
                    </li>
                    @if ($userRoleType == 2)
                        <li>
                            <a href="{{ route('settings') }}">Settings</a>
                        </li>
                    @endif
                </ul>
            </li>

            <li>
                <a href="#"><i class="fa fa-user"></i>Vendors<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('vendors/form/create') }}">Add New Vendor</a>
                    </li>
                    <li>
                        <a href="{{ route('vendors') }}">Vendors list<span class="label label-dependencies pull-right"
                                style="margin-top:4px">{{ Cache::get('countVendors') }}</span></a>
                    </li>
                </ul>
            </li>
            @if ($userRoleType == 2)
                <li>
                    <a href="#"><i class="fa fa-user"></i>Customer<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ URL::to('clients/form/create') }}">Add New Customer</a>
                        </li>
                        <li>
                            <a href="{{ route('clients') }}">Customer List<span
                                    class="label label-dependencies pull-right"
                                    style="margin-top:4px;">{{ Cache::get('countClients') }}</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            @if ($userRoleType == 2)
                <li>
                    <a href="#"><i class="fa fa-user"></i>Companies<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ URL::to('companies/form/create') }}">Add New Company</a>
                        </li>
                        <li>
                            <a href="{{ route('companies') }}">Companies list<span
                                    class="label label-dependencies pull-right"
                                    style="margin-top:4px">{{ Cache::get('countCompanies') }}</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            <li>
                <a href="#"><i class="fa fa-cube"></i>Products<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('products/form/create') }}">Add New Product</a>
                    </li>
                    <li>
                        <a href="{{ route('products') }}">Inventory list<span class="label label-marketing pull-right"
                                style="margin-top:4px">{{ Cache::get('countProducts') }}</span></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-money"></i>Transactions<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('transactions/form/create') }}">New Credit</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('transactions/expence-create') }}">New Debit</a>
                    </li>
                    <li>
                        <a href="{{ route('transactions') }}">View Transactions<span
                                class="label label-sales pull-right" style="margin-top:4px"></span></a>
                    </li>
                </ul>
            </li>
            {{-- @if ($userRoleType == 2 || $userRoleType == 3) --}}
                <li>
                    <a href="#"><i class="fa fa-money"></i>Daybooks<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ URL::to('daybooks/expence-create') }}">New Expense</a>
                        </li>
                        <li>
                            <a href="{{ route('daybooks') }}">View Expenses<span class="label label-sales pull-right"
                                    style="margin-top:4px"></span></a>
                        </li>
                    </ul>
                </li>
            {{-- @endif --}}
            <li>
                <a href="#"><i class="fa fa-bar-chart-o"></i>Reports</a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('transactions/income-report') }}">Income Report</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('transactions/expence-report') }}">Expense Report</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('transactions/incomeVsExpense') }}">Income vs Expense Report</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('transactions/income-report-between-dates/generate') }}">Report Between
                            Dates</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-money"></i>Bank & Cash<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('accounts/form/create') }}">New Account</a>
                    </li>
                    <li>
                        <a href="{{ route('accounts') }}">List Accounts<span class="label label-sales pull-right"
                                style="margin-top:4px">{{ Cache::get('countFinances') }}</span></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-money"></i>Ledger<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('accounts/form/create') }}">Add New Product</a>
                    </li>
                    <li>
                        <a href="{{ route('deals') }}">Ledger List<span class="label label-dependencies pull-right"
                                style="margin-top:4px">{{ Cache::get('countDeals') }}</span></a>
                    </li>
                </ul>
            </li>
            @if ($userRoleType == 2)
                <li>
                    <a href="#"><i class="fa fa-user"></i>Sales/Rental/Purchase<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        @if (isset($invoiceId))
                            <li>
                                <a href="{{ route('invoice.show', ['invoiceId' => $invoiceId]) }}">View Invoice</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('sales') }}">Sales<span class="label label-sales pull-right"
                                    style="margin-top:4px">{{ Cache::get('countSales') }}</span></a>
                        </li>
                        <li>
                            <a href="{{ route('rents') }}">Rental<span class="label label-sales pull-right"
                                    style="margin-top:4px">{{ Cache::get('countRents') }}</span></a>
                        </li>
                        <li>
                            <a href="{{ route('purchase') }}">Purchase<span class="label label-purchase pull-right"
                                    style="margin-top:4px">{{ Cache::get('countPurchases') }}</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            @if ($userRoleType == 2)
                <li style="margin-top: 10px; color: #dee7f1; font-size: 14px; background-color: #09192a;">
                    <h4>Informations <a href="{{ route('reload-info') }}"><span
                                class="refresh-info">Refresh</span></a></h4>
                    {{-- <li><i class="fa fa-money" aria-hidden="true"></i> Today income:  {{ Cache::get('todayIncome') }}</li>
                    <li><i class="fa fa-money" aria-hidden="true"></i> Yesterday income: {{ Cache::get('yesterdayIncome') }}</li>
                    <li><i class="fa fa-money" aria-hidden="true"></i> Cash turnover:  {{ Cache::get('cashTurnover') }}</li> --}}
                    <br>
                    <li><i class="fa fa-cogs" aria-hidden="true"></i> Operations:
                        {{ Cache::get('countAllRowsInDb') }}</li>
                    <li><i class="fa fa-book" aria-hidden="true"></i> System logs:
                        {{ Cache::get('countSystemLogs') }}</li>
                </li>
            @endif
        </ul>
    </div>
</nav>
