<div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">{!! $label !!}</label>
    {!! Form::$type($name, null) !!}
    @if ($errors->has($name))
        {{ $errors->first($name) }}
    @endif
</div>