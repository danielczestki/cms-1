@extends("cms::admin.layouts.media")

@inject("media", "Thinmartian\Cms\App\Services\Media\Media")

@section("body_class", "MediaLibrary")
@section("title", "Media Library")

@section("content")
    
    <!-- Title -->
    <div class="Title Title--sub Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">Media Library</h1>
            <h2 class="h2">Currently uploaded media</h2>
        </div>
        <div class="Title__buttons">
            <a href="{{ route('admin.media.type') }}" class="Button Button--icon Button--small Button--blue">
                <i class="Button__icon fa fa-plus-circle"></i>
                Upload Media
            </a>
        </div>
    </div>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Content -->
    <main class="MediaMain">
        @if ($listing->count()) 
             <ul class="MediaListing">
                @foreach ($listing as $record)
                    <li class="MediaListing__item">
                        <mediathumb csrf="{{ csrf_token() }}" delete-url="{{ route('admin.media.destroy', $record->id) }}" type="{{ $record->type }}">
                            @if ($record->type == "image")
                                <img src="{{ CmsImage::get($record->id, 600, 600) }}" class="MediaListing__image">
                            @elseif ($record->type == "video")
                                VIDEO
                            @elseif ($record->type == "document")
                                DOCUMENT
                            @elseif ($record->type == "embed")
                                EMBED
                            @endif
                        </mediathumb>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="MediaNoresults Utility--valign-middle"><div>
                <p class="MediaNoresults__title">You haven't uploaded anything yet</p>
                <a href="{{ route('admin.media.create') }}" class="Button Button--icon Button--small Button--blue">
                    <i class="Button__icon fa fa-plus-circle"></i>
                    Upload Media
                </a>
            </div></div>
        @endif
        
    </main>
    
@endsection