@extends('page',['h1' => 'Send to telegramm'])

@section('title', 'Send to telegramm')

@section('content')

<form action="/rent/form/{{$model->id}}/send" method="POST">
    @csrf
    <div class="row">
        <div class="col-sm-6">
            <!-- select -->
            <div class="input-group input-group-sm">
                <input type="input" class="form-control" id="url" name="url" placeholder="url" value="{{$model->url}}">
                <span class="input-group-append">
                    <button type="button" onckick="window.open('{{$model->url}}', '_blank');" class="btn btn-info btn-flat">🔍</button>
                </span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>description</label>
                <textarea type="textarea" rows="7" class="form-control" id="description" name="description">{{$model->description}}</textarea>
            </div>
        </div>
    </div>





    <div class="row">
        @foreach ($model->dirtyStateParametersData as $key => $item)
        <div class="col-sm-3">
            <div class="form-group">
                <label for="{{$key}}">{{$item->property}}</label>
                <input type="input" class="form-control" id="{{$key}}" name="{{$key}}" value="{{$item->value}}">
            </div>
        </div>
        @endforeach
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
                <label for="type">rent/sell</label>
                <input type="input" class="form-control" id="type" name="type" value="{{$message->getType()}}">
            </div>
            <div class="form-group">
                <label for="price">price</label>
                <input type="input" class="form-control" id="price" name="price" value="{{$message->price}}">
            </div>
            <div class="form-group">
                <label for="square">square</label>
                <input type="input" class="form-control" id="square" name="square" value="{{$message->square}}">
            </div>
            <div class="form-group">
                <label for="rooms">rooms</label>
                <input type="input" class="form-control" id="rooms" name="rooms" value="{{$message->rooms}}">
            </div>
            <div class="form-group">
                <label for="location">location</label>
                <input type="input" class="form-control" id="location" name="location" value="{{$message->location}}">
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
