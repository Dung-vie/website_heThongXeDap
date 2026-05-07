<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng nhập</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-dark">
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-12 col-md-6 col-lg-4">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <h2 class="text-muted">Đăng nhập</h2>
                        </div>

                        <form action="/login" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="credential" class="form-label fw-semibold">Email hoặc số điện thoại</label>
                                <input type="text" name="credential" id="credential" value="{{ old('credential') }}"
                                    class="form-control form-control-lg @error('credential') is-invalid @enderror"
                                    required>
                                @error('credential')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-4">
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

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Đăng nhập
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <span>Chưa có tài khoản? <a href="/register" class="text-decoration-none">Đăng ký
                                        ngay</a></span>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
