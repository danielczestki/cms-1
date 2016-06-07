<div class="Form__row Form__row--top">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")
        {{ Form::textarea($name, @$value, @$additional) }}    
        @include("cms::html.form._info")
    </div>
</div>