<div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">{!! $label !!}{{ $required ? "*" : null }}</label>
    {{ Form::$type($name, @$value, @$additional) }}
    @if ($errors->has($name))
        {{ $errors->first($name) }}
    @endif
</div>