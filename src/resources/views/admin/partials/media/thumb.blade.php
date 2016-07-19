<mediathumb
    csrf="{{ csrf_token() }}"
    edit-url="{{ $edit_url }}"
    delete-url="{{ $delete_url }}"
    focal-url="{{ $focal_url }}"
    preview-url="{{ $preview_url }}"
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