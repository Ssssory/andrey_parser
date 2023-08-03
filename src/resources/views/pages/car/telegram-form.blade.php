@extends('page')

@section('title', 'Send to telegramm')

@section('content')

<form action="/car/form/{{$model->id}}/send" method="POST">
    @csrf
    <div class="row">
        <div class="col-sm-6">
            <!-- select -->
            <div class="form-group">
                <label>url</label>
                <input type="input" class="form-control" id="url" name="url" placeholder="url" value="{{$model->url}}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>description</label>
                <textarea type="textarea" rows="7" class="form-control" id="description" name="description">{{$model->description}}</textarea>
            </div>
        </div>
    </div>


    <hr><br>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="id">id</label>
                <input type="input" class="form-control" id="id" name="id" value="{{$message->id}}">
            </div>
            <div class="form-group  has-warning">
                <label for="tags">tags</label>
                <input type="input" class="form-control" id="tags" name="tags" value="{{$message->getTags()}}">
                <span class="help-block">без # и каждый тег через пробел</span>
            </div>
            <div class="form-group">
                <label for="name">name</label>
                <input type="input" class="form-control" id="name" name="name" value="{{$message->name}}">
            </div>
            <div class="form-group">
                <label for="model">model</label>
                <input type="input" class="form-control" id="model" name="model" value="{{$message->model}}">
            </div>
            <div class="form-group">
                <label for="year">year</label>
                <input type="input" class="form-control" id="year" name="year" value="{{$message->year}}">
            </div>
            <div class="form-group">
                <label for="mileage">mileage</label>
                <input type="input" class="form-control" id="mileage" name="mileage" value="{{$message->mileage}}">
            </div>
            <div class="form-group">
                <label for="engineType">engineType</label>
                <input type="input" class="form-control" id="engineType" name="engineType" value="{{$message->engineType}}">
            </div>
            <div class="form-group">
                <label for="engineVolume">engineVolume</label>
                <input type="input" class="form-control" id="engineVolume" name="engineVolume" value="{{$message->engineVolume}}">
            </div>
            <div class="form-group">
                <label for="transmission">transmission</label>
                <input type="input" class="form-control" id="transmission" name="transmission" value="{{$message->transmission}}">
            </div>


            <div class="form-group">
                <label for="price">price</label>
                <input type="input" class="form-control" id="price" name="price" value="{{$message->price}}">
            </div>



        </div>
        <div class="col-sm-6">
            <div class="form-group">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            @foreach ($message->getImages() as $image)
            <img style="display: inline-block" src="{{$image}}" width="400">
            @endForeach
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Send to telegramm</button>
            </div>
        </div>
    </div>

</form>

@endsection