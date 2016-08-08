@extends("cms::admin.layouts.media")

@section("body_class", "MediaLibrary")
@section("title", "Media Library")

@section("content")
    
    {{ Form::open(["method" => "get", "url" => route("admin.media.index"), "class" => "Search"]) }}
        {{ Form::text("q", request()->get("q"), ["class" => "Search__field", "placeholder" => "Filter media..."]) }}
    {{ Form::close() }}
    
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
                        <?php
                            if ($record->type == "document") {
                                $preview = CmsDocument::get($record->id);        
                            } else if ($record->type == "embed") {
                                $preview = CmsEmbed::url($record);       
                            } else {
                                $preview = null;
                            }
                        ?>
                        @include("cms::admin.partials.media.thumb", [
                            "media" => $record,
                            "edit_url" => route('admin.media.edit', $record->id),
                            "delete_url" => route('admin.media.destroy', $record->id),
                            "focal_url" => route('admin.media.focal', $record->id),
                            "preview_url" => $preview,
                        ])
                    </li>
                @endforeach
            </ul>
        @else
            <div class="MediaNoresults Utility--valign-middle"><div>
                @if (request()->get("q"))
                    <p class="MediaNoresults__title">Sorry, nothing matched your search</p>
                    <a href="{{ route('admin.media.index') }}" class="Button Button--icon Button--small Button--green">
                        <i class="Button__icon fa fa-bars"></i>
                        All Media
                    </a>
                @else
                    <p class="MediaNoresults__title">You haven't uploaded anything yet</p>
                    <a href="{{ route('admin.media.type') }}" class="Button Button--icon Button--small Button--blue">
                        <i class="Button__icon fa fa-plus-circle"></i>
                        Upload Media
                    </a>
                @endif
            </div></div>
        @endif
        
    </main>
    
@endsection