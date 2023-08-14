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
                        <th>value</th>
                        <th>ru</th>
                        <th>en</th>
                        <th>rs</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $model)
                    <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td>{{$model->value}}</td>
                        <td>
                            <form action="{{route('car.dictionary.values.save',$property->name)}}" method="post">
                                @csrf
                                <input type="hidden" name="original_value" value="{{$model->value}}">
                                <input type="text" name="value_ru" value="{{$model->value_ru}}" placeholder="translate ru">
                                <input type="submit" value="📦">
                            </form>
                        </td>
                        <td>
                            <form action="{{route('car.dictionary.values.save',$property->name)}}" method="post">
                                @csrf
                                <input type="hidden" name="original_value" value="{{$model->value}}">
                                <input type="text" name="value_en" value="{{$model->value_en}}" placeholder="translate en">
                                <input type="submit" value="📦">
                            </form>
                        </td>
                        <td>
                            <form action="{{route('car.dictionary.values.save',$property->name)}}" method="post">
                                @csrf
                                <input type="hidden" name="original_value" value="{{$model->value}}">
                                <input type="text" name="value_rs" value="{{$model->value_rs}}" placeholder="translate rs">
                                <input type="submit" value="📦">
                            </form>
                        </td>
                        <td><button>link</button></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Count all</th>
                        <th>{{$list->total()}}</th>
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
    </div>
</div>


@endsection