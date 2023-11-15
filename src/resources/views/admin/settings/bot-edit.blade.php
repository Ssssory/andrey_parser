@extends('page',['h1' => 'Manage Bot'])

@section('title', 'manage bot')

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
        <input type="hidden" name="id" value="{{$bot->id}}">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="name" value="{{$bot->name}}">
                </div>
                <div class="form-group">
                    <label>token</label>
                    <input type="text" class="form-control" id="token" name="token" placeholder="token" value="{{$bot->token}}">
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
                        <option @if ($type->value == $bot->type) selected @endif
                            >{{$type->value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Scop</label>
                    <select class="form-control" name="scop">
                        <option value="">Select scop</option>
                        @foreach ($scop as $one)
                        <option @if ($one->value == $bot->scop) selected @endif
                            >{{$one->value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Transport</label>
                    <select class="form-control" name="transport">
                        @foreach ($transport as $messenger)
                        <option @if ($messenger->value == $bot->transport) selected @endif
                            >{{$messenger->value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </form>

</div>

@endsection