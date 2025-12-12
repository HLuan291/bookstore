-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 12, 2025 lúc 11:02 AM
-- Phiên bản máy phục vụ: 8.4.7
-- Phiên bản PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bookstore`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bo_sach`
--

DROP TABLE IF EXISTS `bo_sach`;
CREATE TABLE IF NOT EXISTS `bo_sach` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_bosach` char(10) NOT NULL,
  `id_sach` char(10) NOT NULL,
  `id_danhmuc` char(10) NOT NULL,
  `ten_bo_sach` varchar(255) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_bosach_ma` (`id_bosach`),
  KEY `fk_bosach_sach` (`id_sach`),
  KEY `fk_bosach_dm` (`id_danhmuc`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `bo_sach`
--

INSERT INTO `bo_sach` (`id`, `id_bosach`, `id_sach`, `id_danhmuc`, `ten_bo_sach`, `gia`) VALUES
(4, 'BS01', 'S01', 'DM04', 'Sách giáo khoa lớp 2', 250000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

DROP TABLE IF EXISTS `chi_tiet_don_hang`;
CREATE TABLE IF NOT EXISTS `chi_tiet_don_hang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_chitiet` char(10) NOT NULL,
  `id_donhang` char(10) NOT NULL,
  `id_sach` char(10) NOT NULL,
  `so_luong` int NOT NULL,
  `don_gia` decimal(12,2) NOT NULL,
  `thanh_tien` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ctdh` (`id_chitiet`),
  KEY `fk_ctdh_dh` (`id_donhang`),
  KEY `fk_ctdh_sach` (`id_sach`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

DROP TABLE IF EXISTS `danh_muc`;
CREATE TABLE IF NOT EXISTS `danh_muc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_danhmuc` char(10) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_danhmuc_ma` (`id_danhmuc`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`id`, `id_danhmuc`, `ten_danh_muc`, `trang_thai`) VALUES
(1, 'DM01', 'Sách thiếu nhi', 1),
(2, 'DM02', 'Tiểu thuyết', 1),
(3, 'DM03', 'Kỹ năng sống', 1),
(4, 'DM04', 'Giáo khoa', 1),
(5, 'DM05', 'Sách lập trình', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

DROP TABLE IF EXISTS `don_hang`;
CREATE TABLE IF NOT EXISTS `don_hang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_donhang` char(10) NOT NULL,
  `id_nguoidung` char(10) NOT NULL,
  `ngay_dat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trang_thai` varchar(50) NOT NULL,
  `phuong_thuc_thanh_toan` varchar(50) NOT NULL,
  `trang_thai_thanh_toan` varchar(50) NOT NULL,
  `ten_nguoi_nhan` varchar(150) NOT NULL,
  `dien_thoai_nhan` varchar(20) NOT NULL,
  `dia_chi_chi_tiet` varchar(255) NOT NULL,
  `duong` varchar(100) DEFAULT NULL,
  `phuong` varchar(100) DEFAULT NULL,
  `thanh_pho` varchar(100) DEFAULT NULL,
  `tinh_thanh` varchar(100) DEFAULT NULL,
  `phi_ship` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tong_tien` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_donhang` (`id_donhang`),
  KEY `fk_donhang_user` (`id_nguoidung`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`id`, `id_donhang`, `id_nguoidung`, `ngay_dat`, `trang_thai`, `phuong_thuc_thanh_toan`, `trang_thai_thanh_toan`, `ten_nguoi_nhan`, `dien_thoai_nhan`, `dia_chi_chi_tiet`, `duong`, `phuong`, `thanh_pho`, `tinh_thanh`, `phi_ship`, `tong_tien`) VALUES
(1, 'DH01', 'U03', '2025-12-03 17:45:55', 'Đang xử lý', 'COD', 'Chưa thanh toán', 'Nguyễn Văn A', '0900000003', '12/3 Đường Sách', 'Đường Sách', 'Bến Nghé', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 15000.00, 315000.00),
(2, 'DH02', 'U03', '2025-12-03 17:45:55', 'Hoàn thành', 'Chuyển khoản', 'Đã thanh toán', 'Nguyễn Văn A', '0900000003', '45 Lê Lợi', 'Lê Lợi', 'Bến Thành', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 0.00, 120000.00),
(3, 'DH03', 'U04', '2025-12-03 17:45:55', 'Đang giao', 'COD', 'Chưa thanh toán', 'Trần Thị B', '0900000004', '89 Nguyễn Huệ', 'Nguyễn Huệ', 'Bến Nghé', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 20000.00, 200000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_nguoidung` char(10) NOT NULL,
  `ho_ten` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT '1',
  `ngay_tao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nguoidung_ma` (`id_nguoidung`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `id_nguoidung`, `ho_ten`, `email`, `so_dien_thoai`, `mat_khau`, `trang_thai`, `ngay_tao`) VALUES
(1, 'U01', 'Admin', 'admin@bookstore.test', '0900000001', '123456', 1, '2025-12-03 17:45:55'),
(2, 'U02', 'Nhân viên', 'staff@bookstore.test', '0900000002', '123456', 1, '2025-12-03 17:45:55'),
(3, 'U03', 'Nguyễn Văn A', 'khach1@bookstore.test', '0900000003', '123456', 1, '2025-12-03 17:45:55'),
(4, 'U04', 'Trần Thị B', 'khach2@bookstore.test', '0900000004', '123456', 1, '2025-12-03 17:45:55'),
(6, 'U186931', 'HuuLuan', 'huuluan@gmail.com', '04841556455', '$2y$10$xvABYFnhsfoXXIBswe2Jm.NDtFu9MKznknD//ICjB.stN48AE.rC2', 1, '2025-12-06 04:00:35'),
(9, 'U186932', 'Hi', 'test@gmail.com', '0778787878', '$2y$10$C.3StVyyMzXXAmJdqEmMHuyioNeV/4PpUx7G/KOMlf3KrgCALKvze', 1, '2025-12-06 15:19:28'),
(10, 'U986372', 'Hi', 'testadmin@gmail.com', '01245666678', '$2y$10$MvlqT.B17WI7nUxbrc/kxezBVVyBRJo/UMRYWjekTps5shKc57vp2', 1, '2025-12-06 15:33:54'),
(11, 'U986373', 'Hihi', 'hihi@gmail.com', '0778787866', '$2y$10$P5vw0DT5pBPD/BMlVWrF7OdyixypGsUK6CMxVMec001HRfblPeNhS', 1, '2025-12-06 15:59:29'),
(12, 'U986374', 'hello', 'test456@gmail.com', '0778787867', '$2y$10$RGZRfpaXNwSSrZU2xfiIiOWhlBJFV/EJ40KgaEfJIi7IHKKOC4m6u', 1, '2025-12-06 16:51:56'),
(13, 'U986375', 'hii', 'hii@gmail.com', '0778787865', '$2y$10$jeY4ztiJ2esQDy0cLylRWu2NSh5N1n0T2Ag6dpc0LiUZxFZ4Kpziq', 1, '2025-12-12 14:27:28'),
(14, 'U986376', 'heloo', 'h@gmail.com', '0778787866', '$2y$10$WwIj0HWnxNFJRHeA1d8ARu3lNjKw8vvAMUvZwwsrBmpq6BRVHGooy', 1, '2025-12-12 14:42:20'),
(15, 'U986377', 'Hi', 'ha@gmail.com', '0778787866', '$2y$10$aKXwHkE2P2pUQawu3PGXyuWpvG0vMt9klphKqhSMXo6JWRCtXu8ji', 1, '2025-12-12 14:47:49'),
(16, 'U986378', 'hi', 'hi@gmail.com', '0544897485', '$2y$10$RUUMg49RzZm2bQ/VNp7mq.IXDJ5I7tNo8oPWOqmviw/hNfPAFPNWy', 1, '2025-12-12 15:08:06'),
(17, 'U986379', 'hi', 'hffi@gmail.com', '0544897485', '$2y$10$VDYcbW7241xEGmSGmT2xG.rpyvxl4eUcPrm7LZAXHTfodnr57tFpK', 1, '2025-12-12 15:10:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung_vai_tro`
--

DROP TABLE IF EXISTS `nguoi_dung_vai_tro`;
CREATE TABLE IF NOT EXISTS `nguoi_dung_vai_tro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_nguoidungvaitro` char(10) NOT NULL,
  `id_nguoidung` char(10) NOT NULL,
  `id_vaitro` char(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ndvt` (`id_nguoidungvaitro`),
  KEY `fk_ndvt_user` (`id_nguoidung`),
  KEY `fk_ndvt_role` (`id_vaitro`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung_vai_tro`
--

INSERT INTO `nguoi_dung_vai_tro` (`id`, `id_nguoidungvaitro`, `id_nguoidung`, `id_vaitro`) VALUES
(1, 'NDVT01', 'U01', 'VT01'),
(2, 'NDVT02', 'U02', 'VT02'),
(3, 'NDVT03', 'U03', 'VT03'),
(4, 'NDVT04', 'U04', 'VT03'),
(7, '', 'U986375', 'VT03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhat_ky_kho`
--

DROP TABLE IF EXISTS `nhat_ky_kho`;
CREATE TABLE IF NOT EXISTS `nhat_ky_kho` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_nhatky` char(10) NOT NULL,
  `id_sach` char(10) NOT NULL,
  `id_nguoidung` char(10) NOT NULL,
  `hanh_dong` varchar(100) NOT NULL,
  `so_luong` int NOT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ngay_tao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nhatky` (`id_nhatky`),
  KEY `fk_nk_sach` (`id_sach`),
  KEY `fk_nk_nv` (`id_nguoidung`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phan_hoi`
--

DROP TABLE IF EXISTS `phan_hoi`;
CREATE TABLE IF NOT EXISTS `phan_hoi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_phanhoi` char(10) NOT NULL,
  `id_nguoidung` char(10) NOT NULL,
  `id_sach` char(10) NOT NULL,
  `danh_gia` tinyint NOT NULL,
  `noi_dung` text,
  `da_duyet` tinyint(1) NOT NULL DEFAULT '0',
  `ngay_tao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ph` (`id_phanhoi`),
  KEY `fk_ph_user` (`id_nguoidung`),
  KEY `fk_ph_sach` (`id_sach`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sach`
--

DROP TABLE IF EXISTS `sach`;
CREATE TABLE IF NOT EXISTS `sach` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_sach` char(10) NOT NULL,
  `id_danhmuc` char(10) NOT NULL,
  `id_bosach` char(10) DEFAULT NULL,
  `ten_sach` varchar(255) NOT NULL,
  `tac_gia` varchar(150) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  `ton_kho` int NOT NULL DEFAULT '0',
  `mo_ta` text,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT '1',
  `ngay_tao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sach_ma` (`id_sach`),
  KEY `fk_sach_danhmuc` (`id_danhmuc`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `sach`
--

INSERT INTO `sach` (`id`, `id_sach`, `id_danhmuc`, `id_bosach`, `ten_sach`, `tac_gia`, `gia`, `ton_kho`, `mo_ta`, `hinh_anh`, `trang_thai`, `ngay_tao`) VALUES
(19, 'S01', 'DM04', NULL, 'Tiếng Việt Lớp 2', 'NXB Giáo Dục', 15000.00, 10, 'Sách giáo khoa Tiếng Việt Lớp 2 Chân trời sáng tạo', '1765001094_SGK tieng viet 2 tap 1.jpg', 1, '2025-12-06 13:02:17'),
(21, 'S03', 'DM04', NULL, 'Tiếng Việt 2 - Tập 2 (CTST)', 'NXB Giáo Dục', 25000.00, 50, 'Sách Tiếng Việt 2 tập 2 bộ Chân trời sáng tạo', '1765002257_Tiếng Việt 2 T2.jpg', 1, '2025-12-06 13:15:51'),
(22, 'S04', 'DM04', NULL, 'Toán 2 - Tập 1 (CTST)', 'NXB Giáo Dục', 24000.00, 50, 'Sách Toán 2 tập 1 bộ Chân trời sáng tạo', '1765002171_shs_toan_2_tap_1_bia_70787f76d0d04423b9c6d23351ca4025_master.jpg', 1, '2025-12-06 13:15:51'),
(23, 'S05', 'DM04', NULL, 'Toán 2 - Tập 2 (CTST)', 'NXB Giáo Dục', 23000.00, 50, 'Sách Toán 2 tập 2 bộ Chân trời sáng tạo', '1765002100_Chan-troi-sang-tao-toan-2-2.jpg', 1, '2025-12-06 13:15:51'),
(24, 'S06', 'DM04', NULL, 'Tự nhiên và Xã hội 2 (CTST)', 'NXB Giáo Dục', 18000.00, 50, 'Sách TNXH 2 bộ Chân trời sáng tạo', '1765002120_TNXH.jpg', 1, '2025-12-06 13:15:51'),
(25, 'S07', 'DM04', NULL, 'Đạo đức 2 (CTST)', 'NXB Giáo Dục', 15000.00, 50, 'Sách Đạo đức 2 bộ Chân trời sáng tạo', '1765002074_SHS dao duc 2 bia sua.jpg', 1, '2025-12-06 13:15:51'),
(26, 'S08', 'DM04', NULL, 'Âm nhạc 2 (CTST)', 'NXB Giáo Dục', 12000.00, 50, 'Sách Âm nhạc 2 bộ Chân trời sáng tạo', '1765002207_shs-am-nhac-2-bia-sua_12720212159.jpg', 1, '2025-12-06 13:15:51'),
(27, 'S09', 'DM04', NULL, 'Mỹ thuật 2 (CTST)', 'NXB Giáo Dục', 14000.00, 50, 'Sách Mỹ thuật 2 bộ Chân trời sáng tạo', '1765002058_MT-2.jpg', 1, '2025-12-06 13:15:51'),
(28, 'S10', 'DM04', NULL, 'HĐ Trải nghiệm 2 (CTST)', 'NXB Giáo Dục', 16000.00, 50, 'Sách Hoạt động trải nghiệm 2 bộ Chân trời sáng tạo', '1765001970_Bia -SHS -HDTN 2.jpg', 1, '2025-12-06 13:15:51'),
(29, 'S11', 'DM04', NULL, 'Giáo dục thể chất 2 (CTST)', 'NXB Giáo Dục', 17000.00, 50, 'Sách GDTC 2 bộ Chân trời sáng tạo', '1765001950_GDTC 2.png', 1, '2025-12-06 13:15:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vai_tro`
--

DROP TABLE IF EXISTS `vai_tro`;
CREATE TABLE IF NOT EXISTS `vai_tro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_vaitro` char(10) NOT NULL,
  `ten_vai_tro` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_vaitro` (`id_vaitro`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `vai_tro`
--

INSERT INTO `vai_tro` (`id`, `id_vaitro`, `ten_vai_tro`) VALUES
(1, 'VT01', 'Admin'),
(2, 'VT02', 'Nhân viên'),
(3, 'VT03', 'Khách hàng');

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `bo_sach`
--
ALTER TABLE `bo_sach`
  ADD CONSTRAINT `fk_bosach_dm` FOREIGN KEY (`id_danhmuc`) REFERENCES `danh_muc` (`id_danhmuc`),
  ADD CONSTRAINT `fk_bosach_sach` FOREIGN KEY (`id_sach`) REFERENCES `sach` (`id_sach`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_dh` FOREIGN KEY (`id_donhang`) REFERENCES `don_hang` (`id_donhang`),
  ADD CONSTRAINT `fk_ctdh_sach` FOREIGN KEY (`id_sach`) REFERENCES `sach` (`id_sach`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_donhang_user` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id_nguoidung`);

--
-- Ràng buộc cho bảng `nguoi_dung_vai_tro`
--
ALTER TABLE `nguoi_dung_vai_tro`
  ADD CONSTRAINT `fk_ndvt_role` FOREIGN KEY (`id_vaitro`) REFERENCES `vai_tro` (`id_vaitro`),
  ADD CONSTRAINT `fk_ndvt_user` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id_nguoidung`);

--
-- Ràng buộc cho bảng `nhat_ky_kho`
--
ALTER TABLE `nhat_ky_kho`
  ADD CONSTRAINT `fk_nk_nv` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id_nguoidung`),
  ADD CONSTRAINT `fk_nk_sach` FOREIGN KEY (`id_sach`) REFERENCES `sach` (`id_sach`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `phan_hoi`
--
ALTER TABLE `phan_hoi`
  ADD CONSTRAINT `fk_ph_sach` FOREIGN KEY (`id_sach`) REFERENCES `sach` (`id_sach`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ph_user` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id_nguoidung`);

--
-- Ràng buộc cho bảng `sach`
--
ALTER TABLE `sach`
  ADD CONSTRAINT `fk_sach_danhmuc` FOREIGN KEY (`id_danhmuc`) REFERENCES `danh_muc` (`id_danhmuc`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
