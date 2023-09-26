@extends('layouts.base')

@section('caption', 'View Transactions' )

@section('title', 'View Transactions')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-6">
            @if(session()->has('message_success'))
                <div class="alert alert-success">
                    <strong>Well done!</strong> {{ session()->get('message_success') }}
                </div>
            @elseif(session()->has('message_danger'))
                <div class="alert alert-danger">
                    <strong>Danger!</strong> {{ session()->get('message_danger') }}
                </div>
            @endif
            <br/>
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Basic information</a>
                        </li>
                        <div class="text-right">
                            <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">
                                Delete this finance <li class="fa fa-trash-o"></li>
                            </button>
                        </div>

                    </ul>
                    <div class="tab-pane fade active in" id="home">
                        <h4></h4>

                        <table class="table table-striped table-bordered">
                            <tbody class="text-right">
                            <tr>
                                <th>Account</th>
                                <td> {{ $transaction->accountData->account }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ $transaction->date }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $transaction->description }}</td>
                            </tr>
                            <tr>
                                <th>Dr</th>
                                <td>₹{{ $transaction->dr }}</td>
                            </tr>
                            <tr>
                                <th>Cr</th>
                                <td>₹{{ $transaction->cr }}</td>
                            </tr>
                            <tr>
                                <th>Bal</th>
                                <td>₹{{ $transaction->bal }}</td>
                            </tr>
                            <tr>
                                <th>Income Category</th>
                                <td> @if ($transaction->categoryData)
                                    <strong>{{ $transaction->categoryData->name }}</strong>
                                    @endif</td>
                            </tr>
                            <tr>
                                <th>Tags</th>
                                <td>{{$transaction->tags }}</td>
                            </tr>
                            <tr>
                                <th>Payer</th>
                                <td >
                                    @if ($transaction->payerData)
                                   <strong> {{ $transaction->payerData->full_name }}</strong>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Payee</th>
                                <td >
                                    @if ($transaction->payeeData)
                                   <strong> {{ $transaction->payeeData->name }}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Method</th>
                                <td>{{ $transaction->pmethodData->name }}</td>
                            </tr>
                            <tr>
                                <th>Refrence</th>
                                <td>{{ $transaction->ref }}</td>
                            </tr>
                            
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">You want delete this transaction?</h4>
                </div>
                <div class="modal-body">
                    Ation will delete permanently this transaction.
                </div>
                <div class="modal-footer">
                    {{ Form::open(['url' => 'transactions/delete/' . $transaction->id,'class' => 'pull-right']) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete this transaction', ['class' => 'btn btn-small btn-danger']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
