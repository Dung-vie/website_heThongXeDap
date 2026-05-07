# BikeGo - Hệ thống thuê xe đạp thông minh

## Giới thiệu

Website hỗ trợ người dùng thuê xe đạp trực tuyến, giúp quản lý xe, trạm xe và lịch sử thuê một cách thuận tiện.  
Người dùng có thể đăng ký tài khoản, đăng nhập, thuê xe, xem lịch sử thuê xe và đánh giá trạm xe.  
Hệ thống cũng cung cấp trang quản trị dành cho admin để quản lý dữ liệu xe và trạm.

---

# Mô tả

Là hệ thống thuê xe đạp trực tuyến được xây dựng bằng Laravel theo mô hình MVC.  
Website hỗ trợ:

- Quản lý xe đạp
- Quản lý trạm xe
- Đăng ký / đăng nhập tài khoản
- Xem lịch sử thuê xe
- Xếp hạng top biker theo tháng
- Giao diện responsive bằng Bootstrap

---

# Tính năng chính

## Người dùng

- Đăng ký tài khoản
- Đăng nhập / đăng xuất
- Validate dữ liệu realtime bằng JavaScript
- Thuê xe đạp
- Xem lịch sử thuê xe
- Xem danh sách trạm xe
- Xem top biker tháng
- Đánh giá trạm xe

---

## Quản trị viên (Admin)

- Đăng nhập admin
- Quản lý xe đạp (CRUD)
- Quản lý trạm xe (CRUD)
- Phân trang dữ liệu
- Kiểm soát quyền truy cập admin bằng middleware

---

# Công nghệ sử dụng


- Front-end: HTML, CSS, JavaScript
- Backend: Laravel 11
- Framework: Bootstrap 5
- Database: Mysql

---

# Cách chạy project

## 1. Clone project

```bash
git clone đồ án
```

---

## 2. Di chuyển vào thư mục project

```bash
cd bikego
```

---

## 3. Cài package

```bash
composer install
```

---

## 4. Cài node modules

```bash
npm install
```

---

## 5. Tạo file môi trường

```bash
cp .env.example .env
```

---

## 6. Tạo app key

```bash
php artisan key:generate
```

---

## 7. Cấu hình database trong `.env`

```env
DB_DATABASE=bikego
DB_USERNAME=root
DB_PASSWORD=
```

---

## 8. Chạy migration

```bash
php artisan migrate
```

---

## 9. Chạy Vite

```bash
npm run dev
```

---

## 10. Chạy server Laravel

```bash
php artisan serve
```

# Giao diện

- Responsive trên mobile và desktop
- Sử dụng Bootstrap 5
- UI hiện đại và tối giản

---

