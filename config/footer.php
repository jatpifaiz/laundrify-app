</main><!-- end .page-body -->
</div><!-- end .main-content -->

<!-- Modal Konfirmasi Hapus -->
<div class="modal-overlay" id="modal-hapus">
  <div class="modal">
    <div class="modal-icon">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
    </div>
    <h3>Hapus Data?</h3>
    <p>Tindakan ini tidak dapat dibatalkan. Data akan dihapus secara permanen.</p>
    <div class="modal-actions">
      <button class="btn btn-secondary" id="btn-batal-hapus">Batal</button>
      <button class="btn btn-danger" id="btn-konfirm-hapus">Ya, Hapus</button>
    </div>
  </div>
</div>

<script src="<?= $js_path ?? '../../assets/js/script.js' ?>"></script>
</body>
</html>