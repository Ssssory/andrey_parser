@extends('page',['h1' => 'Poslovna category'])

@section('title', 'Poslovna category')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Poslovna category table for parsing</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>title</th>
                    <th>link</th>
                    <th></th>

                </tr>
            </thead>
            <tbody>
                @foreach($list as $model)
                <tr>
                    <td>{{$model['catigory']}}</td>
                    <td>{{$model['title']}}</td>
                    <td>{{$model['link']}}</td>
                    <td></td>

                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Count all</th>
                    <th>{{count($list)}}</th>
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