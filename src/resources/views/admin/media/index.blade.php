@extends("cms::admin.layouts.media")

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
                        @include("cms::admin.partials.media.thumb", ["media" => $record])
                    </li>
                @endforeach
            </ul>
        @else
            <div class="MediaNoresults Utility--valign-middle"><div>
                <p class="MediaNoresults__title">You haven't uploaded anything yet</p>
                <a href="{{ route('admin.media.type') }}" class="Button Button--icon Button--small Button--blue">
                    <i class="Button__icon fa fa-plus-circle"></i>
                    Upload Media
                </a>
            </div></div>
        @endif
        
    </main>
    
@endsection