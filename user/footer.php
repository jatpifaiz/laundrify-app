  </div><!-- end .user-main -->
</div><!-- end .user-layout -->

<!-- Modal Konfirmasi Hapus -->
<div class="modal-overlay" id="modal-hapus">
  <div class="modal">
    <div class="modal-icon">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
    </div>
    <h3>Batalkan Pesanan?</h3>
    <p>Pesanan dengan status <strong>Antri</strong> ini akan dibatalkan secara permanen.</p>
    <div class="modal-actions">
      <button class="btn btn-secondary" id="btn-batal-hapus">Tidak</button>
      <button class="btn btn-danger" id="btn-konfirm-hapus">Ya, Batalkan</button>
    </div>
  </div>
</div>

<script src="<?= $base_url ?>assets/js/script.js"></script>
<script>
(function () {
  var toggle = document.getElementById('user-nav-toggle');
  var links  = document.getElementById('user-nav-links');
  if (!toggle || !links) return;
  toggle.addEventListener('click', function (e) {
    e.stopPropagation();
    var open = links.classList.toggle('open');
    toggle.setAttribute('aria-expanded', open);
    toggle.innerHTML = open
      ? '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>'
      : '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>';
  });
  document.addEventListener('click', function () {
    links.classList.remove('open');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>';
  });
  links.addEventListener('click', function (e) { e.stopPropagation(); });
})();
</script>
<?php if (!empty($extra_scripts)) echo $extra_scripts; ?>
</body>
</html>
