@extends('page')

@section('title', 'first')

@section('content')

<form action="">
    <div class="row">
        <div class="col-sm-6">
            <!-- select -->
            <div class="form-group">
                <label>Select</label>
                <select class="form-control" name="site">
                    <option value="">Select site</option>
                    @foreach ($select as $item)
                    <option>{{$item}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="url">url</label>
                <input type="test" class="form-control" id="url" name="url" placeholder="url without domain">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>

</form>

<div class="row">
    <div class="col-sm-6">
        <h2>Добавленные вручную ссылки</h2>
        <table class="table table-bordered table-striped">
            @foreach ($urls as $url)
                <tr>
                    <td>{{$url->id}}</td>
                    <td>{{$url->source}}</td>
                    <td>{{$url->url}}</td>
                    <td>{{$url->category}}</td>
                    <td>{{$url->status}}</td>
                    <td>{{$url->complete?'да':'нет'}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection