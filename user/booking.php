<?php
$user_active = 'booking';
$page_title  = 'Booking Baru';
include __DIR__ . '/header.php';
require_once __DIR__ . '/../config/membership.php';

$membership = $_SESSION['membership'] ?? 'reguler';
$tier       = getTier($membership);

$layanans = mysqli_query($koneksi, "SELECT id, nama_layanan, harga_per_kg FROM layanan ORDER BY nama_layanan ASC");

$disc_default = $tier['disc_default'];
$disc_bulk    = $tier['disc_bulk'];
$bulk_min     = $tier['bulk_min_kg'];

$extra_scripts = <<<JS
<script>
(function () {
  const membership   = '$membership';
  const discDefault  = $disc_default;
  const discBulk     = $disc_bulk;
  const bulkMin      = $bulk_min;

  const selLayanan   = document.getElementById('id_layanan');
  const inpBerat     = document.getElementById('berat_kg');
  const hiddenDasar  = document.getElementById('harga_dasar');
  const hiddenDiskon = document.getElementById('diskon_persen');
  const hiddenTotal  = document.getElementById('total_harga');

  const rBerat   = document.getElementById('preview-berat');
  const rHarga   = document.getElementById('preview-harga');
  const rDasar   = document.getElementById('preview-dasar');
  const rDiskon  = document.getElementById('preview-diskon');
  const rTotal   = document.getElementById('preview-total');

  const rowDasar  = document.getElementById('row-dasar');
  const rowDiskon = document.getElementById('row-diskon');

  function fmt(n) {
    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
  }

  function calc() {
    const hargaPerKg = parseFloat(selLayanan.selectedOptions[0]?.dataset?.harga || 0);
    const berat      = parseInt(inpBerat.value) || 0;

    rBerat.textContent = berat > 0 ? berat + ' kg' : '—';
    rHarga.textContent = hargaPerKg > 0 ? fmt(hargaPerKg) + '/kg' : '—';

    if (!hargaPerKg || !berat) {
      rDasar.textContent  = '—';
      rDiskon.textContent = '—';
      rTotal.textContent  = '—';
      hiddenDasar.value   = 0;
      hiddenDiskon.value  = 0;
      hiddenTotal.value   = 0;
      return;
    }

    const dasar  = hargaPerKg * berat;
    const persen = (discDefault === 0) ? 0 : (berat >= bulkMin && discBulk > 0 ? discBulk : discDefault);
    const diskon = Math.round(dasar * persen / 100);
    const total  = dasar - diskon;

    rDasar.textContent  = fmt(dasar);
    hiddenDasar.value   = Math.round(dasar);
    hiddenDiskon.value  = persen;
    hiddenTotal.value   = Math.round(total);

    if (persen > 0) {
      rowDasar.style.display  = '';
      rowDiskon.style.display = '';
      rDiskon.textContent = '−' + persen + '% (' + fmt(diskon) + ')';
      rTotal.textContent  = fmt(total);
    } else {
      rowDasar.style.display  = 'none';
      rowDiskon.style.display = 'none';
      rTotal.textContent      = fmt(total);
    }
  }

  selLayanan.addEventListener('change', calc);
  inpBerat.addEventListener('input', calc);
  calc();
})();
</script>
JS;
?>

<div class="page-header">
  <div class="page-header-text">
    <h2>Booking Baru</h2>
    <p>Isi detail pesanan laundry Anda.</p>
  </div>
  <span class="badge <?= $tier['badge_class'] ?>" style="font-size:.8rem;padding:5px 12px;">
    <?= $tier['label'] ?> — <?= $tier['desc'] ?>
  </span>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
  Semua field wajib diisi dengan benar.
</div>
<?php endif; ?>

<div class="card" style="max-width:600px;">
  <div class="card-body">
    <form action="<?= $base_url ?>user/proccess_booking.php" method="POST" data-validate>

      <div class="form-group">
        <label for="id_layanan">Jenis Layanan <span class="req">*</span></label>
        <select id="id_layanan" name="id_layanan" class="form-control"
                data-required data-err-msg="Pilih layanan terlebih dahulu.">
          <option value="">-- Pilih Layanan --</option>
          <?php while ($l = mysqli_fetch_assoc($layanans)): ?>
          <option value="<?= $l['id'] ?>" data-harga="<?= $l['harga_per_kg'] ?>">
            <?= htmlspecialchars($l['nama_layanan']) ?> — Rp <?= number_format($l['harga_per_kg'], 0, ',', '.') ?>/kg
          </option>
          <?php endwhile; ?>
        </select>
        <span class="error-msg"></span>
      </div>

      <div class="form-group">
        <label for="berat_kg">Estimasi Berat (kg) <span class="req">*</span></label>
        <input type="number" id="berat_kg" name="berat_kg" class="form-control"
               placeholder="Contoh: 3" min="1" step="1"
               data-required data-type="number" data-err-msg="Berat wajib diisi.">
        <span class="error-msg"></span>
        <span class="form-hint">Estimasi berat cucian. Petugas akan menimbang ulang saat antar.</span>
      </div>

      <!-- Preview kalkulasi otomatis -->
      <div class="calc-preview" id="calc-preview">
        <div class="calc-row">
          <span>Estimasi Berat</span><span id="preview-berat">—</span>
        </div>
        <div class="calc-row">
          <span>Harga / kg</span><span id="preview-harga">—</span>
        </div>
        <div class="calc-row" id="row-dasar" style="display:none;">
          <span>Harga Dasar</span><span id="preview-dasar">—</span>
        </div>
        <div class="calc-row" id="row-diskon" style="display:none;color:var(--success);">
          <span>Diskon Member</span><span id="preview-diskon">—</span>
        </div>
        <div class="calc-row total">
          <span>Estimasi Total</span><span id="preview-total">—</span>
        </div>
      </div>

      <input type="hidden" id="total_harga"  name="total_harga"  value="0">
      <input type="hidden" id="harga_dasar"  name="harga_dasar"  value="0">
      <input type="hidden" id="diskon_persen" name="diskon_persen" value="0">

      <div class="form-row" style="margin-top:4px;">
        <div class="form-group">
          <label for="tgl_masuk">Tanggal Antar <span class="req">*</span></label>
          <input type="date" id="tgl_masuk" name="tgl_masuk" class="form-control"
                 value="<?= date('Y-m-d') ?>"
                 data-required data-err-msg="Tanggal wajib diisi.">
          <span class="error-msg"></span>
        </div>
        <div class="form-group">
          <label for="tgl_selesai">Estimasi Selesai</label>
          <input type="date" id="tgl_selesai" name="tgl_selesai" class="form-control">
          <span class="form-hint">Opsional.</span>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Kirim Booking</button>
        <a href="<?= $base_url ?>user/index.php" class="btn btn-secondary">Batal</a>
      </div>

    </form>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
