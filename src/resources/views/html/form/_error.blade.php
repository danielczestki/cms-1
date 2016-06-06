@if ($errors->has($name))
    <span class="Form__error"><i class="fa fa-exclamation-circle"></i> {{ $errors->first($name) }}</span>
@endif