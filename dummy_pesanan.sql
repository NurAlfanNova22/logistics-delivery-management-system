-- ==========================================
-- SCRIPT DUMMY DATA PESANAN & CUSTOMER
-- LANCAR EKSPEDISI
-- 
-- Petunjuk Import via phpMyAdmin:
-- 1. Buka phpMyAdmin, pilih database lancar-ekspedisi.
-- 2. Pilih tab "Import" di bagian atas.
-- 3. Upload file "dummy_pesanan.sql" ini dan klik "Go" / "Kirim".
-- ==========================================

-- 1. INSERT DUMMY USERS (CUSTOMERS)
-- Menggunakan ID 101 s/d 105. Jika ID sudah ada, data akan di-update.
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(101, 'PT. Sinar Jaya', 'sinarjaya@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '081234567890', 'Jl. Kawasan Industri No. 12, Cilegon', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
(102, 'CV. Abadi Makmur', 'abadimakmur@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '081234567891', 'Jl. Raya Gresik No. 45, Gresik', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
(103, 'PT. Indofood Sukses', 'indofood@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '081234567892', 'Kawasan Industri Sudirman, Jakarta', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
(104, 'CV. Tunas Baru', 'tunasbaru@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '081234567893', 'Jl. Sidoarjo Industri Indah No. 8, Sidoarjo', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
(105, 'PT. Steel Indonesia', 'steelindo@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '081234567894', 'Kawasan Industri Cikarang, Bekasi', '2026-01-01 00:00:00', '2026-01-01 00:00:00')
ON DUPLICATE KEY UPDATE 
`name`=VALUES(`name`), `email`=VALUES(`email`), `role`=VALUES(`role`), `no_hp`=VALUES(`no_hp`), `alamat`=VALUES(`alamat`);

-- 2. INSERT DUMMY PESANANS
-- Jika nomor resi sudah terdaftar, data akan di-update untuk menghindari duplikasi.
INSERT INTO `pesanans` (`user_id`, `resi`, `nama_pabrik`, `alamat_asal`, `alamat_tujuan`, `jenis_barang`, `berat`, `status`, `status_pengiriman`, `total_biaya`, `status_pembayaran`, `alasan_penolakan`, `created_at`, `updated_at`) VALUES
-- --- JANUARI 2026 ---
(101, 'LEX260105001', 'Pabrik Baja Cilegon', 'Cilegon @-6.01,106.02', 'Tangerang @-6.17,106.63', 'Besi Ulir', 5000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 2500000, 'SUDAH DIBAYAR', NULL, '2026-01-05 10:00:00', '2026-01-05 10:00:00'),
(102, 'LEX260112001', 'Pabrik Semen Gresik', 'Gresik @-7.15,112.65', 'Surabaya @-7.25,112.75', 'Semen Portland', 8000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 3200000, 'SUDAH DIBAYAR', NULL, '2026-01-12 11:30:00', '2026-01-12 11:30:00'),
(103, 'LEX260120001', 'Pabrik Tepung Bogasari', 'Jakarta Utara @-6.11,106.89', 'Bekasi @-6.23,106.99', 'Tepung Terigu', 4000, 'DIBATALKAN', '', 1800000, 'BELUM DIBAYAR', NULL, '2026-01-20 14:15:00', '2026-01-20 14:15:00'),

-- --- FEBRUARI 2026 ---
(104, 'LEX260202001', 'Pabrik Kertas Tjiwi Kimia', 'Sidoarjo @-7.47,112.43', 'Mojokerto @-7.46,112.43', 'Kertas HVS', 6000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 2800000, 'SUDAH DIBAYAR', NULL, '2026-02-02 09:45:00', '2026-02-02 09:45:00'),
(105, 'LEX260214001', 'Pabrik Pipa Spindo', 'Surabaya @-7.25,112.75', 'Sidoarjo @-7.45,112.71', 'Pipa Baja', 7000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 2200000, 'BELUM DIBAYAR', NULL, '2026-02-14 16:20:00', '2026-02-14 16:20:00'),
(101, 'LEX260220001', 'Pabrik Baja Cilegon', 'Cilegon @-6.01,106.02', 'Jakarta Barat @-6.16,106.76', 'Lempengan Besi', 9000, 'DITOLAK', '', 4500000, 'BELUM DIBAYAR', 'Armada tidak mencukupi untuk kapasitas muatan pada tanggal tersebut.', '2026-02-20 10:10:00', '2026-02-20 10:10:00'),

-- --- MARET 2026 ---
(102, 'LEX260305001', 'Pabrik Pupuk Kaltim', 'Surabaya @-7.20,112.72', 'Malang @-7.98,112.63', 'Pupuk Urea', 4500, 'SELESAI', 'PESANAN TELAH DIKIRIM', 2100000, 'SUDAH DIBAYAR', NULL, '2026-03-05 08:30:00', '2026-03-05 08:30:00'),
(103, 'LEX260315001', 'Pabrik Indofood', 'Semarang @-6.99,110.42', 'Solo @-7.56,110.82', 'Mie Instan', 3000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 1500000, 'SUDAH DIBAYAR', NULL, '2026-03-15 11:00:00', '2026-03-15 11:00:00'),
(104, 'LEX260325001', 'Pabrik Gula Candi', 'Sidoarjo @-7.44,112.72', 'Pasuruan @-7.64,112.90', 'Gula Pasir', 5500, 'SELESAI', 'PESANAN TELAH DIKIRIM', 2600000, 'BELUM DIBAYAR', NULL, '2026-03-25 13:15:00', '2026-03-25 13:15:00'),

-- --- APRIL 2026 ---
(105, 'LEX260402001', 'PT. Krakatau Steel', 'Cilegon @-6.01,106.02', 'Karawang @-6.30,107.29', 'Besi Wire Rod', 10000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 5200000, 'SUDAH DIBAYAR', NULL, '2026-04-02 10:00:00', '2026-04-02 10:00:00'),
(101, 'LEX260412001', 'Pabrik Kabel Metal', 'Tangerang @-6.17,106.63', 'Cikarang @-6.26,107.15', 'Kabel Tembaga', 3500, 'SELESAI', 'PESANAN TELAH DIKIRIM', 1900000, 'BELUM DIBAYAR', NULL, '2026-04-12 14:00:00', '2026-04-12 14:00:00'),
(102, 'LEX260422001', 'Pabrik Plastik Mas', 'Surabaya @-7.25,112.75', 'Gresik @-7.15,112.65', 'Biji Plastik', 4000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 1600000, 'SUDAH DIBAYAR', NULL, '2026-04-22 15:30:00', '2026-04-22 15:30:00'),

-- --- MEI 2026 ---
(103, 'LEX260501001', 'PT. Nestle Indonesia', 'Pasuruan @-7.64,112.90', 'Surabaya @-7.25,112.75', 'Susu Kemasan', 5000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 2400000, 'SUDAH DIBAYAR', NULL, '2026-05-01 09:00:00', '2026-05-01 09:00:00'),
(104, 'LEX260510001', 'Pabrik Semen Tuban', 'Tuban @-6.90,112.06', 'Surabaya @-7.25,112.75', 'Semen Zak', 8500, 'SELESAI', 'PESANAN TELAH DIKIRIM', 3400000, 'BELUM DIBAYAR', NULL, '2026-05-10 11:45:00', '2026-05-10 11:45:00'),
(105, 'LEX260518001', 'Pabrik Baja Cilegon', 'Cilegon @-6.01,106.02', 'Serang @-6.12,106.15', 'Baja Profil', 7500, 'AKTIF', 'DALAM PERJALANAN', 2700000, 'BELUM DIBAYAR', NULL, '2026-05-18 14:20:00', '2026-05-18 14:20:00'),
(101, 'LEX260525001', 'PT. Sinar Jaya', 'Jakarta Utara @-6.11,106.89', 'Depok @-6.40,106.82', 'Minyak Goreng', 4800, 'SELESAI', 'PESANAN TELAH DIKIRIM', 2000000, 'SUDAH DIBAYAR', NULL, '2026-05-25 16:10:00', '2026-05-25 16:10:00'),

-- --- JUNI 2026 ---
(102, 'LEX260601001', 'Pabrik Pakan Ternak', 'Sidoarjo @-7.44,112.72', 'Kediri @-7.81,112.01', 'Konsentrat Pakan', 6000, 'SELESAI', 'PESANAN TELAH DIKIRIM', 3000000, 'SUDAH DIBAYAR', NULL, '2026-06-01 10:00:00', '2026-06-01 10:00:00'),
(103, 'LEX260605001', 'Pabrik Indofood', 'Bandung @-6.91,107.60', 'Jakarta Timur @-6.22,106.90', 'Bumbu Instan', 2500, 'MENUNGGU KONFIRMASI', '', 1400000, 'BELUM DIBAYAR', NULL, '2026-06-05 13:00:00', '2026-06-05 13:00:00'),
(104, 'LEX260610001', 'Pabrik Gula Candi', 'Sidoarjo @-7.44,112.72', 'Surabaya @-7.25,112.75', 'Gula Kristal', 4000, 'AKTIF', 'MENUNGGU PICKUP', 1700000, 'BELUM DIBAYAR', NULL, '2026-06-10 14:45:00', '2026-06-10 14:45:00'),
(105, 'LEX260615001', 'PT. Steel Indonesia', 'Cilegon @-6.01,106.02', 'Bogor @-6.59,106.79', 'Besi Pelat', 8000, 'MENUNGGU KONFIRMASI', '', 3600000, 'BELUM DIBAYAR', NULL, '2026-06-15 11:00:00', '2026-06-15 11:00:00')
ON DUPLICATE KEY UPDATE 
`user_id`=VALUES(`user_id`),
`nama_pabrik`=VALUES(`nama_pabrik`),
`alamat_asal`=VALUES(`alamat_asal`),
`alamat_tujuan`=VALUES(`alamat_tujuan`),
`jenis_barang`=VALUES(`jenis_barang`),
`berat`=VALUES(`berat`),
`status`=VALUES(`status`),
`status_pengiriman`=VALUES(`status_pengiriman`),
`total_biaya`=VALUES(`total_biaya`),
`status_pembayaran`=VALUES(`status_pembayaran`),
`alasan_penolakan`=VALUES(`alasan_penolakan`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`);
