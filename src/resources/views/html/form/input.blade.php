<div class="Form__row {{ isset($prefix) ? 'Form--prefixed' : null }} {{ isset($suffix) ? 'Form--suffixed' : null }}">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")   
        @include("cms::html.form._symbol", ["fix" => "prefix"])       
        @include("cms::html.form._symbol", ["fix" => "suffix"])     
        @if ($type == "password")
            {{ Form::$type($name, @$additional) }}
        @elseif ($type == "file") 
            <fileupload name="{{ $name }}" mediatype="{{ @$mediatype }}"></fileupload>
        @else
            {{ Form::$type($name, @$value, @$additional) }}    
        @endif
        @include("cms::html.form._info")
    </div>
</div>