<?php
$user_active = 'home';
$page_title  = 'Beranda';
include __DIR__ . '/header.php';
require_once __DIR__ . '/../config/membership.php';

$uid  = (int) $_SESSION['user_id'];
$tier = getTier($_SESSION['membership'] ?? 'reguler');

$total_booking   = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi WHERE id_user=$uid"))[0] ?? 0;
$booking_aktif   = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi WHERE id_user=$uid AND status IN ('antri','proses')"))[0] ?? 0;
$booking_selesai = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi WHERE id_user=$uid AND status IN ('selesai','diambil')"))[0] ?? 0;
$total_tagihan   = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga),0) FROM transaksi WHERE id_user=$uid"))[0] ?? 0;

$recent = mysqli_query($koneksi, "
    SELECT t.id, l.nama_layanan, t.berat_kg, t.total_harga, t.status, t.tgl_masuk
    FROM transaksi t
    JOIN layanan l ON t.id_layanan = l.id
    WHERE t.id_user = $uid
    ORDER BY t.id DESC LIMIT 5
");
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>!</h2>
    <p>Pantau dan kelola pesanan laundry Anda di sini.
      <span class="badge <?= $tier['badge_class'] ?>" style="margin-left:6px;font-size:.75rem;"><?= $tier['label'] ?></span>
    </p>
  </div>
  <a href="/laundrify-app/user/booking.php" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Buat Booking
  </a>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
    </div>
    <div class="stat-value" data-counter data-target="<?= $total_booking ?>">0</div>
    <div class="stat-label">Total Pesanan</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-value" data-counter data-target="<?= $booking_aktif ?>">0</div>
    <div class="stat-label">Sedang Diproses</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-value" data-counter data-target="<?= $booking_selesai ?>">0</div>
    <div class="stat-label">Selesai</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
    </div>
    <div class="stat-value">Rp <?= number_format($total_tagihan, 0, ',', '.') ?></div>
    <div class="stat-label">Total Tagihan</div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3>Pesanan Terbaru</h3>
    <a href="/laundrify-app/user/riwayat.php" class="btn btn-secondary" style="padding:6px 14px;font-size:0.8rem;">Lihat Semua</a>
  </div>
  <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
    <?php if (mysqli_num_rows($recent) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Layanan</th>
          <th>Berat</th>
          <th>Total</th>
          <th>Tgl Masuk</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($recent)): ?>
        <tr>
          <td data-label="Layanan"><?= htmlspecialchars($row['nama_layanan']) ?></td>
          <td data-label="Berat"><?= $row['berat_kg'] ?> kg</td>
          <td data-label="Total" style="font-weight:500;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
          <td data-label="Tgl Masuk"><?= date('d M Y', strtotime($row['tgl_masuk'])) ?></td>
          <td data-label="Status"><span class="badge badge-<?= $row['status'] ?>"><span class="badge-dot"></span><?= ucfirst($row['status']) ?></span></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75m-7.5 3.75h15M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z"/></svg>
      <h3>Belum ada pesanan</h3>
      <p>Yuk buat booking laundry pertama Anda!</p>
      <a href="/laundrify-app/user/booking.php" class="btn btn-primary">Buat Booking</a>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
