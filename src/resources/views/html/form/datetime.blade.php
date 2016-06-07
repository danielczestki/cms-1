<div class="Form__row {{ isset($prefix) ? 'Form--prefixed' : null }} {{ isset($suffix) ? 'Form--suffixed' : null }}">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")
        @include("cms::html.form._symbol", ["fix" => "prefix"])       
        @include("cms::html.form._symbol", ["fix" => "suffix"])   
        {{ Form::text($name, @$value, @$additional) }}    
        @include("cms::html.form._info")
    </div>
</div>