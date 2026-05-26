<?php require_once __DIR__ . '/../../config/db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Member</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <header>
    <h1>Laundrify</h1>
    <nav>
      <a href="../dashboard.php">Dashboard</a>
      <a href="index.php" class="aktif">Member</a>
    </nav>
  </header>

  <main>
    <section class="form-wrapper">
      <h2>Tambah Member Baru</h2>

      <?php if (isset($_GET['error'])): ?>
        <p class="pesan-error">Semua field wajib diisi!</p>
      <?php endif; ?>

      <form action="process_add.php" method="POST">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" placeholder="Contoh: Budi Santoso" required>

        <label for="no_hp">No HP</label>
        <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 08123456789" required>

        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3" placeholder="Jl. Merdeka No. 1, Bandung" required></textarea>

        <div class="form-aksi">
          <a href="index.php" class="btn-batal">Batal</a>
          <button type="submit" class="btn-simpan">Simpan</button>
        </div>
      </form>
    </section>
  </main>

  <script src="../../assets/js/script.js"></script>
</body>
</html>