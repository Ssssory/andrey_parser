@extends('page',['h1' => 'Rent Table'])

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
                    <th>price</th>
                    <th>address</th>
                    <th>url</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $model)
                <tr>
                    <td>{{$loop->index}}</td>
                    <td>{{$model->name}}</td>
                    <td>{{$model->description}}</td>
                    <td>{{$model->price}}</td>
                    <td>{{$model->address}}</td>
                    <td>{{$model->url}}</td>
                    <td><a href="/rent/form/{{$model->id}}" target="_blank" rel="noopener noreferrer">✍️✉️</a></td>
                </tr>
                <tr>
                    <td colspan="7">
                        @foreach(explode(',',$model->images) as $image)
                        <img src="{{$image}}" width="200px">
                        @endforeach
                    </td>
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
                    <th colspan="2">
                        @if ($list->currentPage() > 1)
                            <a href="{{$list->previousPageUrl()}}">prev</a>
                        @endif
                        {{$list->currentPage()}} of {{$list->lastPage()}}
                        @if ($list->hasMorePages())
                            <a href="{{$list->nextPageUrl()}}">next</a>
                        @endif
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection