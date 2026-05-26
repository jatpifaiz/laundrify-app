-- Jalankan file ini di phpMyAdmin atau MySQL CLI

-- 1. Izinkan tgl_selesai NULL (untuk booking yang belum ada estimasi selesai)
ALTER TABLE transaksi MODIFY COLUMN tgl_selesai DATE NULL DEFAULT NULL;

-- 2. Tambah kolom id_user ke transaksi (link ke tabel users)
ALTER TABLE transaksi ADD COLUMN id_user INT NULL AFTER id;

-- 3. Buat tabel users
CREATE TABLE IF NOT EXISTS users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(100)  NOT NULL,
  email       VARCHAR(100)  NOT NULL UNIQUE,
  password    VARCHAR(255)  NOT NULL,
  no_hp       VARCHAR(15)   NOT NULL DEFAULT '',
  alamat      TEXT          NOT NULL DEFAULT '',
  id_member   INT           NULL,
  role        ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- 4. Jalankan setup_admin.php untuk membuat akun admin pertama
