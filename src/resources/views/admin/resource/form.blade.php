@extends("cms::admin.layouts.default")

@section("title", $subtitle . " - " . $title)

@section("content")
    
    <h1>{{ $title }}</h1>
    <h2>{{ $subtitle }}</h2>
    
    <a href="{{ cmsaction($controller . '@index', true, $filters) }}">Back to listing</a>
    
    <hr>
    
    {{ CmsForm::success() }}
    
    {{ CmsForm::model(["model" => @$resource, "controller" => $controller, "type" => $type, "filters" => $filters]) }}
        @foreach($fields as $name => $data)
            
            {{ CmsForm::$data["type"]($data) }}
            
        @endforeach
        
        {{ CmsForm::submit(["label" => "Save", "icon" => "check", "name" => "save"]) }}
        {{ CmsForm::submit(["label" => "Save and exit", "icon" => "bars", "name" => "saveexit"]) }}
        {{ CmsForm::cancel(["url" => cmsaction($controller . '@index', true, $filters)]) }}
    {{ CmsForm::close() }}
    
@endsection