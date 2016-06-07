<div class="Form__row Form__radios">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")              
        <label class="Form__input-label">{{ Form::radio($name, 1, @$value === true ? true : false, @$additional) }} Yes</label>
        <label class="Form__input-label">{{ Form::radio($name, 0, @$value === false ? true : false, @$additional) }} No</label>
        @include("cms::html.form._info")
    </div>
</div>