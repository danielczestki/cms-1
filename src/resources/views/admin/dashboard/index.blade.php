@extends("cms::admin.layouts.default")

@section("title", "Dashboard")

@section("content")
    
    {{ var_dump(config("auth")) }}
    
@endsection