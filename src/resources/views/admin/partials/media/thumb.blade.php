<?php
    if ($media->type == "document") {
        $preview = CmsDocument::get($media->id);        
    } else if ($media->type == "embed") {
        $preview = CmsEmbed::url($media);       
    } else {
        $preview = null;
    }
?>
<mediathumb
    csrf="{{ csrf_token() }}"
    edit-url="{{ route('admin.media.edit', $media->id) }}"
    delete-url="{{ route('admin.media.destroy', $media->id) }}"
    focal-url="{{ route('admin.media.focal', $media->id) }}"
    preview-url="{{ $preview }}"
    icon="{{ CmsImage::getIconByType($media->type) }}"
    type="{{ $media->type }}"
>
    @if ($media->type == "image")
        <img src="{{ CmsImage::get($media->id, 600, 600) }}" class="MediaListing__image">
    @elseif ($media->type == "video")
        VIDEO
    @elseif ($media->type == "document")
        <img src="{{ asset('vendor/cms/img/dotpix.gif') }}" class="MediaListing__image">
        <div class="MediaListing__slot Utility--valign-middle"><span>
            <p class="MediaListing__filetype"><i class="fa fa-{{ CmsDocument::icon($media) }}"></i></p>
            {{ $media->title }}
            <small class="MediaListing__sub Utility--text-truncate" title="{{ $media->original_name }}">{{ $media->original_name }}</small>
        </span></div>
    @elseif ($media->type == "embed")
        <img src="{{ asset('vendor/cms/img/dotpix.gif') }}" class="MediaListing__image">
        <div class="MediaListing__slot Utility--valign-middle"><span>
            <p class="MediaListing__filetype"><i class="fa fa-youtube"></i></p>
            {{ $media->title }}
            <small class="MediaListing__sub Utility--text-truncate">{{ CmsEmbed::domain($media) }}</small>
        </span></div>
    @endif
</mediathumb>