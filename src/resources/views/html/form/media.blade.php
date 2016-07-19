<div class="Form__row">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")   
        
        
        <mediaselect label="{{ $label }}" existing="{{ json_encode($existing) }}">
        </mediaselect>
        
        
        @include("cms::html.form._info")
    </div>
</div>