# 📱 PhoneZone – Hệ thống bán lẻ điện thoại chính hãng

> Hệ thống thương mại điện tử chuyên bán điện thoại chính hãng, xây dựng trên nền tảng **PHP 8.2 MVC thuần** (không dùng framework), MySQL và Apache.

---

## 🌐 Demo

| | Link |
|---|---|
| 🛍️ **Website** | http://phonezone.rf.gd |
| ⚙️ **Admin Panel** | http://phonezone.rf.gd/admin/dang-nhap |

### Tài khoản thử nghiệm

**Admin:**
```
Email:    admin@phonezone.vn
Password: Admin@123
```

**Khách hàng:**
```
Email:    hoa123@gmail.com
Password: hoa123@
```

---

## ✨ Tính năng

### Phía khách hàng
- 🏠 **Trang chủ** – Hero banner, Flash Sale, sản phẩm nổi bật, bán chạy, thương hiệu
- 🔍 **Tìm kiếm & Lọc** – Theo hãng, giá, đánh giá sao, sắp xếp đa chiều
- 📱 **Chi tiết sản phẩm** – Gallery ảnh, chọn biến thể, tabs mô tả/thông số/đánh giá
- ⚖️ **So sánh** – So sánh tối đa 3 sản phẩm cùng lúc
- 🛒 **Giỏ hàng** – Cập nhật số lượng AJAX, mã giảm giá với thông báo lỗi cụ thể
- 💳 **Thanh toán** – COD, MoMo QR, Chuyển khoản ngân hàng, VNPay
- 👤 **Tài khoản** – Đăng ký/đăng nhập, upload avatar, cập nhật thông tin
- ❤️ **Yêu thích** – Thêm/xóa sản phẩm yêu thích
- 📦 **Đơn hàng** – Lịch sử đơn, theo dõi trạng thái real-time

### Phía Admin
- 📊 **Dashboard** – KPI doanh thu, biểu đồ Chart.js, đơn hàng mới
- 📱 **Sản phẩm** – CRUD đầy đủ, upload ảnh/URL, biến thể (màu/dung lượng/giá/kho)
- 📋 **Đơn hàng** – Xem chi tiết, cập nhật trạng thái, lịch sử
- 🗂️ **Danh mục** – Thêm/sửa/xóa qua modal
- 👥 **Khách hàng** – Danh sách, chi tiết, lịch sử mua hàng
- ⭐ **Đánh giá** – Duyệt/xóa theo tab trạng thái
- 🎫 **Mã giảm giá** – Tạo mã, random code, quản lý hạn dùng
- 📈 **Báo cáo** – Doanh thu theo tháng, top SP, theo hãng, phương thức TT
- ⚙️ **Cài đặt** – Thông tin cửa hàng, vận chuyển, thanh toán, mạng xã hội

---

## 🛠️ Công nghệ sử dụng

| Thành phần | Chi tiết |
|---|---|
| **Backend** | PHP 8.2 – MVC tự xây dựng (không dùng framework) |
| **Database** | MySQL 8.0 – PDO Prepared Statements |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Charts** | Chart.js |
| **Icons** | Font Awesome 6.5 |
| **Fonts** | Inter – Google Fonts |
| **Hosting** | InfinityFree – Apache + PHP + MySQL |

---

## 📁 Cấu trúc thư mục

```
phonezone/
├── index.php                    # Entry point
├── routes.php                   # Định nghĩa URL routes
├── .htaccess                    # Clean URL (mod_rewrite)
├── .env                         # Biến môi trường
├── config/
│   ├── app.php                  # Cấu hình ứng dụng
│   └── database.php             # Cấu hình database
├── core/                        # Engine MVC
│   ├── Router.php
│   ├── Controller.php
│   ├── Model.php
│   ├── Database.php
│   ├── Session.php
│   ├── Env.php
│   └── Helpers.php
├── app/
│   ├── controllers/             # Controllers khách hàng
│   ├── controllers/Admin/       # Controllers admin
│   ├── models/                  # Models
│   └── views/                   # Views (layouts, pages)
├── public/
│   ├── css/                     # Stylesheets
│   ├── js/                      # JavaScript
│   └── uploads/                 # Ảnh upload
└── database/
    └── phonezone.sql            # Schema + dữ liệu mẫu
```

---

## 🚀 Cài đặt local

### Yêu cầu
- XAMPP 8.2+ (Apache + MySQL + PHP 8.2)
- Trình duyệt Chrome / Firefox mới nhất

### Các bước

**1. Clone repository**
```bash
git clone https://github.com/hoaf304/phonezone.git
cd phonezone
```

**2. Copy vào XAMPP**
```
C:\xampp\htdocs\phonezone\
```

**3. Tạo database**
- Mở phpMyAdmin: http://localhost/phpmyadmin
- Tạo database tên `phonezone`
- Import file `database/phonezone.sql`

**4. Cấu hình môi trường**

Tạo file `.env` (copy từ `.env.example`):
```env
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/phonezone
APP_NAME=PhoneZone

DB_HOST=localhost
DB_PORT=3306
DB_NAME=phonezone
DB_USER=root
DB_PASS=

SESSION_NAME=phonezone_session
SESSION_LIFETIME=7200

UPLOAD_MAX_SIZE=5242880
UPLOAD_PATH=public/uploads

SECRET_KEY=your_secret_key_here
```

**5. Truy cập**
```
Website: http://localhost/phonezone
Admin:   http://localhost/phonezone/admin/dang-nhap
```

---

## 📸 Giao diện

| Trang chủ | Danh sách sản phẩm |
|---|---|
| Hero banner, Flash Sale, Brands | Lọc sidebar, Sort bar, Product grid |

| Chi tiết sản phẩm | Giỏ hàng & Thanh toán |
|---|---|
| Gallery, Biến thể, Tabs | Mã giảm giá, MoMo QR, Địa chỉ |

| Admin Dashboard | Admin Sản phẩm |
|---|---|
| KPI + Chart.js | CRUD + Upload ảnh |

---

## 📄 Tài liệu

- 📘 [Hướng dẫn sử dụng (Word)](docs/phonezone_huong_dan.docx)

---

## 👨‍💻 Tác giả

**hoaf304** – Sinh viên  
🔗 GitHub: [@hoaf304](https://github.com/hoaf304)  
🌐 Demo: [phonezone.rf.gd](http://phonezone.rf.gd)

---

## 📝 License

MIT License © 2026 PhoneZone
