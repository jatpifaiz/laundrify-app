<?php
require_once __DIR__ . '/../../config/db.php';
$active     = 'member';
$page_title = 'Tambah Member';
include __DIR__ . '/../../config/header.php';
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Tambah Member</h2>
    <p>Daftarkan pelanggan baru ke sistem.</p>
  </div>
  <a href="index.php" class="btn btn-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
    Kembali
  </a>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
  Semua field wajib diisi.
</div>
<?php endif; ?>

<div class="card form-wrapper">
  <div class="card-body">
    <form action="process_add.php" method="POST" data-validate>

      <div class="form-group">
        <label for="nama">Nama Lengkap <span class="req">*</span></label>
        <input type="text" id="nama" name="nama" class="form-control"
               placeholder="Contoh: Budi Santoso"
               data-required data-err-msg="Nama wajib diisi.">
        <span class="error-msg"></span>
      </div>

      <div class="form-group">
        <label for="no_hp">No HP <span class="req">*</span></label>
        <input type="text" id="no_hp" name="no_hp" class="form-control"
               placeholder="Contoh: 08123456789"
               data-required data-type="phone">
        <span class="error-msg"></span>
      </div>

      <div class="form-group">
        <label for="alamat">Alamat <span class="req">*</span></label>
        <textarea id="alamat" name="alamat" class="form-control"
                  placeholder="Jl. Merdeka No. 1, Bandung"
                  data-required data-err-msg="Alamat wajib diisi."></textarea>
        <span class="error-msg"></span>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan Member</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>

    </form>
  </div>
</div>

<?php include __DIR__ . '/../../config/footer.php'; ?>
