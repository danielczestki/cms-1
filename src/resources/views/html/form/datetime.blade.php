<div class="Form__row">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")        
        {{ Form::text($name, @$value, @$additional) }}    
        @include("cms::html.form._info")
    </div>
</div>