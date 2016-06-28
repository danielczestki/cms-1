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
        {{ CmsForm::open(["method" => "GET", "url" => route("admin.media.create")]) }}
            kjhkjh
            {{ CmsForm::buttons([
                "save_label" => "Next",
                "save_icon" => "arrow-right",
                "hide_save_exit" => true
            ]) }}
        {{ CmsForm::close() }}
    </main>
    
@endsection