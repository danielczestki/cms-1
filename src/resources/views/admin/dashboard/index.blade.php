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
        Quick links to appear here... at some point.        
    </div>
        
@endsection