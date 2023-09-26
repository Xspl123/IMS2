@extends('layouts.base')

@section('caption', 'List of Accounts')

@section('title', 'List of Accounts')

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
                    <i class="fa fa-code-fork" aria-hidden="true"></i> List of accounts
                </div>
                <div class="panel-body">
                    <div class="table">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                            <thead>
                            <tr>
                                <th class="text-center">Account</th>
                                <th class="text-center">Account Number</th>
                                <th class="text-center">Bank Name</th>
                                <th class="text-center">Initial Balance</th>
                                <th class="text-center">Contact Person</th>
                                <th class="text-center">Contact Number</th>
                                <th class="text-center">Description</th>
                                <th class="text-center" style="width:200px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accountsPaginate as $key => $value)
                                <tr class="odd gradeX">
                                    <td class="text-center">{{ $value->account }}</td>
                                    <td class="text-center">{{ $value->account_number }}</td>
                                    <td class="text-center">{{ $value->bank_name }}</td>
                                    <td class="text-center">₹ {{ $value->balance }}</td>
                                    <td class="text-center">{{ $value->contact_person }}</td>
                                    <td class="text-center">{{ $value->contact_phone }}</td>
                                    <td class="text-center">{{ $value->description }}</td>
                                    <td class="text-right" style="text-align: center">
                                        <div class="btn-group">
                                            <a class="btn btn-small btn-primary"
                                               href="{{ URL::to('accounts/view/' . $value->id) }}">More information</a>
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ URL::to('accounts/form/update/' . $value->id) }}">Edit</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Some option</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $accountsPaginate->render() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
