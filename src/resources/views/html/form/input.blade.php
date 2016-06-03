<div class="Form__row{{ $errors->has($name) ? ' Form__row--error' : '' }}">
    <label for="" class="Form__label">{!! $label !!}{!! $required ? "<span class=\"Form__required\">*</span>" : null !!}</label>
    <div class="Form__field">
        @if ($errors->has($name))
            <span class="Form__error"><i class="fa fa-exclamation-circle"></i> {{ $errors->first($name) }}</span>
        @endif
        @if ($type == "password")
            {{ Form::password($name, @$additional) }}
        @else
            {{ Form::$type($name, @$value, @$additional) }}    
        @endif
        @if (isset($formtype) and $formtype == "edit" and isset($infoUpdate) and $infoUpdate)
            <small class="Form__info"><i class="fa fa-info-circle"></i> {{ $infoUpdate }}</small>
        @elseif (isset($info) and $info)
            <small class="Form__info"><i class="fa fa-info-circle"></i> {{ $info }}</small>
        @endif
    </div>
</div>