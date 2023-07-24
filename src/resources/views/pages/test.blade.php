@extends('page')

@section('title', 'Url')

@section('content')

<form action="" method="POST">
    @csrf
    <div class="row">
        <div class="col-sm-6">
            <!-- select -->
            <div class="form-group">
                <label>url</label>
                <input type="test" class="form-control" id="url" name="url" placeholder="url" value="{{old('url')}}">
            </div>
           
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>

</form>

@endsection