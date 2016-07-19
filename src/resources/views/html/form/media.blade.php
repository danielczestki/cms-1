<div class="Form__row">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")     
            @if ($existing->count())
                
            @endif
            
            <button name="save" class="Button Button--small Button--orange Button--icon" type="submit">
                <i class="Button__icon fa fa-photo"></i>
                {{ $label }}
            </button>
        @include("cms::html.form._info")
    </div>
</div>