<button type="submit" class="Button Button--small Button--{{ $color or "grey" }} {{ isset($icon) ? 'Button--icon' : null }}" name="{{ @$name }}">
    @if (isset($icon))
        <i class="Button__icon fa fa-{{ $icon }}"></i>
    @endif
    {!! $label !!}
</button>