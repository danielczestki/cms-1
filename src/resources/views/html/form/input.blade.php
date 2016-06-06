<div class="Form__row">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")        
        @if ($type == "password")
            {{ Form::password($name, @$additional) }}
        @else
            {{ Form::$type($name, @$value, @$additional) }}    
        @endif
        @include("cms::html.form._info")
    </div>
</div>