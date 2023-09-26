@extends('layouts.base')

@section('caption', 'Expense Tracker')

@section('title', 'Expense Tracker')

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
                                <th class="text-center">Category</th>
                                <th class="text-center">Description</th>
                                <th class="text-center" style="color: red">Expense</th>
                                <th class="text-center" style="width:100px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($daybooksPaginate as $key => $value)
                              {{-- @php dd($value->categoryData->name)@endphp --}}
                                <tr class="odd gradeX">
                                    <td class="text-center">{{ $value->date}}</td>
                                    <td> @if ($value->categoryData)
                                        {{ $value->categoryData->name }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $value->description }}</td>
                                    <td class="text-center" style="color: red" >₹{{ $value->dr }}</td>
                                
                                    <td class="text-right" style="text-align: center">
                                        <div class="btn-group">
                                            <a class="btn btn-small btn-primary"
                                               href="{{ URL::to('daybooks/view/' . $value->id) }}">More Details</a>
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ URL::to('daybooks/form/update/' . $value->id) }}">Manage</a></li>
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
                    {!! $daybooksPaginate->render() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
