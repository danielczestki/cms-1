{{ Form::model(isset($model) ? $model : null, ["method" => isset($method) ? $method : "POST", "url" => $url]) }}
{{ Form::hidden("_name", @$_name) }}