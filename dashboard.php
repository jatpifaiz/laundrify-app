<?php
require_once 'config/db.php';

// Stat counts
$total_member     = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM member"))[0]    ?? 0;
$total_layanan    = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM layanan"))[0]   ?? 0;
$total_transaksi  = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi"))[0] ?? 0;
$total_pendapatan = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga), 0) FROM transaksi"))[0] ?? 0;

// Status breakdown
$status_counts = ['antri' => 0, 'proses' => 0, 'selesai' => 0, 'diambil' => 0];
$res_status = mysqli_query($koneksi, "SELECT status, COUNT(*) AS jumlah FROM transaksi GROUP BY status");
while ($row = mysqli_fetch_assoc($res_status)) {
    $status_counts[$row['status']] = (int) $row['jumlah'];
}

// Recent transactions (6 terbaru)
$recent = mysqli_query($koneksi, "
    SELECT t.id, m.nama AS nama_member, l.nama_layanan,
           t.berat_kg, t.total_harga, t.status, t.tgl_masuk
    FROM transaksi t
    JOIN member m ON t.id_member = m.id
    JOIN layanan l ON t.id_layanan = l.id
    ORDER BY t.id DESC
    LIMIT 6
");

$active     = 'dashboard';
$page_title = 'Dashboard';
include 'config/header.php';
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Dashboard</h2>
    <p>Ringkasan data laundry Anda</p>
  </div>
  <a href="/laundrify-app/pages/transaction/add.php" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    Transaksi Baru
  </a>
</div>

<!-- Stat Cards -->
<div class="stats-grid">

  <div class="stat-card">
    <div class="stat-icon blue">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
      </svg>
    </div>
    <div class="stat-value" data-counter data-target="<?= $total_member ?>">0</div>
    <div class="stat-label">Total Member</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon amber">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
      </svg>
    </div>
    <div class="stat-value" data-counter data-target="<?= $total_layanan ?>">0</div>
    <div class="stat-label">Jenis Layanan</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon blue">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
      </svg>
    </div>
    <div class="stat-value" data-counter data-target="<?= $total_transaksi ?>">0</div>
    <div class="stat-label">Total Transaksi</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon green">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
      </svg>
    </div>
    <div class="stat-value">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
    <div class="stat-label">Total Pendapatan</div>
  </div>

</div>

<!-- Status Breakdown + Quick Actions -->
<div class="form-row" style="margin-bottom:28px;">

  <div class="card">
    <div class="card-header">
      <h3>Status Transaksi</h3>
      <a href="/laundrify-app/pages/transaction/index.php" style="font-size:0.8rem;color:var(--text-muted);">Lihat semua &rarr;</a>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
      <?php
        $statuses = [
          'antri'   => ['label' => 'Antri',   'class' => 'badge-antri'],
          'proses'  => ['label' => 'Proses',  'class' => 'badge-proses'],
          'selesai' => ['label' => 'Selesai', 'class' => 'badge-selesai'],
          'diambil' => ['label' => 'Diambil', 'class' => 'badge-diambil'],
        ];
        foreach ($statuses as $key => $s):
          $count = $status_counts[$key];
          $pct   = $total_transaksi > 0 ? round($count / $total_transaksi * 100) : 0;
      ?>
      <div style="display:flex;align-items:center;gap:12px;">
        <span class="badge <?= $s['class'] ?>" style="min-width:72px;justify-content:flex-start;">
          <span class="badge-dot"></span>
          <?= $s['label'] ?>
        </span>
        <div style="flex:1;height:6px;background:var(--border);border-radius:99px;overflow:hidden;">
          <div style="height:100%;width:<?= $pct ?>%;background:var(--accent);border-radius:99px;"></div>
        </div>
        <span style="font-weight:600;font-size:0.9rem;min-width:20px;text-align:right;color:var(--text);"><?= $count ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3>Aksi Cepat</h3>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
      <a href="/laundrify-app/pages/transaction/add.php" class="btn btn-primary" style="justify-content:center;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah Transaksi
      </a>
      <a href="/laundrify-app/pages/member/add.php" class="btn btn-secondary" style="justify-content:center;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766z"/>
        </svg>
        Tambah Member
      </a>
      <a href="/laundrify-app/pages/services/add.php" class="btn btn-secondary" style="justify-content:center;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
        </svg>
        Tambah Layanan
      </a>
      <a href="/laundrify-app/pages/member/index.php" class="btn btn-secondary" style="justify-content:center;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
        </svg>
        Daftar Member
      </a>
    </div>
  </div>

</div>

<!-- Transaksi Terbaru -->
<div class="recent-section">
  <div class="card">
    <div class="card-header">
      <h3>Transaksi Terbaru</h3>
      <a href="/laundrify-app/pages/transaction/index.php" class="btn btn-secondary" style="padding:6px 14px;font-size:0.8rem;">
        Lihat Semua
      </a>
    </div>
    <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
      <?php if (mysqli_num_rows($recent) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Member</th>
            <th>Layanan</th>
            <th>Berat</th>
            <th>Total</th>
            <th>Tgl Masuk</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($recent)): ?>
          <tr>
            <td style="color:var(--text-muted);font-size:0.8rem;"><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_member']) ?></td>
            <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
            <td><?= htmlspecialchars($row['berat_kg']) ?> kg</td>
            <td style="font-weight:500;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            <td style="color:var(--text-muted);font-size:0.82rem;">
              <?= date('d M Y', strtotime($row['tgl_masuk'])) ?>
            </td>
            <td>
              <span class="badge badge-<?= htmlspecialchars($row['status']) ?>">
                <span class="badge-dot"></span>
                <?= ucfirst(htmlspecialchars($row['status'])) ?>
              </span>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
      <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
        </svg>
        <h3>Belum ada transaksi</h3>
        <p>Mulai tambahkan transaksi pertama Anda</p>
        <a href="/laundrify-app/pages/transaction/add.php" class="btn btn-primary">Tambah Transaksi</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'config/footer.php'; ?>
