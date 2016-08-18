@extends("cms::admin.layouts.default")

@section("title", "Dashboard")

@section("content")
    
    <!-- Title -->
    <div class="Title Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">Dashboard</h1>
        </div>
    </div>
    
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <div class="Box">
         <ul class="Quicklinks">
            @foreach (CmsYaml::getNav() as $idx => $nav)
                 <li class="Quicklinks__item"><a href="{{ $nav['url'] }}" class="Quicklinks__link Utility--valign-middle"><span>
                     <i class="fa fa-{{ $nav['icon'] }}"></i>
                     <h2 class="Quicklinks__title">{{ $nav["title"] }}</h2>
                 </span></a></li>
             @endforeach
         </ul>     
    </div>
        
@endsection