@if (isset($options))
    <div class="Form__row Form__radios">
        @include("cms::html.form.label")
        <div class="Form__field">
            @include("cms::html.form._error")
            @foreach ($options as $idx => $label)           
                <label class="Form__input-label">{{ Form::radio($name, $idx, @$value == $idx ? true : false, @$additional) }} {{ $label }}</label>
            @endforeach
            @include("cms::html.form._info")
        </div>
    </div>
@endif