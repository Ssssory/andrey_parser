@extends('page',['h1' => 'Manage bots'])

@section('title', 'manage bots')

@section('content')
@if (session()->has('success'))
<div class="alert alert-sucess" role="alert">{{session()->get('success')}}</div>
@endif
@if (session()->has('errors'))
<div class="alert alert-danger" role="alert">{{$errors}}</div>
@endif
<div class="card-body">
    <form action="{{route('admin.settings.bots.save')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <!-- select -->
                <div class="form-group">
                    <label for="name">name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="name" value="{{old('name')}}">
                </div>
                <div class="form-group">
                    <label>token</label>
                    <input type="text" class="form-control" id="token" name="token" placeholder="token" value="{{old('token')}}">
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
@if(!$bots->isEmpty())
<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>type</th>
                <th>active</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bots as $bot)
            <tr>
                <td>{{$bot->id}}</td>
                <td>{{$bot->name}}</td>
                <td>{{$bot->type}}</td>
                <td>@if($bot->is_active)✅@else❌@endif</td>
                <td>
                    <a href="{{route('admin.settings.bot.active',['bot'=> $bot->id])}}" class="btn btn-primary">
                        @if($bot->is_active) Disable @else Enable @endif
                    </a>
                    <a href="{{route('admin.settings.bot.delete',['bot'=> $bot->id])}}" class="btn btn-danger">Delete</a>
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
            </tr>
        </tfoot>
    </table>
</div>
@endif




@endsection