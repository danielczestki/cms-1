@extends("cms::admin.layouts.media")

@section("body_class", "MediaLibrary")
@section("title", "Media Library")

@section("content")
    
    <!-- Title -->
    <div class="Title Title--sub Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">Media Library</h1>
            <h2 class="h2">{!! $subtitle !!}</h2>
        </div>
        <div class="Title__buttons">
            <a href="{{ route('admin.media.type') }}" class="Button Button--icon Button--small Button--grey">
                <i class="Button__icon fa fa-chevron-circle-left"></i>
                Choose another type
            </a>
        </div>
    </div>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Form -->
    <main class="MediaMain MediaMain--pad">
        {{ CmsForm::model(["model" => @$resource, "controller" => $controller, "type" => $formtype, "filters" => [], "files" => true]) }}
            {{ CmsForm::hidden(["name" => "type", "value" => request()->get("type")]) }}
            
            {{ CmsForm::text([
                "name" => "title",
                "label" => "Title",
                "required" => true,
                "maxlength" => 100,
                "info" => request()->get("type") == 'image' ? "Also serves as the image alt attribute" : null
            ]) }}
            {{ CmsForm::file([
                "name" => "file",
                "label" => "Upload " . strtolower($mediatype["label"]),
                "required" => true,
                "mediatype" => request()->get("type")
            ]) }}
            
            
            {{ CmsForm::buttons([
                "save_label" => "Next Step",
                "save_icon" => "arrow-right",
                "hide_save_exit" => true
            ]) }}
        {{ CmsForm::close() }}
    </main>
    
@endsection