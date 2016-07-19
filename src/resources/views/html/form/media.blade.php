<div class="Form__row">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")   
        
        
        <mediaselect v-ref:{{ $name }} :media_click.sync="media_click" name="{{ $name }}" label="{{ $label }}" existing="{{ json_encode($existing) }}"></mediaselect>
        
        
        @include("cms::html.form._info")
    </div>
</div>