@extends("cms::admin.layouts.install")

@section("content")
    <h2>Administrator account missing!</h2>
    <div class="Box">
        <p>You don't have an admin user to login with.</p>
        <p>Open up a terminal and <code>cd</code> to the root of your Laravel app and enter.</p>
        <p><code>php artisan cms:build</code></p>
        <p>Follow the instructions and refresh this page.</p>
    </div>
@endsection