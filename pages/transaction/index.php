<?php
require_once __DIR__ . '/../../config/db.php';
$active     = 'transaksi';
$page_title = 'Data Transaksi';
include __DIR__ . '/../../config/header.php';

$result = mysqli_query($koneksi, "
    SELECT t.id, m.nama AS nama_member, l.nama_layanan,
           t.berat_kg, t.total_harga, t.status, t.tgl_masuk, t.tgl_selesai
    FROM transaksi t
    JOIN member m ON t.id_member = m.id
    JOIN layanan l ON t.id_layanan = l.id
    ORDER BY t.id DESC
");
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Data Transaksi</h2>
    <p>Kelola semua transaksi laundry.</p>
  </div>
  <a href="add.php" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Tambah Transaksi
  </a>
</div>

<div class="table-controls">
  <div class="search-bar">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
    <input type="text" id="search-input" placeholder="Cari member, layanan, status...">
  </div>
  <span class="info-total">Total: <strong><?= mysqli_num_rows($result) ?></strong> transaksi</span>
</div>

<?php if (mysqli_num_rows($result) === 0): ?>
<div class="table-wrapper">
  <div class="empty-state">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
    <h3>Belum ada transaksi</h3>
    <p>Tambahkan transaksi pertama kamu.</p>
    <a href="add.php" class="btn btn-primary">+ Tambah Transaksi</a>
  </div>
</div>
<?php else: ?>
<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Member</th>
        <th>Layanan</th>
        <th>Berat</th>
        <th>Total</th>
        <th>Tgl Masuk</th>
        <th>Tgl Selesai</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td data-label="#"><?= $no++ ?></td>
        <td data-label="Pelanggan"><strong><?= htmlspecialchars($row['nama_member']) ?></strong></td>
        <td data-label="Layanan"><?= htmlspecialchars($row['nama_layanan']) ?></td>
        <td data-label="Berat"><?= $row['berat_kg'] ?> kg</td>
        <td data-label="Total" style="font-weight:500;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
        <td data-label="Tgl Masuk"><?= date('d/m/Y', strtotime($row['tgl_masuk'])) ?></td>
        <td data-label="Tgl Selesai"><?= $row['tgl_selesai'] ? date('d/m/Y', strtotime($row['tgl_selesai'])) : '—' ?></td>
        <td data-label="Status">
          <span class="badge badge-<?= $row['status'] ?>">
            <span class="badge-dot"></span>
            <?= ucfirst($row['status']) ?>
          </span>
        </td>
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
