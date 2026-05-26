<?php
require_once __DIR__ . '/../../config/db.php';

$members  = mysqli_query($koneksi, "SELECT id, nama FROM member ORDER BY nama ASC");
$layanans = mysqli_query($koneksi, "SELECT id, nama_layanan, harga_per_kg FROM layanan ORDER BY nama_layanan ASC");

$active     = 'transaksi';
$page_title = 'Tambah Transaksi';
include __DIR__ . '/../../config/header.php';
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Tambah Transaksi</h2>
    <p>Catat transaksi laundry baru.</p>
  </div>
  <a href="index.php" class="btn btn-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
    Kembali
  </a>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
  Semua field wajib diisi dengan benar.
</div>
<?php endif; ?>

<div class="card form-wrapper">
  <div class="card-body">
    <form action="proccess_add.php" method="POST" data-validate>

      <div class="form-group">
        <label for="id_member">Member <span class="req">*</span></label>
        <select id="id_member" name="id_member" class="form-control"
                data-required data-err-msg="Pilih member terlebih dahulu.">
          <option value="">-- Pilih Member --</option>
          <?php while ($m = mysqli_fetch_assoc($members)): ?>
          <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nama']) ?></option>
          <?php endwhile; ?>
        </select>
        <span class="error-msg"></span>
      </div>

      <div class="form-group">
        <label for="id_layanan">Layanan <span class="req">*</span></label>
        <select id="id_layanan" name="id_layanan" class="form-control"
                data-required data-err-msg="Pilih layanan terlebih dahulu.">
          <option value="">-- Pilih Layanan --</option>
          <?php while ($l = mysqli_fetch_assoc($layanans)): ?>
          <option value="<?= $l['id'] ?>" data-harga="<?= $l['harga_per_kg'] ?>">
            <?= htmlspecialchars($l['nama_layanan']) ?> — Rp <?= number_format($l['harga_per_kg'], 0, ',', '.') ?>/kg
          </option>
          <?php endwhile; ?>
        </select>
        <span class="error-msg"></span>
      </div>

      <div class="form-group">
        <label for="berat_kg">Berat (kg) <span class="req">*</span></label>
        <input type="number" id="berat_kg" name="berat_kg" class="form-control"
               placeholder="Contoh: 3" min="1" step="1"
               data-required data-type="number" data-err-msg="Berat wajib diisi.">
        <span class="error-msg"></span>
      </div>

      <!-- Preview kalkulasi otomatis -->
      <div class="calc-preview" id="calc-preview">
        <div class="calc-row">
          <span>Berat</span>
          <span id="preview-berat">—</span>
        </div>
        <div class="calc-row">
          <span>Harga / kg</span>
          <span id="preview-harga">—</span>
        </div>
        <div class="calc-row total">
          <span>Total Harga</span>
          <span id="preview-total">—</span>
        </div>
      </div>

      <input type="hidden" id="total_harga" name="total_harga" value="0">

      <div class="form-row">
        <div class="form-group">
          <label for="tgl_masuk">Tanggal Masuk <span class="req">*</span></label>
          <input type="date" id="tgl_masuk" name="tgl_masuk" class="form-control"
                 value="<?= date('Y-m-d') ?>"
                 data-required data-err-msg="Tanggal masuk wajib diisi.">
          <span class="error-msg"></span>
        </div>
        <div class="form-group">
          <label for="tgl_selesai">Tanggal Selesai</label>
          <input type="date" id="tgl_selesai" name="tgl_selesai" class="form-control">
          <span class="form-hint">Kosongkan jika belum diketahui.</span>
        </div>
      </div>

      <div class="form-group">
        <label for="status">Status <span class="req">*</span></label>
        <select id="status" name="status" class="form-control"
                data-required data-err-msg="Pilih status.">
          <option value="antri" selected>Antri</option>
          <option value="proses">Proses</option>
          <option value="selesai">Selesai</option>
          <option value="diambil">Diambil</option>
        </select>
        <span class="error-msg"></span>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>

    </form>
  </div>
</div>

<?php include __DIR__ . '/../../config/footer.php'; ?>
