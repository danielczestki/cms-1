@extends("cms::admin.layouts.default")

@section("title", $subtitle)

@section("content")
    
    <h1>{{ $title }}</h1>
    <h2>{{ $subtitle }}</h2>
    
    <hr>
    
    {{ CmsForm::open(["url" => cmsaction($controller . "@store", true)]) }}
        @foreach($fields as $name => $data)
            
            {{ CmsForm::$data["type"]($data) }}
            
        @endforeach
        
        {{ CmsForm::submit(["label" => $submitlabel]) }}
    {{ CmsForm::close() }}
    
@endsection