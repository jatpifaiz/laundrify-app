<?php
$user_active = 'profil';
$page_title  = 'Profil Saya';
include __DIR__ . '/header.php';

require_once __DIR__ . '/../config/membership.php';
$uid  = (int) $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id=$uid LIMIT 1"));
$tier = getTier($user['membership'] ?? 'reguler');
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Profil Saya</h2>
    <p>Kelola informasi akun dan alamat pengiriman.</p>
  </div>
</div>

<?php if (isset($_GET['sukses'])): ?>
<div class="alert alert-success" style="margin-bottom:20px;">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  Profil berhasil diperbarui.
</div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger" style="margin-bottom:20px;">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
  <?php
    $err_map = [
      'empty'    => 'Semua field wajib diisi.',
      'wrong_pw' => 'Password lama salah.',
      'mismatch' => 'Konfirmasi password baru tidak cocok.',
      'short_pw' => 'Password baru minimal 6 karakter.',
    ];
    echo htmlspecialchars($err_map[$_GET['error']] ?? 'Terjadi kesalahan.');
  ?>
</div>
<?php endif; ?>

<!-- Membership Status -->
<div class="card" style="max-width:560px;margin-bottom:20px;">
  <div class="card-header"><h3>Status Membership</h3></div>
  <div class="card-body">
    <div style="display:flex;align-items:flex-start;gap:16px;flex-wrap:wrap;">
      <span class="badge <?= $tier['badge_class'] ?>" style="font-size:.95rem;padding:8px 18px;flex-shrink:0;"><?= $tier['label'] ?></span>
      <div style="flex:1;min-width:180px;">
        <p style="margin:0;font-size:.9rem;color:var(--text);font-weight:500;"><?= $tier['desc'] ?></p>
        <?php if ($user['membership'] === 'reguler'): ?>
        <p style="margin:4px 0 0;font-size:.82rem;color:var(--text-muted);">
          Upgrade ke Member untuk mendapat diskon 5% setiap order, atau 10% untuk order ≥ 6 kg.
        </p>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($user['membership'] === 'reguler'): ?>
    <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
      <?php if ($user['membership_request'] === 'pending'): ?>
        <div style="display:flex;align-items:center;gap:10px;background:#fef3c7;border:1px solid #fcd34d;border-radius:var(--radius-sm);padding:10px 14px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#d97706"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span style="font-size:.85rem;color:#92400e;font-weight:500;">Permintaan upgrade sedang ditinjau admin. Kami akan segera menghubungi Anda.</span>
        </div>
      <?php else: ?>
        <form method="POST" action="/laundrify-app/user/proccess_profile.php">
          <input type="hidden" name="action" value="request_membership">
          <button type="submit" class="btn btn-primary" style="gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
            Ajukan Upgrade ke Member
          </button>
          <p style="margin:8px 0 0;font-size:.78rem;color:var(--text-muted);">Permintaan akan ditinjau oleh admin dalam 1×24 jam.</p>
        </form>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Info Akun -->
<div class="card form-wrapper" style="max-width:560px;margin-bottom:20px;">
  <div class="card-header"><h3>Informasi Akun</h3></div>
  <div class="card-body">
    <form action="/laundrify-app/user/proccess_profile.php" method="POST" data-validate>
      <input type="hidden" name="action" value="update_info">

      <div class="form-group">
        <label for="nama">Nama Lengkap <span class="req">*</span></label>
        <input type="text" id="nama" name="nama" class="form-control"
               value="<?= htmlspecialchars($user['nama']) ?>"
               data-required data-err-msg="Nama wajib diisi.">
        <span class="error-msg"></span>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled style="opacity:0.6;">
        <span class="form-hint">Email tidak dapat diubah.</span>
      </div>

      <div class="form-group">
        <label for="no_hp">No HP <span class="req">*</span></label>
        <input type="text" id="no_hp" name="no_hp" class="form-control"
               value="<?= htmlspecialchars($user['no_hp']) ?>"
               data-required data-type="phone">
        <span class="error-msg"></span>
      </div>

      <div class="form-group" style="margin-bottom:0;">
        <label for="alamat">Alamat <span class="req">*</span></label>
        <textarea id="alamat" name="alamat" class="form-control"
                  data-required data-err-msg="Alamat wajib diisi."><?= htmlspecialchars($user['alamat']) ?></textarea>
        <span class="error-msg"></span>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<!-- Ganti Password -->
<div class="card form-wrapper" style="max-width:560px;">
  <div class="card-header"><h3>Ganti Password</h3></div>
  <div class="card-body">
    <form action="/laundrify-app/user/proccess_profile.php" method="POST" data-validate>
      <input type="hidden" name="action" value="change_password">

      <div class="form-group">
        <label for="password_lama">Password Lama <span class="req">*</span></label>
        <input type="password" id="password_lama" name="password_lama" class="form-control"
               placeholder="••••••••"
               data-required data-err-msg="Password lama wajib diisi.">
        <span class="error-msg"></span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password_baru">Password Baru <span class="req">*</span></label>
          <input type="password" id="password_baru" name="password_baru" class="form-control"
                 placeholder="Min. 6 karakter"
                 data-required data-err-msg="Password baru wajib diisi.">
          <span class="error-msg"></span>
        </div>
        <div class="form-group">
          <label for="konfirm_baru">Konfirmasi <span class="req">*</span></label>
          <input type="password" id="konfirm_baru" name="konfirm_baru" class="form-control"
                 placeholder="Ulangi password"
                 data-required data-err-msg="Konfirmasi wajib diisi.">
          <span class="error-msg"></span>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-secondary">Ganti Password</button>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
