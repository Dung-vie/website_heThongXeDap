<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng ký</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-dark">
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <h2 class="text-muted">Tạo tài khoản mới</h2>
                        </div>

                        <form action="/register" method="post">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Họ tên</label>
                                <input type="text" value="{{ old('name') }}"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    name="name" id="name" required />
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                                <input type="number" value="{{ old('phone') }}"
                                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                    name="phone" id="phone" required />
                            </div>
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" value="{{ old('email') }}"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"name="email"
                                    id="email" placeholder="abc@gmail.com" required />
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                                    <input type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" id="password" required />
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="password_confirmation" class="form-label fw-semibold">Nhắc lại mật
                                        khẩu</label>
                                    <input type="password" class="form-control form-control-lg"
                                        name="password_confirmation" id="password_confirmation"
                                        class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                        required />
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Đăng ký
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <span>Đã có tài khoản? <a href="/login" class="text-decoration-none">Đăng nhập tại
                                        đây</a></span>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
