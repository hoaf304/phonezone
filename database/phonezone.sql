-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: sql304.byetcluster.com
-- Thời gian đã tạo: Th5 29, 2026 lúc 02:12 AM
-- Phiên bản máy phục vụ: 11.4.11-MariaDB
-- Phiên bản PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `if0_42044394_phonezone`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `ho_ten` varchar(150) NOT NULL,
  `email` varchar(180) NOT NULL,
  `mat_khau` varchar(255) NOT NULL COMMENT 'bcrypt hash',
  `vai_tro` enum('superadmin','admin','nhan_vien') NOT NULL DEFAULT 'nhan_vien',
  `avatar` varchar(255) DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `ho_ten`, `email`, `mat_khau`, `vai_tro`, `avatar`, `trang_thai`, `last_login`, `created_at`) VALUES
(1, 'Super Admin', 'admin@phonezone.vn', '$2y$10$rTLGRR9v8LpXf7Kmrh4RDOKdbgkg9WQopG7fQMrckMmLErfyATCEa', 'superadmin', NULL, 1, '2026-05-28 22:53:38', '2026-05-28 21:52:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banner`
--

CREATE TABLE `banner` (
  `id` int(10) UNSIGNED NOT NULL,
  `tieu_de` varchar(255) DEFAULT NULL,
  `mo_ta` varchar(500) DEFAULT NULL,
  `hinh_anh` varchar(255) NOT NULL,
  `url_link` varchar(255) DEFAULT NULL,
  `vi_tri` enum('hero','popup','sidebar','footer') NOT NULL DEFAULT 'hero',
  `thu_tu` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `an_hien` tinyint(1) NOT NULL DEFAULT 1,
  `bat_dau` datetime DEFAULT NULL,
  `ket_thuc` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_the_san_pham`
--

CREATE TABLE `bien_the_san_pham` (
  `id` int(10) UNSIGNED NOT NULL,
  `san_pham_id` int(10) UNSIGNED NOT NULL,
  `mau_sac` varchar(80) NOT NULL COMMENT 'VD: Titan Đen',
  `dung_luong` varchar(80) NOT NULL COMMENT 'VD: 256GB',
  `sku` varchar(120) NOT NULL COMMENT 'APL-IP15PM-256-BLK',
  `gia_ban` bigint(20) UNSIGNED NOT NULL COMMENT 'Đơn vị: VNĐ',
  `gia_khuyen_mai` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'NULL nếu không KM',
  `ton_kho` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `hinh_anh` varchar(255) DEFAULT NULL COMMENT 'Ảnh riêng theo màu',
  `an_hien` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_the_san_pham`
--

INSERT INTO `bien_the_san_pham` (`id`, `san_pham_id`, `mau_sac`, `dung_luong`, `sku`, `gia_ban`, `gia_khuyen_mai`, `ton_kho`, `hinh_anh`, `an_hien`) VALUES
(11, 1, 'Titan Tự Nhiên', '256GB', 'IP15PM-256-TTN', 34990000, 33990000, 15, 'iphone15promax-natural.jpg', 1),
(12, 2, 'Titan Xanh', '512GB', 'IP15PM-512-TX', 39990000, 38990000, 10, 'iphone15promax-blue.jpg', 1),
(13, 3, 'Titan Đen', '128GB', 'IP15P-128-TD', 29990000, 28990000, 12, 'iphone15pro-black.jpg', 1),
(34, 27, 'Xám', '256GB', 'RM12PP-256-GRY', 11990000, 11490000, 14, 'realme12proplus-gray.jpg', 1),
(35, 15, 'Mint', '128GB', 'S23FE-128-MNT', 14990000, 13990000, 14, NULL, 1),
(36, 16, 'Xanh Navy', '256GB', 'A55-256-NAVY', 10990000, 9990000, 20, NULL, 1),
(38, 17, 'Vàng', '128GB', 'A35-128-YLW', 8990000, 8490000, 18, NULL, 1),
(39, 18, 'Đen', '512GB', 'XM14T-512-BLK', 16990000, 15990000, 12, NULL, 1),
(40, 21, 'Vàng', '256GB', 'POCOX6P-256-YLW', 9990000, 9490000, 13, NULL, 1),
(41, 19, 'Tím', '256GB', 'RN13P-256-PUR', 10990000, 10490000, 17, NULL, 1),
(44, 14, 'Kem', '256GB', 'S23PLUS-256-CRE', 21990000, 20990000, 5, NULL, 1),
(45, 12, 'Đen', '256GB', 'S24PLUS-256-BLK', 25990000, 24990000, 10, NULL, 1),
(48, 7, 'Tím Deep Purple', '128GB', 'IP14PM-128-PR', 27990000, 26990000, 8, NULL, 1),
(51, 10, 'Đen', '128GB', 'IP14-128-BLK', 19990000, 18990000, 10, NULL, 1),
(52, 11, 'Titan Gray', '256GB', 'S24U-256-GR', 31990000, 30990000, 11, NULL, 1),
(54, 23, 'Bạc', '512GB', 'RENO12PRO-512-SL', 18990000, 17990000, 9, NULL, 1),
(55, 24, 'Hồng', '256GB', 'RENO12-256-PNK', 13990000, 13490000, 11, NULL, 1),
(56, 25, 'Tím', '512GB', 'V40-512-PUR', 14990000, 14490000, 10, NULL, 1),
(57, 26, 'Đỏ', '256GB', 'V30-256-RED', 10990000, 10490000, 15, NULL, 1),
(58, 22, 'Đen', '128GB', 'POCOM6P-128-BLK', 5490000, 4990000, 22, NULL, 1),
(59, 8, 'Bạc', '128GB', 'IP14P-128-SL', 24990000, 23990000, 9, NULL, 1),
(60, 9, 'Xanh Dương', '128GB', 'IP14PLUS-128-BLU', 21990000, 20990000, 7, NULL, 1),
(61, 13, 'Xanh Lá', '256GB', 'S23U-256-GRN', 26990000, 25990000, 6, NULL, 1),
(62, 20, 'Xanh', '128GB', 'RN13-128-BLU', 5990000, 5490000, 25, NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cai_dat`
--

CREATE TABLE `cai_dat` (
  `khoa` varchar(100) NOT NULL,
  `gia_tri` text DEFAULT NULL,
  `nhom` varchar(50) NOT NULL DEFAULT 'chung'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cai_dat`
--

INSERT INTO `cai_dat` (`khoa`, `gia_tri`, `nhom`) VALUES
('dia_chi', 'Hà Nội, Việt Nam', 'chung'),
('email_lien_he', 'info@phonezone.vn', 'chung'),
('hotline', '1900 6789', 'chung'),
('mien_phi_ship_tu', '1000000', 'van_chuyen'),
('phi_ship_mac_dinh', '30000', 'van_chuyen'),
('slogan', 'Thiên đường điện thoại', 'chung'),
('ten_cua_hang', 'PhoneZone', 'chung'),
('thue_vat', '0', 'thanh_toan'),
('tien_te', 'VND', 'thanh_toan');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `id` int(10) UNSIGNED NOT NULL,
  `don_hang_id` int(10) UNSIGNED NOT NULL,
  `bien_the_id` int(10) UNSIGNED DEFAULT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `mau_sac` varchar(80) DEFAULT NULL,
  `dung_luong` varchar(80) DEFAULT NULL,
  `sku` varchar(120) DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `don_gia` bigint(20) UNSIGNED NOT NULL,
  `so_luong` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `thanh_tien` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`id`, `don_hang_id`, `bien_the_id`, `ten_san_pham`, `mau_sac`, `dung_luong`, `sku`, `hinh_anh`, `don_gia`, `so_luong`, `thanh_tien`) VALUES
(55, 1, 11, 'iPhone 15 Pro Max 256GB', 'Titan Đen', '256GB', 'APL-IP15PM-256-BLK', 'iphone15promax-black.jpg', 33990000, 1, 33990000),
(56, 2, 12, 'iPhone 15 Pro Max 256GB', 'Titan Tự Nhiên', '256GB', 'IP15PM-256-TTN', 'iphone15promax-natural.jpg', 34990000, 1, 34990000),
(57, 3, 13, 'Samsung Galaxy S24 Ultra 256GB', 'Titan Gray', '256GB', 'S24U-256-GR', 's24ultra-gray.jpg', 30990000, 1, 30990000),
(58, 4, NULL, 'OPPO Reno 12 256GB', 'Hồng', '256GB', 'RENO12-256-PNK', 'reno12-pink.jpg', 13490000, 1, 13490000),
(59, 5, NULL, 'ROG Phone 8 256GB', 'Đen', '256GB', 'ROG8-256-BLK', 'rog8-black.jpg', 24990000, 1, 24990000),
(60, 6, NULL, 'iPhone 15 128GB', 'Đen', '128GB', 'APL-IP15-128-BLK', 'iphone15-black.jpg', 21990000, 1, 21990000),
(61, 7, NULL, 'iPhone 15 128GB', 'Hồng', '128GB', 'APL-IP15-128-PNK', 'iphone15-pink.jpg', 21990000, 1, 21990000),
(62, 8, NULL, 'iPhone 15 128GB', 'Xanh Lá', '128GB', 'APL-IP15-128-GRN', 'iphone15-green.jpg', 21990000, 1, 21990000),
(63, 9, NULL, 'iPhone 15 128GB', 'Xanh Dương', '128GB', 'APL-IP15-128-BLU', 'iphone15-blue.jpg', 21990000, 1, 21990000),
(64, 10, NULL, 'iPhone 15 128GB', 'Vàng', '128GB', 'APL-IP15-128-YLL', 'iphone15-yellow.jpg', 21990000, 1, 21990000),
(65, 11, NULL, 'Samsung Galaxy S24 128GB', 'Xám', '128GB', 'SS-S24-128-GR', 's24-gray.jpg', 19990000, 1, 19990000),
(66, 12, NULL, 'Samsung Galaxy S24 128GB', 'Đen', '128GB', 'SS-S24-128-BLK', 's24-black.jpg', 19990000, 1, 19990000),
(67, 13, NULL, 'Samsung Galaxy S24 128GB', 'Tím', '128GB', 'SS-S24-128-VLT', 's24-violet.jpg', 19990000, 1, 19990000),
(68, 14, NULL, 'Samsung Galaxy S24 128GB', 'Vàng', '128GB', 'SS-S24-128-YLL', 's24-yellow.jpg', 19990000, 1, 19990000),
(69, 14, NULL, 'iPhone 15 256GB', 'Đen', '256GB', 'APL-IP15-256-BLK', 'iphone15-black.jpg', 24990000, 1, 24990000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `id` int(10) UNSIGNED NOT NULL,
  `san_pham_id` int(10) UNSIGNED NOT NULL,
  `khach_hang_id` int(10) UNSIGNED DEFAULT NULL,
  `don_hang_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Xác thực đã mua',
  `ho_ten` varchar(150) NOT NULL COMMENT 'Snapshot tên',
  `so_sao` tinyint(3) UNSIGNED NOT NULL COMMENT '1-5',
  `tieu_de` varchar(255) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `trang_thai` enum('cho_duyet','da_duyet','an') NOT NULL DEFAULT 'cho_duyet',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_gia`
--

INSERT INTO `danh_gia` (`id`, `san_pham_id`, `khach_hang_id`, `don_hang_id`, `ho_ten`, `so_sao`, `tieu_de`, `noi_dung`, `trang_thai`, `created_at`) VALUES
(21, 1, NULL, NULL, 'Nguyễn Minh Quân', 5, 'Máy quá mạnh', 'iPhone 15 Pro Max dùng cực mượt, camera đẹp và pin tốt.', 'da_duyet', '2025-05-01 10:15:00'),
(22, 1, NULL, NULL, 'Trần Quốc Bảo', 4, 'Camera đẹp', 'Zoom và chụp đêm trên iPhone 15 Pro Max rất ấn tượng.', 'da_duyet', '2025-05-02 09:20:00'),
(23, 2, NULL, NULL, 'Lê Hoàng Nam', 5, 'Bản 512GB rất thoải mái', 'Lưu video và game không lo đầy bộ nhớ.', 'da_duyet', '2025-05-03 14:00:00'),
(24, 3, NULL, NULL, 'Phạm Đức Anh', 5, 'Hiệu năng tốt', 'iPhone 15 Pro nhỏ gọn nhưng rất mạnh.', 'da_duyet', '2025-05-04 08:40:00'),
(25, 7, NULL, NULL, 'Đặng Hải Đăng', 4, 'Pin ổn', 'iPhone 14 Pro Max vẫn dùng rất ngon ở thời điểm này.', 'da_duyet', '2025-05-05 11:30:00'),
(26, 8, NULL, NULL, 'Võ Minh Hiếu', 5, 'Màn hình đẹp', 'iPhone 14 Pro hiển thị cực sắc nét.', 'da_duyet', '2025-05-06 13:10:00'),
(27, 9, NULL, NULL, 'Nguyễn Khánh', 4, 'Loa to', 'iPhone 14 Plus xem phim và nghe nhạc rất thích.', 'da_duyet', '2025-05-07 15:45:00'),
(28, 10, NULL, NULL, 'Lý Thành Công', 5, 'Ổn định', 'iPhone 14 dùng hằng ngày rất mượt.', 'da_duyet', '2025-05-08 17:20:00'),
(29, 11, NULL, NULL, 'Bùi Trung Kiên', 5, 'Zoom cực đỉnh', 'S24 Ultra chụp zoom xa cực nét.', 'da_duyet', '2025-05-09 12:00:00'),
(30, 12, NULL, NULL, 'Hoàng Nhật Minh', 4, 'Thiết kế đẹp', 'S24 Plus cầm chắc tay và màn đẹp.', 'da_duyet', '2025-05-10 18:25:00'),
(31, 13, NULL, NULL, 'Phan Văn Tài', 5, 'Gaming mạnh', 'S23 Ultra chơi game rất mượt.', 'da_duyet', '2025-05-11 20:15:00'),
(32, 14, NULL, NULL, 'Đỗ Gia Huy', 4, 'Máy ổn', 'S23 Plus pin khá tốt và hiệu năng cao.', 'da_duyet', '2025-05-12 09:50:00'),
(33, 15, NULL, NULL, 'Trịnh Văn Nam', 4, 'Đáng mua', 'S23 FE phù hợp trong tầm giá.', 'da_duyet', '2025-05-13 10:05:00'),
(34, 16, NULL, NULL, 'Ngô Thành Đạt', 5, 'Pin trâu', 'Galaxy A55 dùng cả ngày không lo hết pin.', 'da_duyet', '2025-05-14 16:30:00'),
(35, 17, NULL, NULL, 'Lê Minh Tuấn', 4, 'Màn hình đẹp', 'A35 hiển thị màu sắc khá tốt.', 'da_duyet', '2025-05-15 14:40:00'),
(36, 18, NULL, NULL, 'Nguyễn Hải Long', 5, 'Camera đẹp', 'Xiaomi 14T chụp chân dung rất ổn.', 'da_duyet', '2025-05-16 11:11:00'),
(37, 19, NULL, NULL, 'Đinh Quang Huy', 5, 'Cấu hình ngon', 'Redmi Note 13 Pro+ chiến game tốt.', 'da_duyet', '2025-05-17 08:45:00'),
(38, 20, NULL, NULL, 'Phạm Nhật Duy', 4, 'Giá hợp lý', 'Redmi Note 13 phù hợp học sinh sinh viên.', 'da_duyet', '2025-05-18 19:10:00'),
(39, 21, NULL, NULL, 'Trần Quốc Việt', 5, 'Gaming cực tốt', 'POCO X6 Pro hiệu năng rất mạnh.', 'da_duyet', '2025-05-19 21:00:00'),
(40, 22, NULL, NULL, 'Vũ Minh Khang', 4, 'Máy ổn định', 'POCO M6 Pro dùng cơ bản rất tốt.', 'da_duyet', '2025-05-20 13:33:00'),
(41, 23, NULL, NULL, 'Nguyễn Đức Thành', 5, 'Camera đẹp', 'OPPO Reno 12 Pro selfie rất đẹp.', 'da_duyet', '2025-05-21 09:00:00'),
(42, 24, NULL, NULL, 'Phạm Gia Bảo', 4, 'Thiết kế đẹp', 'Reno 12 cầm nhẹ và sang.', 'da_duyet', '2025-05-22 10:45:00'),
(43, 25, NULL, NULL, 'Lê Quang Huy', 5, 'Pin tốt', 'Vivo V40 dùng pin rất trâu.', 'da_duyet', '2025-05-23 16:20:00'),
(44, 26, NULL, NULL, 'Trần Minh Nhật', 4, 'Màn đẹp', 'Vivo V30 hiển thị rất sắc nét.', 'da_duyet', '2025-05-24 12:10:00'),
(45, 27, NULL, NULL, 'Hoàng Quốc Anh', 5, 'Chụp zoom đẹp', 'Realme 12 Pro+ chụp xa khá ấn tượng.', 'da_duyet', '2025-05-25 18:40:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `id` int(10) UNSIGNED NOT NULL,
  `ten` varchar(100) NOT NULL COMMENT 'VD: iPhone, Samsung Galaxy, Xiaomi',
  `slug` varchar(120) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `thu_tu` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `an_hien` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`id`, `ten`, `slug`, `mo_ta`, `hinh_anh`, `thu_tu`, `an_hien`, `created_at`) VALUES
(1, 'iPhone', 'iphone', NULL, NULL, 1, 1, '2026-05-28 21:52:40'),
(2, 'Samsung Galaxy', 'samsung-galaxy', NULL, NULL, 2, 1, '2026-05-28 21:52:40'),
(3, 'Xiaomi', 'xiaomi', NULL, NULL, 3, 1, '2026-05-28 21:52:40'),
(4, 'OPPO', 'oppo', NULL, NULL, 4, 1, '2026-05-28 21:52:40'),
(5, 'Vivo', 'vivo', NULL, NULL, 5, 1, '2026-05-28 21:52:40'),
(6, 'Realme', 'realme', NULL, NULL, 6, 1, '2026-05-28 21:52:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_chi_khach_hang`
--

CREATE TABLE `dia_chi_khach_hang` (
  `id` int(10) UNSIGNED NOT NULL,
  `khach_hang_id` int(10) UNSIGNED NOT NULL,
  `ho_ten` varchar(150) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `tinh_thanh` varchar(100) NOT NULL,
  `quan_huyen` varchar(100) NOT NULL,
  `phuong_xa` varchar(100) NOT NULL,
  `so_nha_duong` varchar(255) NOT NULL,
  `la_mac_dinh` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int(10) UNSIGNED NOT NULL,
  `ma_don` varchar(30) NOT NULL COMMENT 'ORD-20250510-001',
  `khach_hang_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'NULL nếu khách vãng lai',
  `ten_nguoi_nhan` varchar(150) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `tinh_thanh` varchar(100) NOT NULL,
  `quan_huyen` varchar(100) NOT NULL,
  `phuong_xa` varchar(100) NOT NULL,
  `so_nha_duong` varchar(255) NOT NULL,
  `ghi_chu` text DEFAULT NULL,
  `tam_tinh` bigint(20) UNSIGNED NOT NULL COMMENT 'Trước KM + ship',
  `giam_gia` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `phi_van_chuyen` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `tong_tien` bigint(20) UNSIGNED NOT NULL COMMENT 'Thực thanh toán',
  `ma_giam_gia_id` int(10) UNSIGNED DEFAULT NULL,
  `phuong_thuc_tt` enum('cod','chuyen_khoan','momo','vnpay','zalopay') NOT NULL DEFAULT 'cod',
  `trang_thai_tt` enum('chua_tt','da_tt','hoan_tien') NOT NULL DEFAULT 'chua_tt',
  `ma_giao_dich` varchar(100) DEFAULT NULL COMMENT 'Mã GD từ cổng thanh toán',
  `trang_thai` enum('cho_xac_nhan','da_xac_nhan','dang_dong_hang','dang_giao','da_giao','da_huy','hoan_hang') NOT NULL DEFAULT 'cho_xac_nhan',
  `ma_van_don` varchar(100) DEFAULT NULL,
  `don_vi_van_chuyen` varchar(100) DEFAULT NULL,
  `ngay_du_kien_giao` date DEFAULT NULL,
  `ngay_giao_thuc` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`id`, `ma_don`, `khach_hang_id`, `ten_nguoi_nhan`, `so_dien_thoai`, `tinh_thanh`, `quan_huyen`, `phuong_xa`, `so_nha_duong`, `ghi_chu`, `tam_tinh`, `giam_gia`, `phi_van_chuyen`, `tong_tien`, `ma_giam_gia_id`, `phuong_thuc_tt`, `trang_thai_tt`, `ma_giao_dich`, `trang_thai`, `ma_van_don`, `don_vi_van_chuyen`, `ngay_du_kien_giao`, `ngay_giao_thuc`, `created_at`, `updated_at`) VALUES
(1, 'ORD-20250510-002', 2, 'Trần Quốc Bảo', '0978123456', 'Hà Nội', 'Đống Đa', 'Láng Hạ', '88 Huỳnh Thúc Kháng', NULL, 21990000, 200000, 30000, 21820000, 2, 'momo', 'da_tt', NULL, 'dang_giao', NULL, NULL, NULL, NULL, '2025-05-10 09:00:00', '2026-05-28 22:13:13'),
(2, 'ORD-20250510-003', 3, 'Lê Hoàng Nam', '0965234789', 'Hà Nội', 'Hai Bà Trưng', 'Bạch Mai', '45 Minh Khai', NULL, 17990000, 0, 30000, 18020000, NULL, 'cod', 'chua_tt', NULL, 'cho_xac_nhan', NULL, NULL, NULL, NULL, '2025-05-10 10:15:00', '2026-05-28 22:13:13'),
(3, 'ORD-20250510-004', 4, 'Phạm Đức Anh', '0934567891', 'Hà Nội', 'Thanh Xuân', 'Nhân Chính', '120 Nguyễn Trãi', NULL, 27990000, 500000, 0, 27490000, 1, 'vnpay', 'da_tt', NULL, 'da_giao', NULL, NULL, NULL, NULL, '2025-05-11 14:30:00', '2026-05-28 22:13:13'),
(4, 'ORD-20250510-005', 5, 'Đặng Hải Đăng', '0912345678', 'Hà Nội', 'Hoàng Mai', 'Định Công', '56 Giải Phóng', NULL, 10990000, 0, 30000, 11020000, NULL, 'cod', 'chua_tt', NULL, 'dang_dong_hang', NULL, NULL, NULL, NULL, '2025-05-11 16:00:00', '2026-05-28 22:13:13'),
(5, 'ORD-20250510-006', 6, 'Võ Minh Hiếu', '0981122334', 'Hà Nội', 'Nam Từ Liêm', 'Mỹ Đình 1', '33 Hàm Nghi', NULL, 16990000, 200000, 30000, 16820000, 2, 'zalopay', 'da_tt', NULL, 'da_xac_nhan', NULL, NULL, NULL, NULL, '2025-05-12 08:20:00', '2026-05-28 22:13:13'),
(6, 'ORD-20250510-007', 7, 'Nguyễn Khánh', '0977445566', 'Hà Nội', 'Bắc Từ Liêm', 'Xuân Đỉnh', '78 Phạm Văn Đồng', NULL, 8990000, 0, 30000, 9020000, NULL, 'cod', 'chua_tt', NULL, 'cho_xac_nhan', NULL, NULL, NULL, NULL, '2025-05-12 10:45:00', '2026-05-28 22:13:13'),
(7, 'ORD-20250510-008', 8, 'Lý Thành Công', '0944556677', 'Hà Nội', 'Long Biên', 'Ngọc Lâm', '11 Nguyễn Văn Cừ', NULL, 23990000, 500000, 0, 23490000, 1, 'momo', 'da_tt', NULL, 'da_giao', NULL, NULL, NULL, NULL, '2025-05-13 13:15:00', '2026-05-28 22:13:13'),
(8, 'ORD-20250510-009', 9, 'Bùi Trung Kiên', '0922334455', 'Hà Nội', 'Hà Đông', 'Mộ Lao', '99 Trần Phú', NULL, 7990000, 0, 30000, 8020000, NULL, 'cod', 'chua_tt', NULL, 'dang_giao', NULL, NULL, NULL, NULL, '2025-05-13 15:00:00', '2026-05-28 22:13:13'),
(9, 'ORD-20250510-010', 10, 'Hoàng Nhật Minh', '0909888777', 'Hà Nội', 'Ba Đình', 'Kim Mã', '66 Đội Cấn', NULL, 24990000, 500000, 0, 24490000, 1, 'vnpay', 'da_tt', NULL, 'da_giao', NULL, NULL, NULL, NULL, '2025-05-14 11:40:00', '2026-05-28 22:13:13'),
(10, 'ORD-20250510-011', 1, 'Nguyễn Minh Quân', '0987654321', 'Hà Nội', 'Cầu Giấy', 'Dịch Vọng', '12 Trần Thái Tông', NULL, 15990000, 200000, 30000, 15720000, 2, 'cod', 'chua_tt', NULL, 'da_xac_nhan', NULL, NULL, NULL, NULL, '2025-05-14 18:20:00', '2026-05-28 22:13:13'),
(11, 'ORD-20250510-012', 2, 'Trần Quốc Bảo', '0978123456', 'Hà Nội', 'Đống Đa', 'Láng Hạ', '88 Huỳnh Thúc Kháng', NULL, 11990000, 0, 30000, 12020000, NULL, 'momo', 'da_tt', NULL, 'da_giao', NULL, NULL, NULL, NULL, '2025-05-15 09:50:00', '2026-05-28 22:13:13'),
(12, 'ORD-20250510-013', 3, 'Lê Hoàng Nam', '0965234789', 'Hà Nội', 'Hai Bà Trưng', 'Bạch Mai', '45 Minh Khai', NULL, 4990000, 0, 30000, 5020000, NULL, 'cod', 'chua_tt', NULL, 'cho_xac_nhan', NULL, NULL, NULL, NULL, '2025-05-15 12:10:00', '2026-05-28 22:13:13'),
(13, 'ORD-20250510-014', 4, 'Phạm Đức Anh', '0934567891', 'Hà Nội', 'Thanh Xuân', 'Nhân Chính', '120 Nguyễn Trãi', NULL, 22990000, 500000, 0, 22490000, 1, 'zalopay', 'da_tt', NULL, 'dang_giao', NULL, NULL, NULL, NULL, '2025-05-16 08:00:00', '2026-05-28 22:13:13'),
(14, 'ORD-20250510-015', 5, 'Đặng Hải Đăng', '0912345678', 'Hà Nội', 'Hoàng Mai', 'Định Công', '56 Giải Phóng', NULL, 10990000, 200000, 30000, 10720000, 2, 'cod', 'chua_tt', NULL, 'cho_xac_nhan', NULL, NULL, NULL, NULL, '2025-05-16 16:45:00', '2026-05-28 22:13:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gio_hang`
--

CREATE TABLE `gio_hang` (
  `id` int(10) UNSIGNED NOT NULL,
  `khach_hang_id` int(10) UNSIGNED NOT NULL,
  `bien_the_id` int(10) UNSIGNED NOT NULL,
  `so_luong` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hang_san_xuat`
--

CREATE TABLE `hang_san_xuat` (
  `id` int(10) UNSIGNED NOT NULL,
  `ten` varchar(100) NOT NULL COMMENT 'Apple, Samsung, Xiaomi, OPPO...',
  `slug` varchar(120) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `quoc_gia` varchar(100) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `an_hien` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hang_san_xuat`
--

INSERT INTO `hang_san_xuat` (`id`, `ten`, `slug`, `logo`, `quoc_gia`, `mo_ta`, `an_hien`) VALUES
(1, 'Apple', 'apple', NULL, 'Hoa Kỳ', NULL, 1),
(2, 'Samsung', 'samsung', NULL, 'Hàn Quốc', NULL, 1),
(3, 'Xiaomi', 'xiaomi', NULL, 'Trung Quốc', NULL, 1),
(4, 'OPPO', 'oppo', NULL, 'Trung Quốc', NULL, 1),
(5, 'Vivo', 'vivo', NULL, 'Trung Quốc', NULL, 1),
(6, 'Realme', 'realme', NULL, 'Trung Quốc', NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hinh_anh_san_pham`
--

CREATE TABLE `hinh_anh_san_pham` (
  `id` int(10) UNSIGNED NOT NULL,
  `san_pham_id` int(10) UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `thu_tu` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `la_hinh_chinh` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

CREATE TABLE `khach_hang` (
  `id` int(10) UNSIGNED NOT NULL,
  `ho_ten` varchar(150) NOT NULL,
  `email` varchar(180) NOT NULL,
  `mat_khau` varchar(255) NOT NULL COMMENT 'bcrypt hash',
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `gioi_tinh` enum('nam','nu','khac') DEFAULT 'khac',
  `ngay_sinh` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `trang_thai` enum('hoat_dong','bi_khoa') NOT NULL DEFAULT 'hoat_dong',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

INSERT INTO `khach_hang` (`id`, `ho_ten`, `email`, `mat_khau`, `so_dien_thoai`, `gioi_tinh`, `ngay_sinh`, `avatar`, `trang_thai`, `email_verified`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Minh Quân', 'quan1@gmail.com', '$2y$10$abc123', '0987654321', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(2, 'Trần Quốc Bảo', 'bao2@gmail.com', '$2y$10$abc123', '0978123456', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(3, 'Lê Hoàng Nam', 'nam3@gmail.com', '$2y$10$abc123', '0965234789', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(4, 'Phạm Đức Anh', 'anh4@gmail.com', '$2y$10$abc123', '0934567891', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(5, 'Đặng Hải Đăng', 'dang5@gmail.com', '$2y$10$abc123', '0912345678', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(6, 'Võ Minh Hiếu', 'hieu6@gmail.com', '$2y$10$abc123', '0981122334', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(7, 'Nguyễn Khánh', 'khanh7@gmail.com', '$2y$10$abc123', '0977445566', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(8, 'Lý Thành Công', 'cong8@gmail.com', '$2y$10$abc123', '0944556677', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(9, 'Bùi Trung Kiên', 'kien9@gmail.com', '$2y$10$abc123', '0922334455', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08'),
(10, 'Hoàng Nhật Minh', 'minh10@gmail.com', '$2y$10$abc123', '0909888777', 'nam', NULL, NULL, 'hoat_dong', 1, NULL, '2026-05-28 21:56:08', '2026-05-28 21:56:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_don_hang`
--

CREATE TABLE `lich_su_don_hang` (
  `id` int(10) UNSIGNED NOT NULL,
  `don_hang_id` int(10) UNSIGNED NOT NULL,
  `trang_thai` varchar(50) NOT NULL,
  `ghi_chu` text DEFAULT NULL,
  `nguoi_thuc_hien` varchar(100) DEFAULT NULL COMMENT 'Admin name hoặc system',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_don_hang`
--

INSERT INTO `lich_su_don_hang` (`id`, `don_hang_id`, `trang_thai`, `ghi_chu`, `nguoi_thuc_hien`, `created_at`) VALUES
(1, 1, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-10 08:00:00'),
(2, 1, 'da_xac_nhan', 'Đã xác nhận đơn hàng', 'Admin', '2025-05-10 08:15:00'),
(3, 2, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-10 09:10:00'),
(4, 2, 'da_xac_nhan', 'Đã xác nhận đơn hàng', 'Admin', '2025-05-10 09:25:00'),
(5, 3, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-10 10:00:00'),
(6, 4, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-10 10:40:00'),
(7, 4, 'da_xac_nhan', 'Đã xác nhận đơn hàng', 'Admin', '2025-05-10 10:55:00'),
(8, 5, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-10 11:20:00'),
(9, 5, 'dang_giao', 'Đơn hàng đang được giao', 'Admin', '2025-05-10 13:00:00'),
(10, 5, 'da_giao', 'Đã giao hàng thành công', 'Admin', '2025-05-11 09:30:00'),
(11, 6, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 08:45:00'),
(12, 7, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 09:15:00'),
(13, 8, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 10:05:00'),
(14, 8, 'da_huy', 'Khách hàng yêu cầu hủy đơn', 'Admin', '2025-05-11 10:45:00'),
(15, 9, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 11:30:00'),
(16, 10, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 12:15:00'),
(17, 11, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 13:00:00'),
(18, 11, 'da_xac_nhan', 'Đã xác nhận đơn hàng', 'Admin', '2025-05-11 13:20:00'),
(19, 12, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 14:10:00'),
(20, 13, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 15:25:00'),
(21, 14, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system', '2025-05-11 16:40:00'),
(22, 14, 'da_xac_nhan', 'Đã xác nhận đơn hàng', 'Admin', '2025-05-11 17:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ma_giam_gia`
--

CREATE TABLE `ma_giam_gia` (
  `id` int(10) UNSIGNED NOT NULL,
  `ma` varchar(50) NOT NULL,
  `ten` varchar(150) DEFAULT NULL,
  `loai` enum('phan_tram','co_dinh') NOT NULL DEFAULT 'phan_tram',
  `gia_tri` decimal(12,2) NOT NULL COMMENT '% hoặc số tiền cố định',
  `giam_toi_da` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Giảm tối đa (cho loại %)',
  `don_hang_toi_thieu` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `so_luong_tong` int(10) UNSIGNED DEFAULT NULL COMMENT 'NULL = không giới hạn',
  `so_luong_da_dung` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `gioi_han_moi_kh` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `bat_dau` datetime DEFAULT NULL,
  `ket_thuc` datetime DEFAULT NULL,
  `an_hien` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ma_giam_gia`
--

INSERT INTO `ma_giam_gia` (`id`, `ma`, `ten`, `loai`, `gia_tri`, `giam_toi_da`, `don_hang_toi_thieu`, `so_luong_tong`, `so_luong_da_dung`, `gioi_han_moi_kh`, `bat_dau`, `ket_thuc`, `an_hien`, `created_at`) VALUES
(1, 'WELCOME10', 'Giảm 10% chào mừng', 'phan_tram', '10.00', 500000, 1000000, 1000, 0, 1, NULL, '2025-12-31 23:59:59', 1, '2026-05-28 21:52:40'),
(2, 'FLASH500K', 'Flash sale giảm 500K', 'co_dinh', '500000.00', NULL, 5000000, 200, 0, 1, NULL, '2025-06-30 23:59:59', 1, '2026-05-28 21:52:40'),
(3, 'PHONEZONE5', 'Ưu đãi 5% mọi đơn', 'phan_tram', '5.00', 300000, 500000, NULL, 0, 1, NULL, '2025-12-31 23:59:59', 1, '2026-05-28 21:52:40'),
(4, 'SALE10', 'Giảm 10% tối đa 500K', 'phan_tram', '10.00', 500000, 5000000, 100, 0, 1, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1, '2026-05-28 22:12:51'),
(5, 'GIAM200K', 'Giảm trực tiếp 200K', 'co_dinh', '200000.00', NULL, 3000000, 50, 0, 1, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1, '2026-05-28 22:12:51'),
(6, 'FREESHIP', 'Miễn phí vận chuyển', 'co_dinh', '30000.00', NULL, 1000000, NULL, 0, 2, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1, '2026-05-28 22:12:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhat_ky_admin`
--

CREATE TABLE `nhat_ky_admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL,
  `hanh_dong` varchar(100) NOT NULL COMMENT 'create_product, update_order...',
  `doi_tuong` varchar(50) DEFAULT NULL COMMENT 'san_pham, don_hang...',
  `doi_tuong_id` int(10) UNSIGNED DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int(10) UNSIGNED NOT NULL,
  `danh_muc_id` int(10) UNSIGNED NOT NULL,
  `hang_id` int(10) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `slug` varchar(280) NOT NULL,
  `mo_ta_ngan` varchar(500) DEFAULT NULL,
  `mo_ta_chi_tiet` longtext DEFAULT NULL,
  `hinh_chinh` varchar(255) DEFAULT NULL,
  `noi_bat` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Hiển thị trang chủ',
  `ban_chay` tinyint(1) NOT NULL DEFAULT 0,
  `an_hien` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_desc` varchar(500) DEFAULT NULL,
  `luot_xem` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`id`, `danh_muc_id`, `hang_id`, `ten`, `slug`, `mo_ta_ngan`, `mo_ta_chi_tiet`, `hinh_chinh`, `noi_bat`, `ban_chay`, `an_hien`, `meta_title`, `meta_desc`, `luot_xem`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'iPhone 15 Pro Max 256GB – Chính hãng VN/A', 'iphone-15-pro-max-256gb-chinh-hang', 'Smartphone cao cấp nhất của Apple năm 2024 với chip A17 Pro, camera 48MP và màn hình 6.7 inch ProMotion 120Hz.', 'iPhone 15 Pro Max là chiếc điện thoại flagship cao cấp nhất của Apple, được trang bị chip A17 Pro tiên tiến nhất được sản xuất trên tiến trình 3nm, mang lại hiệu năng vượt trội cùng khả năng tiết kiệm pin tốt hơn.', NULL, 1, 1, 1, 'iPhone 15 Pro Max 256GB Chính Hãng | PhoneZone', 'Mua iPhone 15 Pro Max 256GB chính hãng tại PhoneZone. Giá tốt nhất, bảo hành 12 tháng.', 0, '2026-05-28 21:58:14', '2026-05-28 22:05:10'),
(2, 2, 2, 'Samsung Galaxy S24 Ultra 256GB – Chính hãng', 'samsung-galaxy-s24-ultra-256gb', 'Flagship Android cao cấp của Samsung với bút S Pen tích hợp và AI Galaxy.', 'Samsung Galaxy S24 Ultra mang đến trải nghiệm smartphone cao cấp nhất với chip Snapdragon 8 Gen 3, camera 200MP và tích hợp S Pen.', NULL, 1, 1, 1, 'Samsung Galaxy S24 Ultra 256GB | PhoneZone', 'Mua Samsung Galaxy S24 Ultra chính hãng tại PhoneZone.', 0, '2026-05-28 21:58:14', '2026-05-28 22:05:15'),
(3, 3, 3, 'Xiaomi 14 Ultra 512GB – Chính hãng', 'xiaomi-14-ultra-512gb', 'Flagship Xiaomi với hệ thống camera Leica và chip Snapdragon 8 Gen 3.', 'Xiaomi 14 Ultra là đỉnh cao công nghệ của Xiaomi với camera đồng phát triển cùng Leica, chip Snapdragon 8 Gen 3 và màn hình AMOLED 6.73 inch.', NULL, 1, 0, 1, 'Xiaomi 14 Ultra 512GB | PhoneZone', 'Mua Xiaomi 14 Ultra chính hãng tại PhoneZone.', 0, '2026-05-28 21:58:14', '2026-05-28 22:05:19'),
(7, 1, 1, 'iPhone 14 Pro 256GB', 'iphone-14-pro-256gb', 'iPhone cao cấp màn 120Hz', 'iPhone 14 Pro sở hữu Dynamic Island và chip A16 Bionic.', 'products/img_6a192c2b9b8361.78846331.jpg', 1, 1, 1, 'iPhone 14 Pro Chính Hãng', 'Mua iPhone 14 Pro giá tốt.', 95, '2026-05-28 22:07:12', '2026-05-28 23:03:24'),
(8, 1, 1, 'iPhone 14 Plus 128GB', 'iphone-14-plus-128gb', 'iPhone màn lớn pin khỏe', 'iPhone 14 Plus có màn hình lớn và pin cực tốt.', 'products/img_6a192c53cf2172.67663386.jpeg', 1, 1, 1, 'iPhone 14 Plus', 'iPhone 14 Plus chính hãng.', 70, '2026-05-28 22:07:12', '2026-05-28 23:10:33'),
(9, 1, 1, 'iPhone 13 128GB', 'iphone-13-128gb', 'iPhone hiệu năng ổn định', 'iPhone 13 phù hợp chơi game và chụp ảnh.', 'products/img_6a192c73b40003.16588058.jpg', 1, 1, 1, 'iPhone 13', 'iPhone 13 giá tốt.', 65, '2026-05-28 22:07:12', '2026-05-28 23:10:39'),
(10, 1, 1, 'iPhone 12 Mini', 'iphone-12-mini', 'iPhone nhỏ gọn', 'iPhone 12 Mini dành cho người thích máy nhỏ.', 'products/img_6a192c919353b9.14864688.jpg', 0, 0, 1, 'iPhone 12 Mini', 'iPhone nhỏ gọn hiệu năng cao.', 30, '2026-05-28 22:07:12', '2026-05-28 23:05:06'),
(11, 1, 2, 'Samsung Galaxy S23 Ultra', 'samsung-galaxy-s23-ultra', 'Flagship Samsung cao cấp', 'S23 Ultra có camera zoom mạnh và bút S-Pen.', 'products/img_6a192cb609d263.82881506.webp', 1, 1, 1, 'Samsung Galaxy S23 Ultra', 'Galaxy S23 Ultra chính hãng.', 120, '2026-05-28 22:07:12', '2026-05-28 23:05:42'),
(12, 1, 2, 'Samsung Galaxy S23 FE', 'samsung-galaxy-s23-fe', 'Flagship giá dễ tiếp cận', 'S23 FE hiệu năng mạnh camera đẹp.', 'products/img_6a192ba3b23ab9.35968836.jpg', 1, 1, 1, 'Samsung Galaxy S23 FE', 'Samsung S23 FE giá tốt.', 88, '2026-05-28 22:07:12', '2026-05-28 23:01:08'),
(13, 1, 2, 'Samsung Galaxy A55 5G', 'samsung-galaxy-a55-5g', 'Tầm trung pin khỏe', 'Galaxy A55 có màn hình AMOLED đẹp.', 'products/img_6a192b76ec4432.49104645.webp', 1, 1, 1, 'Galaxy A55 5G', 'Samsung A55 chính hãng.', 52, '2026-05-28 22:07:12', '2026-05-28 23:10:52'),
(14, 1, 2, 'Samsung Galaxy Z Flip5', 'samsung-galaxy-z-flip5', 'Điện thoại gập thời trang', 'Galaxy Z Flip5 thiết kế hiện đại.', 'products/img_6a192b88333aa8.90741551.jpg', 1, 0, 1, 'Galaxy Z Flip5', 'Điện thoại Samsung gập.', 74, '2026-05-28 22:07:12', '2026-05-28 23:00:40'),
(15, 1, 3, 'Xiaomi 13T Pro', 'xiaomi-13t-pro', 'Camera Leica cao cấp', 'Xiaomi 13T Pro hiệu năng mạnh sạc siêu nhanh.', 'products/img_6a192a782daae4.93224543.webp', 1, 1, 1, 'Xiaomi 13T Pro', 'Điện thoại Xiaomi cao cấp.', 90, '2026-05-28 22:07:12', '2026-05-28 22:56:08'),
(16, 1, 3, 'Redmi Note 13 Pro', 'redmi-note-13-pro', 'Giá rẻ cấu hình mạnh', 'Redmi Note 13 Pro phù hợp học sinh sinh viên.', 'products/img_6a192a98ccec87.71104891.jpg', 0, 1, 1, 'Redmi Note 13 Pro', 'Redmi Note 13 Pro giá tốt.', 58, '2026-05-28 22:07:12', '2026-05-28 22:56:40'),
(17, 1, 3, 'POCO X6 Pro', 'poco-x6-pro', 'Gaming phone giá tốt', 'POCO X6 Pro hiệu năng mạnh cho game thủ.', 'products/img_6a192ac855f836.90812525.webp', 0, 1, 1, 'POCO X6 Pro', 'POCO X6 Pro cấu hình mạnh.', 62, '2026-05-28 22:07:12', '2026-05-28 22:57:28'),
(18, 1, 3, 'Redmi 13C', 'redmi-13c', 'Điện thoại phổ thông', 'Redmi 13C pin khỏe giá rẻ.', 'products/img_6a192b0a6ce547.34400176.png', 0, 0, 1, 'Redmi 13C', 'Xiaomi Redmi giá rẻ.', 27, '2026-05-28 22:07:12', '2026-05-28 22:58:34'),
(19, 1, 4, 'OPPO Find X6 Pro', 'oppo-find-x6-pro', 'Flagship camera đẹp', 'Find X6 Pro có camera Hasselblad cao cấp.', 'products/img_6a192b4251a046.44013849.webp', 1, 1, 1, 'OPPO Find X6 Pro', 'OPPO flagship chính hãng.', 77, '2026-05-28 22:07:12', '2026-05-28 22:59:30'),
(20, 1, 4, 'OPPO Reno11 F', 'oppo-reno11-f', 'Thiết kế đẹp selfie tốt', 'Reno11 F phù hợp giới trẻ.', 'products/img_6a192b54bbae27.62396641.webp', 0, 1, 1, 'OPPO Reno11 F', 'OPPO Reno11 F giá tốt.', 41, '2026-05-28 22:07:12', '2026-05-28 22:59:48'),
(21, 1, 4, 'OPPO A79 5G', 'oppo-a79-5g', 'Pin khỏe sạc nhanh', 'OPPO A79 đáp ứng tốt nhu cầu cơ bản.', 'products/img_6a192b28b0b247.15985983.webp', 0, 0, 1, 'OPPO A79 5G', 'Điện thoại OPPO giá rẻ.', 26, '2026-05-28 22:07:12', '2026-05-28 22:59:04'),
(22, 5, 5, 'Vivo X100 Pro', 'vivo-x100-pro', 'Camera Zeiss flagship', 'Vivo X100 Pro chụp ảnh chuyên nghiệp.', 'products/img_6a192cd51410f9.28063444.png', 1, 1, 1, 'Vivo X100 Pro', 'Vivo flagship cao cấp.', 93, '2026-05-28 22:07:12', '2026-05-28 23:09:15'),
(23, 5, 5, 'Vivo V30 5G', 'vivo-v30-5g', 'Thiết kế mỏng nhẹ', 'Vivo V30 nổi bật với camera selfie.', 'products/img_6a192cfe335812.64405919.webp', 0, 1, 1, 'Vivo V30 5G', 'Vivo V30 chính hãng.', 37, '2026-05-28 22:07:12', '2026-05-28 23:06:54'),
(24, 5, 5, 'Vivo Y100', 'vivo-y100', 'Pin lớn giá hợp lý', 'Vivo Y100 phù hợp nhu cầu cơ bản.', 'products/img_6a192d251d21f9.05448826.webp', 0, 0, 1, 'Vivo Y100', 'Điện thoại Vivo giá tốt.', 23, '2026-05-28 22:07:12', '2026-05-28 23:07:33'),
(25, 6, 6, 'Realme GT5 Pro', 'realme-gt5-pro', 'Gaming phone mạnh mẽ', 'Realme GT5 Pro dùng chip Snapdragon cao cấp.', 'products/img_6a192d552593b4.87879431.jpg', 1, 1, 1, 'Realme GT5 Pro', 'Điện thoại Realme gaming.', 68, '2026-05-28 22:07:12', '2026-05-28 23:08:21'),
(26, 6, 6, 'Realme 12 Pro Plus', 'realme-12-pro-plus', 'Camera zoom đẹp', 'Realme 12 Pro Plus có camera tiềm vọng.', 'products/img_6a192d8237e350.65681354.jpg', 0, 1, 1, 'Realme 12 Pro Plus', 'Realme camera đẹp.', 45, '2026-05-28 22:07:12', '2026-05-28 23:09:06'),
(27, 1, 6, 'Realme C67', 'realme-c67', 'Điện thoại giá rẻ', 'Realme C67 pin khỏe cho học sinh sinh viên.', 'c67.jpg', 0, 0, 1, 'Realme C67', 'Realme C67 giá tốt.', 21, '2026-05-28 22:07:12', '2026-05-28 22:07:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thong_so_ky_thuat`
--

CREATE TABLE `thong_so_ky_thuat` (
  `id` int(10) UNSIGNED NOT NULL,
  `san_pham_id` int(10) UNSIGNED NOT NULL,
  `ten_thong_so` varchar(150) NOT NULL COMMENT 'VD: Màn hình, RAM, Pin...',
  `gia_tri` varchar(500) NOT NULL COMMENT 'VD: 6.7 inch OLED 120Hz',
  `thu_tu` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thong_so_ky_thuat`
--

INSERT INTO `thong_so_ky_thuat` (`id`, `san_pham_id`, `ten_thong_so`, `gia_tri`, `thu_tu`) VALUES
(21, 1, 'Màn hình', '6.7 inch Super Retina XDR OLED', 0),
(22, 1, 'Chip', 'Apple A17 Pro', 0),
(23, 1, 'RAM', '8GB', 0),
(24, 1, 'Bộ nhớ', '256GB', 0),
(25, 1, 'Camera sau', '48MP + 12MP + 12MP', 0),
(26, 1, 'Pin', '4422mAh', 0),
(27, 3, 'Màn hình', '6.1 inch Super Retina XDR OLED', 0),
(28, 3, 'Chip', 'Apple A17 Pro', 0),
(29, 3, 'RAM', '8GB', 0),
(30, 3, 'Bộ nhớ', '128GB', 0),
(31, 3, 'Camera sau', '48MP + 12MP + 12MP', 0),
(32, 3, 'Pin', '3274mAh', 0),
(87, 27, 'Màn hình', '6.7 inch AMOLED 120Hz', 0),
(88, 27, 'Chip', 'Snapdragon 7s Gen 2', 0),
(89, 27, 'RAM', '12GB', 0),
(90, 27, 'Bộ nhớ', '256GB', 0),
(91, 27, 'Camera sau', '50MP + 64MP + 8MP', 0),
(92, 27, 'Pin', '5000mAh', 0),
(93, 16, 'Màn hình', '6.6 inch Super AMOLED', 0),
(94, 16, 'Chip', 'Exynos 1480', 1),
(95, 16, 'RAM', '8GB', 2),
(96, 16, 'Bộ nhớ', '256GB', 3),
(97, 16, 'Camera sau', '50MP + 12MP + 5MP', 4),
(98, 16, 'Pin', '5000mAh', 5),
(105, 18, 'Màn hình', '6.67 inch AMOLED 144Hz', 0),
(106, 18, 'Chip', 'Dimensity 8300 Ultra', 1),
(107, 18, 'RAM', '12GB', 2),
(108, 18, 'Bộ nhớ', '512GB', 3),
(109, 18, 'Camera sau', '50MP + 12MP + 50MP', 4),
(110, 18, 'Pin', '5000mAh', 5),
(111, 21, 'Màn hình', '6.67 inch AMOLED 120Hz', 0),
(112, 21, 'Chip', 'Dimensity 8300 Ultra', 1),
(113, 21, 'RAM', '12GB', 2),
(114, 21, 'Bộ nhớ', '256GB', 3),
(115, 21, 'Camera sau', '64MP + 8MP + 2MP', 4),
(116, 21, 'Pin', '5000mAh', 5),
(117, 19, 'Màn hình', '6.67 inch AMOLED', 0),
(118, 19, 'Chip', 'Dimensity 7200 Ultra', 1),
(119, 19, 'RAM', '12GB', 2),
(120, 19, 'Bộ nhớ', '256GB', 3),
(121, 19, 'Camera sau', '200MP + 8MP + 2MP', 4),
(122, 19, 'Pin', '5000mAh', 5),
(129, 12, 'Màn hình', '6.7 inch Dynamic AMOLED 2X', 0),
(130, 12, 'Chip', 'Exynos 2400', 1),
(131, 12, 'RAM', '12GB', 2),
(132, 12, 'Bộ nhớ', '256GB', 3),
(133, 12, 'Camera sau', '50MP + 12MP + 10MP', 4),
(134, 12, 'Pin', '4900mAh', 5),
(135, 11, 'Màn hình', '6.8 inch Dynamic AMOLED 2X', 0),
(136, 11, 'Chip', 'Snapdragon 8 Gen 3', 1),
(137, 11, 'RAM', '12GB', 2),
(138, 11, 'Bộ nhớ', '256GB', 3),
(139, 11, 'Camera sau', '200MP + 50MP + 12MP + 10MP', 4),
(140, 11, 'Pin', '5000mAh', 5),
(141, 23, 'Màn hình', '6.7 inch AMOLED', 0),
(142, 23, 'Chip', 'Dimensity 9200+', 1),
(143, 23, 'RAM', '12GB', 2),
(144, 23, 'Bộ nhớ', '512GB', 3),
(145, 23, 'Camera sau', '50MP + 50MP + 8MP', 4),
(146, 23, 'Pin', '5000mAh', 5),
(147, 25, 'Màn hình', '6.78 inch AMOLED 120Hz', 0),
(148, 25, 'Chip', 'Snapdragon 7 Gen 3', 1),
(149, 25, 'RAM', '12GB', 2),
(150, 25, 'Bộ nhớ', '512GB', 3),
(151, 25, 'Camera sau', '50MP + 50MP', 4),
(152, 25, 'Pin', '5500mAh', 5),
(153, 13, 'Màn hình', '6.8 inch Dynamic AMOLED 2X', 0),
(154, 13, 'Chip', 'Snapdragon 8 Gen 2', 1),
(155, 13, 'RAM', '12GB', 2),
(156, 13, 'Bộ nhớ', '256GB', 3),
(157, 13, 'Camera sau', '200MP + 12MP + 10MP + 10MP', 4),
(158, 13, 'Pin', '5000mAh', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `yeu_thich`
--

CREATE TABLE `yeu_thich` (
  `id` int(10) UNSIGNED NOT NULL,
  `khach_hang_id` int(10) UNSIGNED NOT NULL,
  `san_pham_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bien_the_san_pham`
--
ALTER TABLE `bien_the_san_pham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_sp` (`san_pham_id`);

--
-- Chỉ mục cho bảng `cai_dat`
--
ALTER TABLE `cai_dat`
  ADD PRIMARY KEY (`khoa`);

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dh` (`don_hang_id`),
  ADD KEY `idx_bt` (`bien_the_id`);

--
-- Chỉ mục cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sp` (`san_pham_id`),
  ADD KEY `idx_kh` (`khach_hang_id`),
  ADD KEY `fk_dg_dh` (`don_hang_id`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `dia_chi_khach_hang`
--
ALTER TABLE `dia_chi_khach_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kh` (`khach_hang_id`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_don` (`ma_don`),
  ADD KEY `idx_kh` (`khach_hang_id`),
  ADD KEY `idx_ma_don` (`ma_don`),
  ADD KEY `idx_trang_thai` (`trang_thai`),
  ADD KEY `fk_dh_mgg` (`ma_giam_gia_id`);

--
-- Chỉ mục cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kh_bt` (`khach_hang_id`,`bien_the_id`),
  ADD KEY `fk_gh_bt` (`bien_the_id`);

--
-- Chỉ mục cho bảng `hang_san_xuat`
--
ALTER TABLE `hang_san_xuat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `hinh_anh_san_pham`
--
ALTER TABLE `hinh_anh_san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sp` (`san_pham_id`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Chỉ mục cho bảng `lich_su_don_hang`
--
ALTER TABLE `lich_su_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dh` (`don_hang_id`);

--
-- Chỉ mục cho bảng `ma_giam_gia`
--
ALTER TABLE `ma_giam_gia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma` (`ma`);

--
-- Chỉ mục cho bảng `nhat_ky_admin`
--
ALTER TABLE `nhat_ky_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_id`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_danh_muc` (`danh_muc_id`),
  ADD KEY `idx_hang` (`hang_id`);

--
-- Chỉ mục cho bảng `thong_so_ky_thuat`
--
ALTER TABLE `thong_so_ky_thuat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sp` (`san_pham_id`);

--
-- Chỉ mục cho bảng `yeu_thich`
--
ALTER TABLE `yeu_thich`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kh_sp` (`khach_hang_id`,`san_pham_id`),
  ADD KEY `fk_yt_sp` (`san_pham_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bien_the_san_pham`
--
ALTER TABLE `bien_the_san_pham`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `dia_chi_khach_hang`
--
ALTER TABLE `dia_chi_khach_hang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hang_san_xuat`
--
ALTER TABLE `hang_san_xuat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hinh_anh_san_pham`
--
ALTER TABLE `hinh_anh_san_pham`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `lich_su_don_hang`
--
ALTER TABLE `lich_su_don_hang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `ma_giam_gia`
--
ALTER TABLE `ma_giam_gia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `nhat_ky_admin`
--
ALTER TABLE `nhat_ky_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `thong_so_ky_thuat`
--
ALTER TABLE `thong_so_ky_thuat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT cho bảng `yeu_thich`
--
ALTER TABLE `yeu_thich`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bien_the_san_pham`
--
ALTER TABLE `bien_the_san_pham`
  ADD CONSTRAINT `fk_bt_sp` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_bt` FOREIGN KEY (`bien_the_id`) REFERENCES `bien_the_san_pham` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ctdh_dh` FOREIGN KEY (`don_hang_id`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `fk_dg_dh` FOREIGN KEY (`don_hang_id`) REFERENCES `don_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dg_kh` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dg_sp` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `dia_chi_khach_hang`
--
ALTER TABLE `dia_chi_khach_hang`
  ADD CONSTRAINT `fk_dc_kh` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_dh_kh` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dh_mgg` FOREIGN KEY (`ma_giam_gia_id`) REFERENCES `ma_giam_gia` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `fk_gh_bt` FOREIGN KEY (`bien_the_id`) REFERENCES `bien_the_san_pham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_gh_kh` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hinh_anh_san_pham`
--
ALTER TABLE `hinh_anh_san_pham`
  ADD CONSTRAINT `fk_hinh_sp` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lich_su_don_hang`
--
ALTER TABLE `lich_su_don_hang`
  ADD CONSTRAINT `fk_ls_dh` FOREIGN KEY (`don_hang_id`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nhat_ky_admin`
--
ALTER TABLE `nhat_ky_admin`
  ADD CONSTRAINT `fk_nk_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `fk_sp_danhmuc` FOREIGN KEY (`danh_muc_id`) REFERENCES `danh_muc` (`id`),
  ADD CONSTRAINT `fk_sp_hang` FOREIGN KEY (`hang_id`) REFERENCES `hang_san_xuat` (`id`);

--
-- Các ràng buộc cho bảng `thong_so_ky_thuat`
--
ALTER TABLE `thong_so_ky_thuat`
  ADD CONSTRAINT `fk_tskt_sp` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `yeu_thich`
--
ALTER TABLE `yeu_thich`
  ADD CONSTRAINT `fk_yt_kh` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_yt_sp` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
