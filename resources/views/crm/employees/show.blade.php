@extends('layouts.base')

@section('caption', 'Information about employees')

@section('title', 'Information about employees')

@section('lyric', '')

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
            <div class="panel panel-default">
                <div class="panel-heading">
                    More information about: {{ $employee->full_name }}
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Basic information</a>
                        </li>
                        <li class=""><a href="#profile" data-toggle="tab">Tasks <span
                                        class="badge badge-warning">{{ $employee->taskCount }}</span></a>
                        </li>
                        <div class="text-right">
                            <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">
                                Delete this employee <li class="fa fa-trash-o"></li>
                            </button>
                        </div>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="home">
                            <h4></h4>

                            <table class="table table-striped table-bordered">
                                <tbody class="text-right">
                                <tr>
                                    <th>Full name</th>
                                    <td>{{ $employee->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $employee->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Email address</th>
                                    <td>{{ $employee->email }}</td>
                                </tr>
                                <tr>
                                    <th>Job</th>
                                    <td>{{ $employee->job }}</td>
                                </tr>
                                <tr>
                                    <th>Note</th>
                                    <td>{{ $employee->note }}</td>
                                </tr>
                                <tr>
                                    <th>Assigned client</th>
                                    <td>
                                        @if($employee->client)
                                            <a href="{{ URL::to('clients/view/' . $employee->client->id) }}">{{ $employee->client->full_name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $employee->is_active ? 'Active' : 'Deactivate' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile">
                            <br>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                </tr>
                                @foreach($employee->tasks as $task)
                                    <tbody>
                                    <tr class="odd gradeX">
                                        <td>{{ $task->name }}</td>
                                        <td>
                                            {{ Form::open(['url' => 'tasks/view/' . $task->id,'class' => 'pull-right']) }}
                                            {{ Form::hidden('_method', 'GET') }}
                                            {{ Form::submit('More information about this task', ['class' => 'btn btn-primary btn-sm']) }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
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
                    <h4 class="modal-title" id="myModalLabel">You want to delete this employee?</h4>
                </div>
                <div class="modal-body">
                    Action will permanently delete this employee.
                </div>
                <div class="modal-footer">
                    {{ Form::open(['url' => 'employees/delete/' . $employee->id,'class' => 'pull-right']) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete this employee', ['class' => 'btn btn-small btn-danger']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
