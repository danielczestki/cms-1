<button type="submit" class="btn btn-primary">
    @if (isset($icon))
        <i class="fa fa-btn fa-{{ $icon }}"></i>
    @endif
    {!! $label !!}
</button>