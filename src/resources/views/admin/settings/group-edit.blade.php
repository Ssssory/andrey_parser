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
        <input type="hidden" name="id" value="{{$group->id}}">
        <div class="row">
            <div class="col-sm-6">
                <!-- select -->
                <div class="form-group">
                    <label for="name">name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="name" value="{{$group->name}}">
                </div>
                <div class="form-group">
                    <label>group id</label>
                    <input type="text" class="form-control" id="group_id" name="group_id" placeholder="group_id" value="{{$group->group_id}}">
                </div>
                <div class="form-group">
                    <label>topic name</label>
                    <input type="text" class="form-control" id="topic_name" name="topic_name" placeholder="topic name" value="{{$group->topic_name}}">
                </div>
                <div class="form-group">
                    <label>topic</label>
                    <input type="text" class="form-control" id="topic" name="topic" placeholder="topic" value="{{$group->topic}}">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Type</label>
                    <select class="form-control" name="type">
                        @foreach ($types as $type)
                        <option
                        @if ($type->value == $group->type) selected @endif
                        >{{$type->value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Scop</label>
                    <select class="form-control" name="scop">
                        <option value="">Select scop</option>
                        @foreach ($scop as $one)
                        <option
                        @if ($one->value == $group->scop) selected @endif
                        >{{$one->value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Transport</label>
                    <select class="form-control" name="transport">
                        @foreach ($transport as $messenger)
                        <option
                        @if ($messenger->value == $group->transport) selected @endif
                        >{{$messenger->value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </form>

</div>

@endsection