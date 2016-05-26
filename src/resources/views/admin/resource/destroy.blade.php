@extends("cms::admin.layouts.default")

@section("title", $subtitle)

@section("content")
    
    <h1>{{ $title }}</h1>
    <h2>{{ $subtitle }}</h2>
    
    <a href="{{ cmsaction($controller . '@index', true, $filters) }}">Back to listing</a>
    
    <hr>
    
    {{ CmsForm::success() }}
    
    {{ CmsForm::open(["method" => "DELETE", "url" => url()->current() . "?" . http_build_query($filters)]) }}
        {{ Form::hidden("_confirmed", 1) }}
        @foreach (request()->get("ids") as $id)
            {{ Form::hidden("ids[]", $id) }}
        @endforeach
        <p>You are about to delete {{ count(request()->get("ids")) }} {{ strtolower(str_plural(str_singular($_name), count(request()->get("ids")))) }}</p>
        
        
        {{ CmsForm::submit(["label" => "Delete forever", "icon" => "trash"]) }}
        {{ CmsForm::cancel(["url" => cmsaction($controller . '@index', true, $filters)]) }}
        
    {{ CmsForm::close() }}
    
@endsection