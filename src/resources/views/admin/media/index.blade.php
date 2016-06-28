@extends("cms::admin.layouts.media")

@section("body_class", "MediaLibrary")
@section("title", "Media Library")

@section("content")
    
    <!-- Title -->
    <div class="Title Title--sub Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">Media Library</h1>
            <h2 class="h2">Currently uploaded media</h2>
        </div>
        <div class="Title__buttons">
            <a href="{{ route('admin.media.create') }}" class="Button Button--icon Button--small Button--blue">
                <i class="Button__icon fa fa-plus-circle"></i>
                Upload Media
            </a>
        </div>
    </div>
    
    <!-- Content -->
    <main class="MediaMain">
         <div class="MediaListing">
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/1.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/2.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/3.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/4.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/5.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/6.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/7.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/8.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/5.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/6.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/3.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/2.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/8.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/6.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/1.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/8.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/3.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/2.jpg') }}" class="MediaListing__image"></a>
            <a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/3.jpg') }}" class="MediaListing__image"></a>
        </div>
        
        <!-- <div class="MediaNoresults Utility--valign-middle"><div>
            <p class="MediaNoresults__title">You haven't uploaded anything yet</p>
            <a href="{{ route('admin.media.create') }}" class="Button Button--icon Button--small Button--blue">
                <i class="Button__icon fa fa-plus-circle"></i>
                Upload Media
            </a>
        </div></div> -->
        
    </main>
    
@endsection