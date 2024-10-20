@if($model['total'] != 0)
<div class="row">
    <h2>{{ ucfirst($model['name']) }}</h2>
    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $model['urls'] }}</h3>
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
                <h3>{{ $model['total'] }}</h3>
                <p>Количество полученных сырых данных</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>

    <div class="col-lg-2 col-4">

        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $model['messages']['day'] }}</h3>
                <p>Количество сообщений отправленныйх за день</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>

    <div class="col-lg-2 col-4">

        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $model['messages']['week'] }}</h3>
                <p>Количество сообщений отправленныйх за неделю</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>

    <div class="col-lg-2 col-4">

        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $model['messages']['month'] }}</h3>
                <p>Количество сообщений отправленныйх за месяц</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
</div>
@endif