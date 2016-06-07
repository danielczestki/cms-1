@if (($fix == "prefix" and isset($prefix)) or ($fix == "suffix" and isset($suffix)))
    <span class="Form__symbol Form__symbol--{{ $fix }}">{!! ($fix == "prefix" and isset($prefix)) ? $prefix : $suffix !!}</span>
@endif