@if (session('error'))
    <div class="alert alert-error" style="color: red">
        {{ session('error') }}
    </div>
@endif