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
            <a href="{{ cmsaction($controller . '@index', false, $filters) }}" class="Button Button--icon Button--small Button--grey">
                <i class="Button__icon fa fa-chevron-circle-left"></i>
                Back to listing
            </a>
        </div>
    </div>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Form -->
    {{ CmsForm::model(["model" => $resource, "controller" => $controller, "type" => $type, "filters" => $filters, "cmsAppAction" => false]) }}
        @foreach($fields as $name => $data)
            @if ($controller == "UsersController" and $data["name"] == "permissions")
                @if (Auth::guard("cms")->user()->access_level == "Admin")
                    {{-- Special permissions drop for users --}}
                    {{ CmsForm::access_level([], @$resource) }}
                    {{ CmsForm::permissions($data, @$resource) }}
                @endif
            @else
                <?php $_field = $data["type"]; ?>
                {{ CmsForm::$_field($data, @$resource) }}
            @endif
        @endforeach
        {{--{{ CmsForm::buttons() }}--}}
    {{ CmsForm::close() }}
    
@endsection