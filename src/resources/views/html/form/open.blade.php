{{ Form::open(["method" => isset($method) ? $method : "POST", "url" => $url, "files" => isset($files) ? $files : false]) }}
    {{ Form::hidden("_name", @$_name) }}
        <div class="Box"><div class="Form">