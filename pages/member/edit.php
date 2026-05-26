<?php
require_once __DIR__ . '/../../config/db.php';

$id    = (int) $_GET['id'];
$query = "SELECT * FROM member WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$data  = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Member</title>
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
      <h2>Edit Member</h2>

      <form action="process_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama"
               value="<?= htmlspecialchars($data['nama']) ?>" required>

        <label for="no_hp">No HP</label>
        <input type="text" id="no_hp" name="no_hp"
               value="<?= htmlspecialchars($data['no_hp']) ?>" required>

        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($data['alamat']) ?></textarea>

        <div class="form-aksi">
          <a href="index.php" class="btn-batal">Batal</a>
          <button type="submit" class="btn-simpan">Update</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>