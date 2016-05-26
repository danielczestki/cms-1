@if (session('success'))
    <div class="alert alert-success" style="color: green">
        {{ session('success') }}
    </div>
@endif