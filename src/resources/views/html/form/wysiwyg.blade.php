<div class="Form__row Form__row--top">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")   
        {{-- WYSIWYG COMING SOON ONCE I DECIDE WHAT ONE TO USE --}}
        <p style="margin: 0; font-size: 10px;color: #999">WYSIWYG coming soon</p>
        {{ Form::textarea($name, @$value, @$additional) }}  
        @include("cms::html.form._info")
    </div>
</div>