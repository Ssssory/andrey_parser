@extends('page',['h1' => 'Manage groups'])

@section('title', 'manage groups')

@section('content')

@if (session()->has('success'))
<div class="alert alert-sucess" role="alert">{{session()->get('success')}}</div>
@endif
@if (session()->has('errors'))
<div class="alert alert-danger" role="alert">{{$errors}}</div>
@endif
<div class="card-body">
    <form action="{{route('admin.settings.groups.save')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <!-- select -->
                <div class="form-group">
                    <label for="name">name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="name" value="{{old('name')}}">
                </div>
                <div class="form-group">
                    <label>group id</label>
                    <input type="text" class="form-control" id="group_id" name="group_id" placeholder="group_id" value="{{old('group_id')}}">
                </div>
                <div class="form-group">
                    <label>topic name</label>
                    <input type="text" class="form-control" id="topic_name" name="topic_name" placeholder="topic name" value="{{old('topic_name')}}">
                </div>
                <div class="form-group">
                    <label>topic</label>
                    <input type="text" class="form-control" id="topic" name="topic" placeholder="topic" value="{{old('topic')}}">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Type</label>
                    <select class="form-control" name="type">
                        <option value="">Select type</option>
                        @foreach ($types as $type)
                        <option>{{$type->value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Scop</label>
                    <select class="form-control" name="type">
                        <option value="">Select scop</option>
                        @foreach ($scop as $one)
                        <option>{{$one->value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Transport</label>
                    <select class="form-control" name="transport">
                        <option value="">Select messenger</option>
                        @foreach ($transport as $messenger)
                        <option>{{$messenger->value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </form>

</div>
@if(!$groups->isEmpty())
<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>type</th>
                <th>scop</th>
                <th>topic name</th>
                <th>topic id</th>
                <th>active</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
            <tr>
                <td>{{$group->id}}</td>
                <td>{{$group->name}}</td>
                <td>{{$group->type}}</td>
                <td>{{$group->scop}}</td>
                <td>{{$group->topic_name}}</td>
                <td>{{$group->topic}}</td>
                <td>@if($group->is_active)✅@else❌@endif</td>
                <td>
                    <a href="{{route('admin.settings.group.active',['group'=> $group->id])}}" class="btn btn-primary">
                        @if($group->is_active) Disable @else Enable @endif
                    </a>
                    <a href="{{route('admin.settings.group.delete',['group'=> $group->id])}}" class="btn btn-danger">Delete</a>
                    <a href="{{route('admin.settings.group.edit',['group'=> $group->id])}}" class="btn btn-success">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@endsection