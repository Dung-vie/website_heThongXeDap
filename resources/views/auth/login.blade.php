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
        <div class="row justify-content-center align-items-center g-2">
            <form action="/login" method="post">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email hoặc số điện thoại</label>
                    <input type="text" name="credential" class="form-control" >
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" />
                </div>
                <button type="submit" class="btn btn-primary w-auto">
                    Đăng nhập
                </button>
            </form>
        </div>

    </div>


</body>

</html>
