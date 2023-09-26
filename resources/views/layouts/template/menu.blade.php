@php
    // Assuming 'role_type' is the field in the users table that stores the user's role type.
    $userRoleType = auth()->user()->role_type;
@endphp

<nav class="navbar-default navbar-side" role="navigation" style="margin-top: 60px; 
  ">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li>
                <a class="active-menu" href="#"><i class="fa fa-dashboard"></i>System<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('/') }}">Dashboard</a>
                        @if($userRoleType == 2)
                        <a href="{{ route('settings') }}">Settings</a>
                        @endif
                    </li>
                </ul>
            </li>

            <li>
                <a href="#"><i class="fa fa-user"></i>Vendors<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ URL::to('vendors/form/create') }}">Add New Vendor</a>
                        <a href="{{ route('vendors') }}">Vendors list<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countVendors') }}</span></a>
                    </li>
                </ul>
            </li>
            @if($userRoleType == 2)
            <li>
                <a href="#"><i class="fa fa-user"></i>Customer<span class="fa arrow"></span></a>


                <ul class="nav nav-second-level">
                    <li>
                        {{-- <a href="{{ route('clients') }}"><span class="label label-dependencies pull-right" style="margin-top:4px;">{{ Cache::get('countClients') }}</span></a> --}}
                        {{-- <a href="{{ route('vendors') }}">Vendors<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countVendors') }}</span></a> --}}
                        {{-- <a href="{{ route('employees') }}">Vendor Companies<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countEmployees') }}</span></a> --}}
                        {{-- <a href="{{ route('deals') }}">Ledger<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countDeals') }}</span></a> --}}
                        <a href="{{ URL::to('clients/form/create') }}">Add New Customer</a>
                        
                        <a href="{{ route('clients') }}">Customer List<span class="label label-dependencies pull-right" style="margin-top:4px;">{{ Cache::get('countClients') }}</span></a> 
                        
                    </li>
            </ul>
        </li>   
          @endif
            @if($userRoleType == 2)
            <li>
                <a href="#"><i class="fa fa-user"></i>Companies<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                        <li>
                            {{-- <a href="{{ route('clients') }}"><span class="label label-dependencies pull-right" style="margin-top:4px;">{{ Cache::get('countClients') }}</span></a> --}}
                            {{-- <a href="{{ route('vendors') }}">Vendors<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countVendors') }}</span></a> --}}
                            {{-- <a href="{{ route('employees') }}">Vendor Companies<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countEmployees') }}</span></a> --}}
                            {{-- <a href="{{ route('deals') }}">Ledger<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countDeals') }}</span></a> --}}
                            <a href="{{ URL::to('companies/form/create') }}">Add New Company</a>
                            
                            <a href="{{ route('companies') }}">Companies list<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countCompanies') }}</span></a>
                            
                        </li>
                </ul>
            </li>   
              @endif
              <li>
                <a href="#"><i class="fa fa-cube"></i>Products<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ URL::to('products/form/create') }}">Add New Product</a>
                            <a href="{{ route('products') }}">Inventory list<span class="label label-marketing pull-right" style="margin-top:4px">{{ Cache::get('countProducts') }}</span></a>

                        </li>
                    </ul>
              </li> 
              <li>
                <a href="#"><i class="fa fa-money"></i>Transactions<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ URL::to('transactions/form/create') }}">New Credit</a>
                            <a href="{{ URL::to('transactions/expence-create') }}">New Debit</a>
                            <a href="{{ route('transactions') }}">View  Transactions<span class="label label-sales pull-right" style="margin-top:4px"></span></a>
                        </li>
                    </ul>
              </li>

              <li>
                <a href="#"><i class="fa fa-money"></i>Daybooks<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            {{-- <a href="{{ URL::to('daybooks/form/create') }}">New Income</a> --}}
                            <a href="{{ URL::to('daybooks/expence-create') }}">New Expence</a>
                            <a href="{{ route('daybooks') }}">View Expences<span class="label label-sales pull-right" style="margin-top:4px"></span></a>
                        </li>
                    </ul>
              </li>

              <li>
                <a href="#"><i class="fa fa-bar-chart-o"></i>Reports</a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ URL::to('transactions/income-report') }}">Income Report</a>
                            <a href="{{ URL::to('transactions/expence-report') }}">Expence Report</a>
                            <a href="{{ URL::to('transactions/incomeVsExpense') }}">incomeVsExpence Report</a>
                            <a href="{{ URL::to('transactions/income-report-between-dates/generate') }}">Report Between Date</a>
                        </li>
                    </ul>
              </li>

            <li>
                <a href="#"><i class="fa fa-money"></i>Bank & Cash<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ URL::to('accounts/form/create') }}">New Account</a>
                            <a href="{{ route('accounts') }}">List Accounts<span class="label label-sales pull-right" style="margin-top:4px">{{ Cache::get('countFinances') }}</span></a>
                        </li>
                    </ul>
            </li>


            <li>
                <a href="#"><i class="fa fa-money"></i>Ledger<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ URL::to('accounts/form/create') }}">Add New Product</a>
                            <a href="{{ route('deals') }}">Ledger List<span class="label label-dependencies pull-right" style="margin-top:4px">{{ Cache::get('countDeals') }}</span></a
                        </li>
                    </ul>
            </li>

            @if($userRoleType == 2)
            <li>
                <a href="#"><i class="fa fa-user"></i>Sales/Rental/Parchase<span class="fa arrow"></span></a> 
                <ul class="nav nav-second-level">
                    <li>
                        @if(isset($invoiceId))
                        <a href="{{ route('invoice.show', ['invoiceId' => $invoiceId]) }}">View Invoice</a>
                        @endif  
                        <a href="{{ route('sales') }}">Sales<span class="label label-sales pull-right" style="margin-top:4px">{{ Cache::get('countSales') }}</span></a>
                        <a href="{{ route('rents') }}">Rental<span class="label label-sales pull-right" style="margin-top:4px">{{ Cache::get('countRents') }}</span></a>
                        <a href="{{ route('purchase') }}">Parchase<span class="label label-purchase pull-right" style="margin-top:4px">{{ Cache::get('countPurchases') }}</span></a>
                    </li>
                </ul>
            </li>    
            @endif
          @if ($userRoleType == 2)
            <ul style="margin-top: 10px; color: #dee7f1;margin-left:-30px;font-size: 14px;"></a>
                <h4>Informations <a href="{{ route('reload-info') }}"><span class="refresh-info">Refresh</span></a></h4>
                {{-- <li><i class="fa fa-money" aria-hidden="true"></i> Today income:  {{ Cache::get('todayIncome') }}</li>
                <li><i class="fa fa-money" aria-hidden="true"></i> Yesterday income: {{ Cache::get('yesterdayIncome') }}</li>
                <li><i class="fa fa-money" aria-hidden="true"></i> Cash turnover:  {{ Cache::get('cashTurnover') }}</li> --}}
                <br>
                <li><i class="fa fa-cogs" aria-hidden="true"></i> Operations: {{ Cache::get('countAllRowsInDb')  }}</li>
                <li><i class="fa fa-book" aria-hidden="true"></i> System logs: {{ Cache::get('countSystemLogs') }}</li>
            </ul>
        @endif


       
    </div>
</nav>
