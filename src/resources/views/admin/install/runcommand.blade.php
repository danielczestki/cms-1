@extends("cms::admin.layouts.install")

@section("content")
    <h2>Let's install Thin Martian CMS</h2>
    <div class="Box">
        <p>If you haven't already, setup your Laravel database config.</p>
        <p>Open up a terminal and <code>cd</code> to the root of your Laravel app and enter.</p>
        <p><code>php artisan cms:build</code></p>
        <p>Follow the instructions and refresh this page.</p>
    </div>
@endsection