@extends("cms::admin.layouts.default")

@section("title", "Dashboard")

@section("content")
    
    <p>DASHBOARD!! - <a href="#" v-on:click.prevent="media_click">Open media</a></p>
    
    
    {{ CmsImage::get(1, 300, 300) }}
    
@endsection