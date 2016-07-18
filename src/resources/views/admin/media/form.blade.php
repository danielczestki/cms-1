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
            @if ($formtype == "create")
                <a href="{{ route('admin.media.type') }}" class="Button Button--icon Button--small Button--grey">
                    <i class="Button__icon fa fa-chevron-circle-left"></i>
                    Choose another type
                </a>
            @endif
        </div>
    </div>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Form -->
    <main class="MediaMain MediaMain--pad">
        {{ CmsForm::model(["model" => @$resource, "controller" => $controller, "type" => $formtype, "filters" => [], "files" => true, "progress" => true]) }}
            {{ CmsForm::hidden(["name" => "type", "value" => $mediakey]) }}
            
            {{ CmsForm::text([
                "name" => "title",
                "label" => "Title",
                "required" => true,
                "maxlength" => 100,
                "info" => $mediakey == 'image' ? "Also serves as the image alt attribute" : null
            ]) }}
            @if ($formtype == "edit") 
                {{ CmsForm::content([
                    "label" => "Current media",
                    "value" => $preview
                ]) }}
            @endif
            @if (in_array($mediakey, ["image", "video", "document"]))
                {{ CmsForm::file([
                    "name" => "file",
                    "label" => "Upload " . strtolower($mediatype["label"]),
                    "required" => true,
                    "mediatype" => $mediakey
                ]) }}
            @endif
            @if (in_array($mediakey, ["embed"]))
                {{ CmsForm::textarea([
                    "name" => "embed_code",
                    "label" => "Embed code",
                    "required" => true,
                    "maxlength" => 2000,
                    "placeholder" => '<iframe width="560" height="315" src="https://www.youtube.com/embed/6UQijb6ETIA"></iframe>',
                    "info" => "Embed HTML code from source",
                    "value" => $formtype == "edit" ? $resource->embed->embed_code : null
                ]) }}
            @endif
            
            @if ($formtype == "edit") 
                {{ CmsForm::buttons([
                    "save_label" => $mediakey == "image" ? "Next Step" : "Finish",
                    "save_icon" => "arrow-right",
                    "hide_save_exit" => $mediakey != "image" ? true : false,
                    "save_exit_label" => "Set Focal",
                    "save_exit_icon" => "crosshairs",
                    "save_exit_link" => route("admin.media.focal", $resource->id),
                ]) }}
            @else
                {{ CmsForm::buttons([
                    "save_label" => $mediakey == "image" ? "Next Step" : "Finish",
                    "save_icon" => "arrow-right",
                    "hide_save_exit" => true
                ]) }}
            @endif
        {{ CmsForm::close() }}
    </main>
    
@endsection