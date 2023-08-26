@extends('page',['h1' => 'Dictionary'])

@section('title', 'Cars dictionary')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit names to ru</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>property</th>
                        <th>name -> transalate</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $model)
                    <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td>{{$model->property}}</td>
                        <td>
                            <form action="{{route('car.dictionary.property',$model->property)}}" method="post">
                                @csrf
                                <input type="text" name="name" value="{{$model->name}}">
                                <input type="text" name="value_ru" value="{{$model->value_ru}}" placeholder="translate">
                                <input type="submit" value="📦">
                            </form>
                        </td>
                        <td><a href="{{route('car.dictionary.values',$model->property)}}" target="_blank">edit values</a></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Count all</th>
                        <th>{{$list->total()}}</th>
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
    </div>
</div>


@endsection