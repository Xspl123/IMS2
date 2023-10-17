@extends('layouts.base')

@section('caption', 'View Transactions')

@section('title', 'View Transactions')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-6">
            @if (session()->has('message_success'))
                <div class="alert alert-success">
                    <strong>Well done!</strong> {{ session()->get('message_success') }}
                </div>
            @elseif(session()->has('message_danger'))
                <div class="alert alert-danger">
                    <strong>Danger!</strong> {{ session()->get('message_danger') }}
                </div>
            @endif
            <br />
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
                                    <th>Date</th>
                                    <td>{{ $daybook->date }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $daybook->description }}</td>
                                </tr>
                                <tr>
                                    <th>Expences</th>
                                    <td>₹{{ $daybook->dr }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>
                                        @if ($daybook->categoryData)
                                            <strong>{{ $daybook->categoryData->name }}</strong>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payer</th>
                                    <td>
                                        {{ $daybook->payer }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Payee</th>
                                    <td>
                                        {{ $daybook->payee }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td>{{ $daybook->pmethodData->name }}</td>
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
                    <h4 class="modal-title" id="myModalLabel">You want delete this daybook?</h4>
                </div>
                <div class="modal-body">
                    Ation will delete permanently this daybook.
                </div>
                <div class="modal-footer">
                    {{ Form::open(['url' => 'daybooks/delete/' . $daybook->id, 'class' => 'pull-right']) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete this daybook', ['class' => 'btn btn-small btn-danger']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
