<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body>
<div class="d-flex" style="min-height:100vh">

    {{-- Sidebar --}}
    <div class="bg-dark text-white p-3" style="width:220px; flex-shrink:0">
        <h5 class="text-warning mb-4">Admin</h5>
        <ul class="nav flex-column gap-1">
            <li><a href="/admin/bikes"    class="nav-link text-white {{ request()->is('admin/bikes*')    ? 'bg-secondary rounded' : '' }}">
                <i class="bi bi-bicycle"></i> Quản lý xe</a></li>
            <li><a href="/admin/stations" class="nav-link text-white {{ request()->is('admin/stations*') ? 'bg-secondary rounded' : '' }}">
                <i class="bi bi-pin-map"></i> Quản lý trạm</a></li>
        </ul>
        <hr class="border-secondary">
        <form action="/admin/logout" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-danger w-100">Đăng xuất</button>
        </form>
    </div>

    {{-- Content --}}
    <div class="flex-grow-1 p-4 bg-light">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{ $slots }}
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
