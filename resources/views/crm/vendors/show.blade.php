@extends('layouts.base')

@section('caption', 'Information about Vendors')

@section('title', 'Information about Vendors')

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
                    More information about {{ $vendor->name }}
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Basic information</a>
                        </li>
                        <div class="text-right">
                            <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">
                                Delete this vendor <li class="fa fa-trash-o"></li>
                            </button>
                        </div>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="home">
                            <h4></h4>

                            <table class="table table-striped table-bordered">
                                <tbody class="text-right">
                                <tr>
                                    <th>Vendor Name</th>
                                    <td>{{ $vendor->name }}</td>
                                </tr>
                                <tr>
                                    <th>Gst number</th>
                                    <td>{{ $vendor->gst_number }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $vendor->phone }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $vendor->city }}</td>
                                </tr>
                                <tr>
                                    <th>Billing Address</th>
                                    <td>{{ $vendor->billing_address }}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $vendor->country }}</td>
                                </tr>
                                <tr>
                                    <th>Postal code</th>
                                    <td>{{ $vendor->postal_code }}</td>
                                </tr>
                                {{-- <tr>
                                    <th>Employee size</th>
                                    <td>{{ $vendor->employees_size }}</td>
                                </tr> --}}
                                {{-- <tr>
                                    <th>Assigned client</th>
                                    <td>
                                        @if($vendor->client)
                                            <a href="{{ URL::to('clients/view/' . $vendor->client->id) }}">{{ $vendor->client->full_name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>Fax</th>
                                    <td>{{ $vendor->fax }}</td>
                                </tr>
                                <tr height="100px">
                                    <th>Description</th>
                                    <td>{{ $vendor->description }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $vendor->is_active ? 'Active' : 'Deactivate' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile">
                            <h4>Lorem ipsum</h4>
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
                    <h4 class="modal-title" id="myModalLabel">You want delete this vendor?</h4>
                </div>
                <div class="modal-body">
                    Action will delete permanently this vendor.
                </div>
                <div class="modal-footer">
                    {{ Form::open(['url' => 'vendors/delete/' . $vendor->id,'class' => 'pull-right']) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete this vendor', ['class' => 'btn btn-small btn-danger']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
