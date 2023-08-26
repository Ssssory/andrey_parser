@extends('page',['h1' => 'Telegram keys'])

@section('title', 'telegram keys')

@section('content')

<form action="" method="post">
    @csrf
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="json">keys</label>
                <textarea class="form-control" name="json" id="json" cols="30" rows="10" placeholder='{"botname":{"token":"xxx","groups":[{"name":"xxx","id":"-xxx"},{"name":"yyy","id":"-yyy"}]}}'>{{old('json', $json)}}</textarea>
                <span>
                    <pre>{
	"botname": {
		"token": "xxx",
		"groups": {
			"groupName": {
				"type": "group/channel/forum",
				"id": "-xxxxxxxxxxxxx",      
				"topics": [{
					"name": "BMW",
					"id": 4
				}, {
					"name": "Audi",
					"id": 3
				}]
			}
		}
	}
}</pre>
                </span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>

</form>

@endsection