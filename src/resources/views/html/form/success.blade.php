@if (session("success"))
    <alert type="success">{{ session("success") }}</alert>
@endif