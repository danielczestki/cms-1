@if (isset($formtype) and $formtype == "edit" and isset($infoUpdate) and $infoUpdate)
    <small class="Form__info"><i class="fa fa-info-circle"></i> {{ $infoUpdate }}</small>
@elseif (isset($info) and $info)
    <small class="Form__info"><i class="fa fa-info-circle"></i> {{ $info }}</small>
@endif