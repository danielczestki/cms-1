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
            <a href="{{ route('admin.media.type') }}" class="Button Button--icon Button--small Button--blue">
                <i class="Button__icon fa fa-plus-circle"></i>
                Upload Media
            </a>
        </div>
    </div>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Content -->
    <main class="MediaMain">
         <ul class="MediaListing">
            <li class="MediaListing__item"><a href=""><i class="MediaListing__icon fa fa-photo" title="Image"></i><img src="{{ asset('vendor/cms/img/dummy/thumbs/1.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><i class="MediaListing__icon fa fa-film" title="Video"></i><img src="{{ asset('vendor/cms/img/dummy/thumbs/2.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><i class="MediaListing__icon fa fa-youtube" title="Embed"></i><img src="{{ asset('vendor/cms/img/dummy/thumbs/3.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/4.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/5.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/6.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/7.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/8.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/5.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/6.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/3.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/2.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/8.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/6.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/1.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/8.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/3.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/2.jpg') }}" class="MediaListing__image"></a></li>
            <li class="MediaListing__item"><a href=""><img src="{{ asset('vendor/cms/img/dummy/thumbs/3.jpg') }}" class="MediaListing__image"></a></li>
        </ul>
        
        <!-- <div class="MediaNoresults Utility--valign-middle"><div>
            <p class="MediaNoresults__title">You haven't uploaded anything yet</p>
            <a href="{{ route('admin.media.create') }}" class="Button Button--icon Button--small Button--blue">
                <i class="Button__icon fa fa-plus-circle"></i>
                Upload Media
            </a>
        </div></div> -->
        
    </main>
    
@endsection