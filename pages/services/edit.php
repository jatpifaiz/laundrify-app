<?php
require_once __DIR__ . '/../../config/db.php';

$id     = (int) ($_GET['id'] ?? 0);
$result = mysqli_query($koneksi, "SELECT * FROM layanan WHERE id = $id");
$data   = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php");
    exit;
}

$active     = 'layanan';
$page_title = 'Edit Layanan';
include __DIR__ . '/../../config/header.php';
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Edit Layanan</h2>
    <p>Perbarui data jenis layanan.</p>
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
    <form action="proccess_edit.php" method="POST" data-validate>
      <input type="hidden" name="id" value="<?= $data['id'] ?>">

      <div class="form-group">
        <label for="nama_layanan">Nama Layanan <span class="req">*</span></label>
        <input type="text" id="nama_layanan" name="nama_layanan" class="form-control"
               value="<?= htmlspecialchars($data['nama_layanan']) ?>"
               data-required data-err-msg="Nama layanan wajib diisi.">
        <span class="error-msg"></span>
      </div>

      <div class="form-group">
        <label for="harga_per_kg">Harga per kg (Rp) <span class="req">*</span></label>
        <input type="number" id="harga_per_kg" name="harga_per_kg" class="form-control"
               value="<?= $data['harga_per_kg'] ?>" min="1"
               data-required data-type="number" data-err-msg="Harga wajib diisi.">
        <span class="error-msg"></span>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>

    </form>
  </div>
</div>

<?php include __DIR__ . '/../../config/footer.php'; ?>
