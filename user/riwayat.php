<?php
$user_active = 'riwayat';
$page_title  = 'Riwayat Pesanan';
include __DIR__ . '/header.php';

$uid    = (int) $_SESSION['user_id'];
$result = mysqli_query($koneksi, "
    SELECT t.id, l.nama_layanan, t.berat_kg, t.harga_dasar, t.diskon_persen, t.total_harga, t.status, t.tgl_masuk, t.tgl_selesai
    FROM transaksi t
    JOIN layanan l ON t.id_layanan = l.id
    WHERE t.id_user = $uid
    ORDER BY t.id DESC
");
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Riwayat Pesanan</h2>
    <p>Semua riwayat pesanan laundry Anda.</p>
  </div>
  <a href="<?= $base_url ?>user/booking.php" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Booking Baru
  </a>
</div>

<?php if (mysqli_num_rows($result) === 0): ?>
<div class="card">
  <div class="empty-state">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75m-7.5 3.75h15M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z"/></svg>
    <h3>Belum ada pesanan</h3>
    <p>Buat booking laundry pertama Anda sekarang.</p>
    <a href="<?= $base_url ?>user/booking.php" class="btn btn-primary">Buat Booking</a>
  </div>
</div>
<?php else: ?>
<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Layanan</th>
        <th>Berat</th>
        <th>Diskon</th>
        <th>Total</th>
        <th>Tgl Antar</th>
        <th>Est. Selesai</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td data-label="#"><?= $no++ ?></td>
        <td data-label="Layanan"><?= htmlspecialchars($row['nama_layanan']) ?></td>
        <td data-label="Berat"><?= $row['berat_kg'] ?> kg</td>
        <td data-label="Diskon">
          <?php if ($row['diskon_persen'] > 0): ?>
            <span style="color:var(--success);font-weight:500;">−<?= $row['diskon_persen'] ?>%</span>
          <?php else: ?>
            <span style="color:var(--text-light);">—</span>
          <?php endif; ?>
        </td>
        <td data-label="Total" style="font-weight:500;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
        <td data-label="Tgl Antar"><?= date('d M Y', strtotime($row['tgl_masuk'])) ?></td>
        <td data-label="Est. Selesai"><?= $row['tgl_selesai'] ? date('d M Y', strtotime($row['tgl_selesai'])) : '—' ?></td>
        <td data-label="Status">
          <span class="badge badge-<?= $row['status'] ?>">
            <span class="badge-dot"></span>
            <?= ucfirst($row['status']) ?>
          </span>
        </td>
        <td data-label="Aksi">
          <?php if ($row['status'] === 'antri'): ?>
          <a href="cancel_booking.php?id=<?= $row['id'] ?>" class="btn-hapus">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Batalkan
          </a>
          <?php else: ?>
          <span style="font-size:0.8rem;color:var(--text-light);">—</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
