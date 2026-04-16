<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>

    <div class="container mt-5">
        <h2>Đăng kí</h2>
        <div class="row justify-content-center align-items-center g-2">
            <form action="/register" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Họ tên</label>
                    <input type="text" class="form-control" name="name" id="name"  />
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="number" class="form-control" name="phone" id="phone" />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="abc@mail.com" />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" name="password" id="password"  />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nhắc lại mật khẩu</label>
                    <input type="password" class="form-control" name="password" id="password"  />
                </div>
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Đăng ký
                </button>


            </form>
        </div>

    </div>


</body>

</html>
