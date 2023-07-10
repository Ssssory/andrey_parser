@extends('page')

@section('title', 'first')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">DataTable with default features</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>description</th>
                    <th>images</th>
                    <th>address</th>
                    <th>phone</th>
                    <th>url</th>  
                </tr>
            </thead>
            <tbody>
                @foreach($list as $model)
                <tr>
                    <td>{{$loop->index}}</td>
                    <td>{{$model->name}}</td>
                    <td>{{$model->description}}</td>
                    <td>
                    @foreach(explode(',',$model->images) as $image)
                        <img src="{{$image}}" width="100px">
                    @endforeach
                    </td>
                    <td>{{$model->address}}</td>
                    <td>{{$model->phone}}</td>
                    <td>{{$model->url}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Count all</th>
                    <th>{{$count}}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection