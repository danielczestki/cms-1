<div class="Form__row {{ isset($options) ? 'Form__row--top' : null }}">
    @include("cms::html.form.label")
    <div class="Form__field">
        @include("cms::html.form._error")
        
        
        @if (isset($options))
            <div class="Form__checkboxes">
                @foreach ($options as $idx => $label)           
                    <label class="Form__input-label Utility--text-truncate">{{ Form::checkbox("{$name}[]", $idx, @in_array($idx, is_array($value) ? $value : [$value]) ? true : false, @$additional) }} {{ $label }}</label>
                @endforeach
            </div>
        @else
            <label class="Form__input-label">{{ Form::checkbox($name, 1, @$value === true ? true : false, @$additional) }} {{ $label }}</label>
        @endif
        
        @include("cms::html.form._info")
    </div>
</div>