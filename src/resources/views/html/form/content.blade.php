<div class="Form__row Form__row--top {{ isset($prefix) ? 'Form--prefixed' : null }} {{ isset($suffix) ? 'Form--suffixed' : null }}">
    @include("cms::html.form.label")
    <div class="Form__field">   
        {!! $value !!}
        @include("cms::html.form._info")
    </div>
</div>