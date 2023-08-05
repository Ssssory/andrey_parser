@extends('page'['h1' => 'Organization Table'])

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
                    <th>source</th>
                    <!--th>hash</th-->
                    <th>name</th>
                    <th>industry</th>
                    <th>address</th>
                    <th>email</th>
                    <th>city</th>
                    <th>delatnost</th>
                    <th>phone</th>
                    <th>vlasnik</th>
                    <th>zastupnik</th>
                    <th>category</th>
                    <th>subcategory</th>
                    <th>site</th>
                    <th>adscheck</th>
                    <th>rate</th>
                    <th>employees</th>
                    <th>country</th>
                    <th>url</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $model)
                <tr>
                    <td>{{$loop->index}}</td>
                    <td>{{$model->source}}</td>
                    <!--td>{{$model->hash}}</td-->
                    <td>{{$model->name}}</td>
                    <td>{{$model->industry}}</td>
                    <td>{{$model->address}}</td>
                    <td>{{$model->email}}</td>
                    <td>{{$model->city}}</td>
                    <td>{{$model->delatnost}}</td>
                    <td>{{$model->phone}}</td>
                    <td>{{$model->vlasnik}}</td>
                    <td>{{$model->zastupnik}}</td>
                    <td>{{$model->category}}</td>
                    <td>{{$model->subcategory}}</td>
                    <td>{{$model->site}}</td>
                    <td>{{$model->adscheck}}</td>
                    <td>{{$model->rate}}</td>
                    <td>{{$model->employees}}</td>
                    <td>{{$model->country}}</td>
                    <td>{{$model->url}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Count all</th>
                    <th>{{$count}}</th>
                    <!--th></th-->
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
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