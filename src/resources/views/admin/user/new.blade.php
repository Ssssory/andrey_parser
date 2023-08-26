@extends('page',['h1' => 'New user'])

@section('title', 'New User')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-sucess" role="alert">{{$success}}</div>
    @endif
    @if (session()->has('errors'))
        <div class="alert alert-danger" role="alert">{{$errors}}</div>
    @endif
    <form action="" method="POST">
    @csrf
        <div class="row">
            <div class="col-sm-6">
                <!-- select -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="test" class="form-control" id="email" name="email" placeholder="email" value="{{old("email")}}">
                </div>
                <div class="form-group">
                    <label for="name">name</label>
                    <input type="test" class="form-control" id="name" name="name" placeholder="name" value="{{old("name")}}">
                </div>
                <div class="form-group">
                    <label for="password">password</label>
                    <input type="test" class="form-control" id="password" name="password" placeholder="password">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>

    </form>

@endsection