@extends('layouts.base')

@section('caption', 'List of Transactions')

@section('title', 'List of Income')

@section('lyric', 'Vert-Age')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session()->has('message_success'))
                <div class="alert alert-success">
                    <strong>Well done!</strong> {{ session()->get('message_success') }}
                </div>
            @elseif(session()->has('message_danger'))
                <div class="alert alert-danger">
                    <strong>Danger!</strong> {{ session()->get('message_danger') }}
                </div>
            @endif
            {{-- <a href="{{ URL::to('accounts/form/create') }}">
                <button type="button" class="btn btn-primary btn active">New Accounts</button>
            </a> --}}
            <br><br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-code-fork" aria-hidden="true"></i> List of Transactions
                </div>
                <div class="panel-body">
                    <div class="table">
                        <table class="table table-bordered sys_table  table-hover" id="dataTables-example" data-sortable>
                            <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Account</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Description</th>
                                <th class="text-center" style="color: red">Dr</th>
                                <th class="text-center" style="color: green">Cr</th>
                                <th class="text-center" style="color: #0000FF;">Balance</th>
                                {{-- <th class="text-center">Income Category</th>
                                <th class="text-center">Tags</th>
                                <th class="text-center">Payer</th>
                                <th class="text-center">Payee</th>
                                <th class="text-center">Payment Method</th>
                                <th class="text-center">Refrence</th> --}}
                                <th class="text-center" style="width:100px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactionsPaginate as $key => $value)
                              {{-- @php dd($value->categoryData->name)@endphp --}}
                                <tr class="odd gradeX">
                                    <td class="text-center">{{ $value->date}}</td>
                                    <td class="text-center">
                                        @if ($value->accountData)
                                        <a href="{{ URL::to('accounts/view/' . $value->accountData->id) }}">{{ $value->accountData->account }}</a>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $value->type }}</td>
                                    <td class="text-center">₹{{$value->amount}}</td>
                                    <td class="text-center">{{ $value->description }}</td>
                                    <td class="text-center" style="color: red" >₹{{ $value->dr }}</td>
                                    <td class="text-center" style="color: green">₹{{ $value->cr }}</td>
                                    <td class="text-center" style="color: #0000FF;">₹{{ $value->bal }}</td>
                                {{-- <td class="text-center">   
                                    @if ($value->categoryData)
                                           <strong>{{ $value->categoryData->name }}</strong>
                                    @endif
                                </td>    
                                    <td class="text-center">{{ $value->tags}}</td>
                                    <td class="text-center">
                                        @if ($value->payerData)
                                       <strong> {{ $value->payerData->full_name }}</strong>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($value->payeeData)
                                       <strong> {{ $value->payeeData->name }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($value->pmethodData)
                                        <strong>{{ $value->pmethodData->name }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $value->ref }}</td> --}}
                                    <td class="text-right" style="text-align: center">
                                        <div class="btn-group">
                                            <a class="btn btn-small btn-primary"
                                               href="{{ URL::to('transactions/view/' . $value->id) }}">More Details</a>
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ URL::to('transactions/form/update/' . $value->id) }}">Manage</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Some option</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            {{-- <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>₹ {{$sumexpense}}</th>
                                    <th>₹ {{ $sumIncome}}</th> 
                                    <th>₹ {{ $latestBalance}}</th>
                                    <th></th>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                    {!! $transactionsPaginate->render() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
