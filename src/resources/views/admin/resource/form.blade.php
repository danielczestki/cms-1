@extends("cms::admin.layouts.default")

@section("body_class", "Resource")
@section("title", $subtitle . " - " . $title)

@section("content")
    
    <!-- Title -->
    <div class="Title Title--sub Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">{{ $title }}</h1>
            <h2 class="h2">{{ $subtitle }}</h2>
        </div>
        <div class="Title__buttons">
            <a href="{{ cmsaction($controller . '@index', true, $filters) }}" class="Button Button--icon Button--small Button--grey">
                <i class="Button__icon fa fa-chevron-circle-left"></i>
                Back to listing
            </a>
        </div>
    </div>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Form -->
    {{ CmsForm::model(["model" => @$resource, "controller" => $controller, "type" => $type, "filters" => $filters]) }}
        @foreach($fields as $name => $data)
            <?php $_field = $data["type"]; ?>
            {{ CmsForm::$_field($data, @$resource) }}
        @endforeach
        {{ CmsForm::buttons() }}
    {{ CmsForm::close() }}

    <!-- Article History -->
    @if (@$resource && $resource->versions && count($resource->versions))
        <div class="Box Utility--clearfix">
            <h1>Revisions</h1>
            <ul>
                @foreach ($resource->versions as $version)
                    @if (count($resource->currentVersion()->diff($version)))
                        <li>
                            <a href="{!! cmsaction($controller . '@edit', true, array_merge(['id' => $resource->id, 'version_id' => $version->version_id], $filters)) !!}">
                                {!! $version->created_at->format('l jS \\of F Y h:i:s A') !!}
                            </a>
                             - 
                            @foreach ($resource->currentVersion()->diff($version) as $key=>$item)
                                @if (is_string($item))
                                    {!! $key . ': ' . $item !!}
                                @endif
                            @endforeach
                        </li>
                    @endif
                @endforeach
        </div>
    @endif
    
@endsection