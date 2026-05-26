-- Membership feature migration
-- Run once against laundri_db

ALTER TABLE users
  ADD COLUMN membership ENUM('reguler','member') NOT NULL DEFAULT 'reguler' AFTER role;

ALTER TABLE transaksi
  ADD COLUMN harga_dasar   INT NOT NULL DEFAULT 0 AFTER total_harga,
  ADD COLUMN diskon_persen TINYINT NOT NULL DEFAULT 0 AFTER harga_dasar;
