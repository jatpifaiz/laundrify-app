-- ============================================
--  LAUNDRIFY — Seed Data
-- ============================================
USE laundri_db;

-- ---- Seed Member ----
INSERT INTO member (nama, no_hp, alamat, created_at) VALUES
('Budi Santoso',     '08123456789',  'Jl. Merdeka No. 10, Bandung',        '2025-01-10 08:00:00'),
('Siti Rahayu',      '08987654321',  'Jl. Sudirman No. 25, Jakarta Selatan','2025-01-15 09:30:00'),
('Ahmad Fauzi',      '08561234567',  'Jl. Pahlawan No. 3, Surabaya',       '2025-02-01 10:00:00'),
('Dewi Lestari',     '08112233445',  'Jl. Diponegoro No. 7, Yogyakarta',   '2025-02-20 11:00:00'),
('Riko Prasetyo',    '08223344556',  'Jl. Gatot Subroto No. 99, Semarang', '2025-03-05 14:00:00');

-- ---- Seed Layanan ----
INSERT INTO layanan (nama_layanan, harga_per_kg, satuan, deskripsi) VALUES
('Cuci + Setrika',   7000.00,  'kg',  'Layanan cuci dan setrika standar'),
('Cuci Kering',      5000.00,  'kg',  'Hanya cuci dan dikeringkan'),
('Setrika Saja',     4000.00,  'kg',  'Hanya setrika tanpa cuci'),
('Laundry Kilat',    10000.00, 'kg',  'Selesai dalam 3 jam, harga premium');

-- ---- Seed Transaksi ----
INSERT INTO transaksi (id_member, id_layanan, berat_kg, total_harga, status, catatan, tanggal_masuk, tanggal_selesai) VALUES
(1, 1, 3.5,  24500.00, 'diambil',  'Pakai pewangi',       '2025-05-01', '2025-05-02'),
(2, 2, 5.0,  25000.00, 'selesai',  NULL,                  '2025-05-10', '2025-05-11'),
(3, 4, 2.0,  20000.00, 'proses',   'Urgent, besok pagi',  '2025-05-20', NULL),
(4, 1, 4.0,  28000.00, 'antri',    NULL,                  '2025-05-25', NULL),
(5, 3, 6.0,  24000.00, 'selesai',  'Jangan terlipat',     '2025-05-18', '2025-05-19'),
(1, 2, 2.5,  12500.00, 'diambil',  NULL,                  '2025-04-15', '2025-04-16'),
(2, 1, 7.0,  49000.00, 'proses',   'Pisah baju putih',    '2025-05-23', NULL),
(3, 4, 1.5,  15000.00, 'antri',    NULL,                  '2025-05-26', NULL);
