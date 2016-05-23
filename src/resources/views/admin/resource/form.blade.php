@extends("cms::admin.layouts.default")

@section("title", $subtitle)

@section("content")
    
    <h1>{{ $title }}</h1>
    <h2>{{ $subtitle }}</h2>
    
    <hr>
    
    @foreach($fields as $name => $data)
        
        {{ CmsForm::$data["type"]($data) }}
        
    @endforeach
    
@endsection