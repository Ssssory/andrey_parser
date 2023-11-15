@foreach ($typeModel as $name => $model)
<div class="row">
    <h2>{{ $name }}</h2>
    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">
            <div class="inner">
                <h3></h3>
                <p>Количество ссылок</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $model }}</h3>
                <p>Количество полученных сырых данных</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
</div>
@endforeach