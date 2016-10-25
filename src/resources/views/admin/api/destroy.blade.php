@extends("cms::admin.layouts.default")

@section("title", $subtitle . " - " . $title)

@section("content")
    
    <!-- Title -->
    <div class="Title Title--sub Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">{{ $title }}</h1>
            <h2 class="h2">{{ $subtitle }}</h2>
        </div>
        <div class="Title__buttons">
            {{ CmsForm::cancel(["url" => cmsaction($controller . '@index', false, $filters)]) }}
        </div>
    </div>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Alert -->
    {{ Form::open(["method" => "DELETE", "url" => url()->current() . "?" . http_build_query($filters)]) }}
        {{ Form::hidden("_confirmed", 1) }}
        @foreach (request()->get("ids") as $id)
            {{ Form::hidden("ids[]", $id) }}
        @endforeach
        <div class="Box Form__destroy">
            <p>You are about to delete <strong>{{ count(request()->get("ids")) }}</strong> {{ strtolower(str_plural('key', count(request()->get("ids")))) }}</p>
            {{ CmsForm::submit(["label" => "Delete forever", "icon" => "trash", "color" => "red"]) }}         
        </div>
    {{ Form::close() }}
    
    
    <?php /* ?>

    <h1>{{ $title }}</h1>
    <h2>{{ $subtitle }}</h2>

    <a href="{{ cmsaction($controller . '@index', false, $filters) }}">Back to listing</a>

    <hr>

    {{ CmsForm::success() }}

    {{ CmsForm::open(["method" => "DELETE", "url" => url()->current() . "?" . http_build_query($filters)]) }}
        {{ Form::hidden("_confirmed", 1) }}
        @foreach (request()->get("ids") as $id)
            {{ Form::hidden("ids[]", $id) }}
        @endforeach
        <p>You are about to delete {{ count(request()->get("ids")) }} {{ strtolower(str_plural(str_singular($_name), count(request()->get("ids")))) }}</p>


        {{ CmsForm::submit(["label" => "Delete forever", "icon" => "trash"]) }}
        {{ CmsForm::cancel(["url" => cmsaction($controller . '@index', false, $filters)]) }}

    {{ CmsForm::close() }}

    <?php */ ?>
    
@endsection