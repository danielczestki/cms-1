<div class="Form__row">
    @include("cms::html.form.label")
    <div class="Form__field Form__field--simple-info">
        @include("cms::html.form._error")  
        
        <mediaselect v-ref:{{ $name }} :media_focus.sync="media_focus" :media_click.sync="media_click" name="{{ $name }}" label="{{ $label }}" limit="{{ isset($limit) ? $limit : 0 }}" allowed="{{ isset($allowed) ? json_encode($allowed) : null }}" existing="{{ json_encode($existing) }}"></mediaselect>
        
        
        @include("cms::html.form._info")
    </div>
</div>