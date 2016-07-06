@extends("cms::admin.layouts.media")

@section("body_class", "MediaLibrary")
@section("title", "Media Library")

@section("content")
    
    <!-- Title -->
    <div class="Title Title--sub Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">Media Library</h1>
            <h2 class="h2"><i class="fa fa-photo"></i> Set the focal point of the photo</h2>
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
        {{ Form::model(@$resource, ["route" => ["admin.media.focusing", $cms_medium_id]]) }}
            <div class="Box Utility--clearfix"><div class="Form">
                <mediafocus image="{{ $resource->image->aspect == "portrait" ? CmsImage::get($resource->id, null, 1024, true) : CmsImage::get($resource->id, 1024, null, true) }}" section="{{ $resource->image->focal }}"></mediafocus>
            {{ CmsForm::buttons([
                "save_label" => "Finish",
                "hide_save_exit" => true
            ]) }}
        {{ Form::close() }}
    </main>
    
@endsection