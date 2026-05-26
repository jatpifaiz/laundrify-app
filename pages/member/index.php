<?php
require_once __DIR__ . '/../../config/db.php';

// Menentukan konfigurasi halaman untuk dibaca oleh header.php
$page_title = 'Data Pelanggan';
$active     = 'member';

// 1. PANGGIL HEADER (Aset CSS, JS, dan Sidebar otomatis terikat dengan aman)
include __DIR__ . '/../../config/header.php';

// Ambil data + status membership dari users (LEFT JOIN karena bisa saja belum punya akun)
$result = mysqli_query($koneksi,
    "SELECT m.*, u.membership
     FROM member m
     LEFT JOIN users u ON u.id_member = m.id
     ORDER BY m.created_at DESC"
);
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Data Pelanggan</h2>
    <p>Kelola data pelanggan laundry terdaftar.</p>
  </div>
  <a href="add.php" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Tambah Member
  </a>
</div>


<div class="table-controls">
  <div class="search-bar">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
    <input type="text" id="search-input" placeholder="Cari nama, no HP, alamat...">
  </div>
  <span class="info-total">Total: <strong><?= mysqli_num_rows($result) ?></strong> member</span>
</div>

<?php if (mysqli_num_rows($result) === 0): ?>
  <div class="table-wrapper">
    <div class="empty-state">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
      <h3>Belum ada member</h3>
      <p>Tambahkan member pertama kamu.</p>
      <a href="add.php" class="btn btn-primary">+ Tambah Member</a>
    </div>
  </div>
<?php else: ?>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>No HP</th>
          <th>Alamat</th>
          <th>Tgl Daftar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td data-label="#"><?= $no++ ?></td>
          <td data-label="Nama">
            <span style="display:inline-flex;align-items:center;gap:6px;">
              <strong><?= htmlspecialchars($row['nama']) ?></strong>
              <?php if (($row['membership'] ?? '') === 'member'): ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#7c3aed" title="Member">
                <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
              </svg>
              <?php endif; ?>
            </span>
          </td>
          <td data-label="No HP"><?= htmlspecialchars($row['no_hp']) ?></td>
          <td data-label="Alamat"><?= htmlspecialchars($row['alamat']) ?></td>
          <td data-label="Tgl Daftar"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
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

<?php 
// 2. PERBAIKAN SINTAKS INCLUDE FOOTER
include __DIR__ . '/../../config/footer.php'; 
?>