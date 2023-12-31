@extends('layouts.base')

@section('caption', 'Vendor List')

@section('title', '')

@section('lyric', '')

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
            {{-- <a href="{{ URL::to('vendors/form/create') }}">
                <button type="button" class="btn btn-primary btn active">Add vendors</button>
            </a> --}}
            <br><br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calendar-o" aria-hidden="true"></i> List of vendors
                </div>
                <div class="panel-body">
                    <div class="table">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                            <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Country</th>
                                {{-- <th class="text-center">Employes count</th> --}}
                                {{-- <th class="text-center">Gst number</th> --}}
                                {{-- <th class="text-center">Client</th> --}}
                                {{-- <th class="text-center">Status</th> --}}
                                <th class="text-center" style="width:200px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vendorsPaginate as $key => $value)
                                <tr class="odd gradeX">
                                    <td class="text-center">{{ $value->name }}</td>
                                    <td class="text-center">{{ $value->phone }}</td>
                                    <td class="text-center">{{ $value->city }}</td>
                                    <td class="text-center">{{ $value->country }}</td>
                                    {{-- <td class="text-center">{{ $value->employees_size }}</td> --}}
                                    {{-- <td class="text-center">{{ $value->gst_number }}</td> --}}
                                    {{-- <td class="text-center">
                                        @if($value->client)
                                            <a href="{{ URL::to('clients/view/' . $value->client->id) }}">{{ $value->client->full_name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td> --}}
                                    {{-- <td class="text-center">
                                        @if($value->is_active)
                                            <label class="switch">
                                                <input type="checkbox"
                                                       onchange='window.location.assign("{{ URL::to('vendors/set-active/' . $value->id . '/0') }}")' checked>
                                                <span class="slider"></span>
                                            </label>
                                        @else
                                            <label class="switch">
                                                <input type="checkbox"
                                                       onchange='window.location.assign("{{ URL::to('vendors/set-active/' . $value->id . '/1') }}")'>
                                                <span class="slider"></span>
                                            </label>
                                        @endif
                                    </td> --}}
                                    <td class="text-right" style="text-align: center">
                                        <div class="btn-group">
                                            <a class="btn btn-small btn-primary"
                                               href="{{ URL::to('vendors/view/' . $value->id) }}">More information</a>
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ URL::to('vendors/form/update/' . $value->id) }}">Edit</a></li>
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
                    {!! $vendorsPaginate->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
