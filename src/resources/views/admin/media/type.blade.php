@extends("cms::admin.layouts.media")

@inject("media", "Thinmartian\Cms\App\Services\Media\Media")

@section("body_class", "MediaLibrary")
@section("title", "Media Library")

@section("content")
    
    <!-- Title -->
    <div class="Title Title--sub Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">Media Library</h1>
            <h2 class="h2">What media are you uploading</h2>
        </div>
        <div class="Title__buttons">
            <a href="{{ route('admin.media.index') }}" class="Button Button--icon Button--small Button--grey">
                <i class="Button__icon fa fa-chevron-circle-left"></i>
                Back to library
            </a>
        </div>
    </div>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Form -->
    <main class="MediaMain MediaMain--pad">
        <div class="Box"><div class="Form Utility--text-center">
            <ul class="MediaTypes">
                @foreach ($media->getMediaTypes() as $medianame => $mediatype)
                    @if ($mediatype["enabled"] and in_array($medianame, $allowed))
                        <li class="MediaTypes__type"><a href="{{ route('admin.media.create', ['type' => $medianame]) }}" class="MediaTypes__link">
                            <i class="fa fa-{{ $mediatype['icon'] }}"></i>
                            <span class="MediaTypes__title">{{ $mediatype["label"] }}</span>
                        </a></li>
                    @endif
                @endforeach
            </ul>
            
        </div></div>
    </main>
    
@endsection