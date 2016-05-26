<div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">{!! $label !!}{{ $required ? "*" : null }}</label>
    @if ($type == "password")
        {{ Form::password($name, @$additional) }}
    @else
        {{ Form::$type($name, @$value, @$additional) }}    
    @endif
    @if ($errors->has($name))
        {{ $errors->first($name) }}
    @endif
</div>