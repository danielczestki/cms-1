<?php 
    switch ($media->type) {
        case "image":
            $tinyHtml = '<img src="'. CmsImage::get($media->id, 800) .'" style="max-width:100%;" />';
        break;
        case "video":
            $tinyHtml = '<video src="'. CmsVideo::get($media->id) .'" controls preload></video>';
        break;
        case "embed":
            $tinyHtml = CmsEmbed::get($media->id);
        break;
        case "document":
            $tinyHtml = '<a href="'. CmsDocument::get($media->id) .'" target="_blank">Download '. $media->title .'</a>';
        break;
        default:
            $tinyHtml = "";
    }
?>

<mediathumb
    :parent-vue="parentVue"
    mediadata="{{ json_encode(CmsForm::mediaArray($media)) }}"
    csrf="{{ csrf_token() }}"
    edit-url="{{ $edit_url }}"
    delete-url="{{ $delete_url }}"
    focal-url="{{ $focal_url }}"
    preview-url="{{ $preview_url }}"
    tiny-html="{{ $tinyHtml }}"
    id="{{ $media->id }}"
    icon="{{ CmsImage::getIconByType($media->type) }}"
    type="{{ $media->type }}"
    :deleted="{{ $deleted }}"
    :tiny="{{ $tiny }}"
>

    @if ($media->type == "image")
        <img src="{{ CmsImage::get($media->id, 600, 600) }}" class="MediaListing__image">
    @elseif ($media->type == "video")
        <img src="{{ asset('vendor/cms/img/dotpix.gif') }}" class="MediaListing__image">
        @if ($video = CmsVideo::get($media->id))
            <div class="MediaListing__cover-thumb" style="background-image: url({{ CmsVideo::thumbnail($media->id) }})"></div>
        @else
            <div class="MediaListing__slot Utility--valign-middle"><span>
                <p class="MediaListing__filetype"><i class="fa fa-hourglass-half"></i></p>
                {{ $media->title }}
                <small class="MediaListing__sub Utility--text-truncate">Encoding...</small>
            </span></div>
        @endif
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