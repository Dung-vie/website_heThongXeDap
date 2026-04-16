<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>

<body>
    <header class="text-center">
        <a href= "{{ route('home') }}">
            <h1> <span class="bike">Bike</span><span class="go">Go</span> </h1>
        </a>
    </header>
    <div class="banner">
        <div class="circle" style="top: -130%; left: -30%"></div>
        <div class="circle" style="top: -200%; right: 20%"></div>
        <div class="circle" style="bottom: -200%; right: -30%"></div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <nav class="nav-blur">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('station') }}">Trạm xe</a></li>
                            @guest
                                <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                <li><a href="{{ route('register') }}">Đăng ký</a></li>
                            @endguest

                            @auth
                                <li class="text-white"> <a href="{{ route('rental.history') }}">{{ Auth::user()->name }}</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                        @csrf
                                    </form>

                                    <a href="#" onclick="document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </nav>
                </div>
                <div class="col">
                    <a href="{{ route('rental.rentForm') }}" class = "text-white text-decoration-none">
                        <div class="panel banner-left rounded">
                            <span class="text-white-50">&laquo;</span>&laquo; THUÊ XE
                            &raquo;<span class="text-white-50">&raquo;</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href={{ route('rental.returnForm') }} class = "text-white text-decoration-none">
                        <div class="panel banner-right rounded">
                            <span class="text-white-50">&laquo;</span>&laquo; TRẢ XE
                            &raquo;<span class="text-white-50">&raquo;</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <main>
        <div class="container px-4">
            {{ $slot }}
        </div>
    </main>

    <footer class="py-4 mt-4 bg-black">
        <div class="container">
            <div class="row text-white">
                <div class="col-md-4 text-center">
                    <img src="{{ asset('images/logoThanhDoanTP.png') }}" alt="Thành Đoàn TP.HCM" height="60">
                </div>
                <div class="col-md-4">
                    <h6 class="mb-1">Thành Đoàn TP.HCM</h6>
                    <p class="mb-1"><i class="bi bi-geo-alt"></i> Số 1 Phạm Ngọc Thạch, Quận 1, TP HCM</p>
                    <p class="mb-0"><i class="bi bi-envelope"></i> thanhdoan@tphcm.gov.vn</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-0">&copy; Thành Đoàn TP.HCM. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
