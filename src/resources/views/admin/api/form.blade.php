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
    {{ CmsForm::model(["model" => @$resource, "controller" => $controller, "type" => $type, "filters" => $filters, "cmsAppAction" => false]) }}
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

{{-- dupe of buttons.blade.php but with 'cmsaction' set to false --}}
</div></div><div class="Box Utility--clearfix">
    <div class="Form__buttons">
        @if (! isset($hide_save))
            {{ CmsForm::submit(["label" => isset($save_label) ? $save_label : "Save", "icon" => isset($save_icon) ? $save_icon : "check", "name" => "save", "color" => "blue"]) }}
        @endif
        @if (! isset($hide_save_exit))
            @if (isset($save_exit_link))
                <a href="{{ $save_exit_link }}" class="Button Button--small Button--green Button--icon">
                    <i class="Button__icon fa fa-{{ $save_exit_icon }}"></i>
                    {{ $save_exit_label }}
                </a>
            @else
                {{ CmsForm::submit(["label" => isset($save_exit_label) ? $save_exit_label : "Save and exit", "icon" => isset($save_exit_icon) ? $save_exit_icon : "bars", "name" => "saveexit", "color" => "green"]) }}
            @endif
        @endif
    </div>
    @if (! isset($hide_cancel))
        <div class="Form__cancel">
            {{ CmsForm::cancel(["url" => cmsaction($controller . '@index', false, $filters)]) }}
        </div>
    @endif
</div>
{{-- end buttons.blade.php --}}

    {{ CmsForm::close() }}
    
@endsection