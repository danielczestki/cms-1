@extends("cms::admin.layouts.media")

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
                <li class="MediaTypes__type"><a href="{{ route('admin.media.create', ['type' => 'image']) }}" class="MediaTypes__link">
                    <i class="fa fa-photo"></i>
                    <span class="MediaTypes__title">Image</span>
                </a></li>
                <li class="MediaTypes__type"><a href="{{ route('admin.media.create', ['type' => 'video']) }}" class="MediaTypes__link">
                    <i class="fa fa-film"></i>
                    <span class="MediaTypes__title">Video</span>
                </a></li>
                <li class="MediaTypes__type"><a href="{{ route('admin.media.create', ['type' => 'document']) }}" class="MediaTypes__link">
                    <i class="fa fa-file"></i>
                    <span class="MediaTypes__title">Document</span>
                </a></li>
                <li class="MediaTypes__type"><a href="{{ route('admin.media.create', ['type' => 'embed']) }}" class="MediaTypes__link">
                    <i class="fa fa-youtube"></i>
                    <span class="MediaTypes__title">Embed</span>
                </a></li>
            </ul>
            
        {{ CmsForm::buttons([
            "save_label" => "Next",
            "save_icon" => "arrow-right",
            "hide_save_exit" => true
        ]) }}
    </main>
    
@endsection