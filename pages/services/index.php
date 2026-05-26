<?php
require_once __DIR__ . '/../../config/db.php';
$active     = 'layanan';
$page_title = 'Data Layanan';
include __DIR__ . '/../../config/header.php';

$result = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id DESC");
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Data Layanan</h2>
    <p>Kelola jenis layanan dan harga laundry.</p>
  </div>
  <a href="add.php" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Tambah Layanan
  </a>
</div>

<div class="table-controls">
  <div class="search-bar">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
    <input type="text" id="search-input" placeholder="Cari nama layanan...">
  </div>
  <span class="info-total">Total: <strong><?= mysqli_num_rows($result) ?></strong> layanan</span>
</div>

<?php if (mysqli_num_rows($result) === 0): ?>
<div class="table-wrapper">
  <div class="empty-state">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
    <h3>Belum ada layanan</h3>
    <p>Tambahkan jenis layanan pertama kamu.</p>
    <a href="add.php" class="btn btn-primary">+ Tambah Layanan</a>
  </div>
</div>
<?php else: ?>
<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Layanan</th>
        <th>Harga / kg</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td data-label="#"><?= $no++ ?></td>
        <td data-label="Nama Layanan"><strong><?= htmlspecialchars($row['nama_layanan']) ?></strong></td>
        <td data-label="Harga / kg">Rp <?= number_format($row['harga_per_kg'], 0, ',', '.') ?></td>
        <td data-label="Aksi">
          <div style="display:flex;gap:6px">
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
            <a href="delete.php?id=<?= $row['id'] ?>" class="btn-hapus">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
              Hapus
            </a>
          </div>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <div id="empty-search" style="display:none" class="empty-state">
    <p>Tidak ada data yang cocok dengan pencarian.</p>
  </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../../config/footer.php'; ?>
