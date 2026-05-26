-- ============================================
--  LAUNDRIFY — Database Schema
--  Database: laundri_db
-- ============================================

CREATE DATABASE IF NOT EXISTS laundri_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laundri_db;

-- ---- Tabel Member ----
CREATE TABLE IF NOT EXISTS member (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100)  NOT NULL,
    no_hp      VARCHAR(20)   NOT NULL,
    alamat     TEXT          NOT NULL,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---- Tabel Layanan ----
CREATE TABLE IF NOT EXISTS layanan (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(100)   NOT NULL,
    harga_per_kg DECIMAL(10, 2) NOT NULL,
    satuan       ENUM('kg', 'pcs') DEFAULT 'kg',
    deskripsi    TEXT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---- Tabel Transaksi ----
CREATE TABLE IF NOT EXISTS transaksi (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_member       INT UNSIGNED NOT NULL,
    id_layanan      INT UNSIGNED NOT NULL,
    berat_kg        DECIMAL(6, 2) NOT NULL DEFAULT 1.00,
    total_harga     DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    status          ENUM('antri', 'proses', 'selesai', 'diambil') DEFAULT 'antri',
    catatan         TEXT,
    tanggal_masuk   DATE NOT NULL,
    tanggal_selesai DATE,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_member)  REFERENCES member(id)  ON DELETE CASCADE,
    FOREIGN KEY (id_layanan) REFERENCES layanan(id) ON DELETE CASCADE
) ENGINE=InnoDB;
