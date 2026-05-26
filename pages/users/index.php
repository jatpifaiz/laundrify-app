<?php
$page_title = 'Pengguna';
$active     = 'users';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/header.php';
require_once __DIR__ . '/../../config/membership.php';

$search = trim($_GET['q'] ?? '');
$esc    = mysqli_real_escape_string($koneksi, $search);
$where  = $search ? "WHERE u.nama LIKE '%$esc%' OR u.email LIKE '%$esc%'" : '';

$result = mysqli_query($koneksi,
    "SELECT u.id, u.nama, u.email, u.no_hp, u.membership, u.membership_request, u.created_at
     FROM users u
     $where
     ORDER BY u.membership_request DESC, u.created_at DESC"
);

$pending_count = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM users WHERE membership_request='pending'"))[0] ?? 0;
?>

<div class="page-header">
  <div>
    <h2 class="page-heading">Pengguna</h2>
    <p style="color:var(--text-muted);font-size:.85rem;margin-top:2px;">
      Kelola akun pelanggan dan status membership
      <?php if ($pending_count > 0): ?>
        — <span style="color:#d97706;font-weight:600;"><?= $pending_count ?> permintaan upgrade menunggu</span>
      <?php endif; ?>
    </p>
  </div>
</div>

<?php if (isset($_GET['sukses'])): ?>
<div class="alert alert-success">
  <?php
  $msgs = [
    'upgrade'  => 'Membership berhasil diperbarui.',
    'approved' => 'Permintaan upgrade disetujui.',
    'rejected' => 'Permintaan upgrade ditolak.',
  ];
  echo htmlspecialchars($msgs[$_GET['sukses']] ?? 'Berhasil.');
  ?>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <span class="card-title">Daftar Pengguna</span>
    <form method="GET" style="display:flex;gap:8px;align-items:center;">
      <div class="search-bar">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
        </svg>
        <input type="text" name="q" placeholder="Cari nama / email…" value="<?= htmlspecialchars($search) ?>">
      </div>
      <button type="submit" class="btn btn-secondary" style="padding:7px 16px;font-size:.82rem;white-space:nowrap;">Cari</button>
    </form>
  </div>

  <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email</th>
          <th>No HP</th>
          <th>Membership</th>
          <th>Bergabung</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php if (mysqli_num_rows($result) === 0): ?>
        <tr>
          <td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px;">
            Tidak ada pengguna ditemukan.
          </td>
        </tr>
      <?php else: ?>
        <?php $no = 1; while ($u = mysqli_fetch_assoc($result)): ?>
        <?php $tier = getTier($u['membership']); $isPending = $u['membership_request'] === 'pending'; ?>
        <tr <?= $isPending ? 'style="background:linear-gradient(to right,#fffbeb,transparent);"' : '' ?>>
          <td data-label="#"><?= $no++ ?></td>
          <td data-label="Nama"><strong><?= htmlspecialchars($u['nama']) ?></strong></td>
          <td data-label="Email"><?= htmlspecialchars($u['email']) ?></td>
          <td data-label="No HP"><?= htmlspecialchars($u['no_hp']) ?></td>
          <td data-label="Membership">
            <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-start;">
              <span class="badge <?= $tier['badge_class'] ?>"><?= $tier['label'] ?></span>
              <?php if ($isPending): ?>
                <span class="badge" style="background:#fef3c7;color:#92400e;font-size:.7rem;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Minta Upgrade
                </span>
              <?php endif; ?>
            </div>
          </td>
          <td data-label="Bergabung"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
          <td data-label="Aksi">
            <?php if ($isPending && $u['membership'] === 'reguler'): ?>
              <!-- Approve / Reject request -->
              <div style="display:flex;gap:6px;align-items:center;">
                <form method="POST" action="approve_membership.php" style="margin:0;">
                  <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                  <button type="submit" name="action" value="approve"
                    class="btn btn-primary" style="padding:5px 12px;font-size:.78rem;gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Setujui
                  </button>
                  <button type="submit" name="action" value="reject"
                    class="btn btn-secondary" style="padding:5px 12px;font-size:.78rem;color:var(--danger);border-color:#fecaca;">
                    Tolak
                  </button>
                </form>
              </div>
            <?php else: ?>
              <!-- Toggle membership -->
              <form method="POST" action="update_membership.php" style="margin:0;">
                <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                <div class="membership-toggle">
                  <button type="submit" name="membership" value="reguler"
                    class="toggle-btn <?= $u['membership']==='reguler' ? 'active-reguler' : '' ?>">
                    Reguler
                  </button>
                  <button type="submit" name="membership" value="member"
                    class="toggle-btn <?= $u['membership']==='member' ? 'active-member' : '' ?>">
                    Member
                  </button>
                </div>
              </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../../config/footer.php'; ?>
