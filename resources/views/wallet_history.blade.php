@extends('layouts.base')

@section('title', '')

@section('caption', 'Welcome in Vert-Age ERP')

@section('content')

<div class="row">
    <div class="col-md-12">
        @if (session()->has('message_success'))
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
                <i class="fa fa-code-fork" aria-hidden="true"></i> List of Expenses
            </div>
            <div class="panel-body">
                <div class="table">
                    <table class="table table-bordered sys_table table-hover" id="dataTables-example" data-sortable>
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($walletHistories as $key => $value)
                                <tr class="odd gradeX">
                                    <td class="text-center">{{ date('d F Y - h:i A', strtotime($value->created_at)) }}</td>
                                    <td class="text-center">{{ $value->admin_name}}</td>
                                    <td class="text-center">{{ $value->amount }}</td> 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
