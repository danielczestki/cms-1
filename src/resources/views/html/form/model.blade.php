{{ Form::model(isset($model) ? $model : null, ["method" => isset($method) ? $method : "POST", "url" => $url, "files" => isset($files) ? $files : false, "class" => isset($progress) ? "Form--progress" : null]) }}
{{ Form::hidden("_name", @$_name) }}
    <div class="Box Utility--clearfix"><div class="Form">