<div class="Box">
    {{ Form::open(["method" => isset($method) ? $method : "POST", "url" => $url]) }}
    {{ Form::hidden("_name", @$_name) }}