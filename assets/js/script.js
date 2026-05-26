/* ============================================
   LAUNDRIFY — script.js
   Validation, Interactivity, Animations
   ============================================ */

// ---- Toast Notification ----
function showToast(message, type = "success") {
  let container = document.querySelector(".toast-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "toast-container";
    document.body.appendChild(container);
  }

  const icons = {
    success: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="toast-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    danger: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="toast-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>`,
  };

  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.innerHTML = `${icons[type] || icons.success} <span>${message}</span>`;
  container.appendChild(toast);

  setTimeout(() => {
    toast.classList.add("hide");
    toast.addEventListener("animationend", () => toast.remove());
  }, 3000);
}

// ---- Auto show toast from URL params ----
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  if (params.get("sukses") === "tambah")
    showToast("Data berhasil ditambahkan!");
  if (params.get("sukses") === "edit") showToast("Data berhasil diperbarui!");
  if (params.get("sukses") === "hapus")
    showToast("Data berhasil dihapus!", "danger");
  if (params.get("error")) showToast("Terjadi kesalahan, coba lagi.", "danger");
});

// ---- Sidebar Toggle (Mobile) ----
const hamburger = document.getElementById("hamburger");
const sidebar = document.querySelector(".sidebar");
const overlay = document.getElementById("sidebar-overlay");

if (hamburger) {
  hamburger.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    overlay.classList.toggle("show");
  });
}

if (overlay) {
  overlay.addEventListener("click", () => {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
  });
}

// ---- Topbar Date ----
const dateEl = document.getElementById("topbar-date");
if (dateEl) {
  const now = new Date();
  dateEl.textContent = now.toLocaleDateString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  });
}

// ---- Form Validation ----
function validateForm(formEl) {
  let valid = true;

  formEl.querySelectorAll("[data-required]").forEach((field) => {
    const errorEl = field.parentElement.querySelector(".error-msg");
    field.classList.remove("error", "success");

    if (!field.value.trim()) {
      field.classList.add("error");
      if (errorEl)
        errorEl.textContent = field.dataset.errMsg || "Field ini wajib diisi.";
      valid = false;
    } else {
      // Extra validations
      if (field.dataset.type === "phone") {
        const phoneRegex = /^(\+62|62|0)[0-9]{8,13}$/;
        if (!phoneRegex.test(field.value.replace(/[\s-]/g, ""))) {
          field.classList.add("error");
          if (errorEl) errorEl.textContent = "Format nomor HP tidak valid.";
          valid = false;
          return;
        }
      }
      if (field.dataset.type === "number") {
        if (isNaN(field.value) || parseFloat(field.value) <= 0) {
          field.classList.add("error");
          if (errorEl) errorEl.textContent = "Harus berupa angka positif.";
          valid = false;
          return;
        }
      }
      field.classList.add("success");
    }
  });

  return valid;
}

// Attach validation to all forms with data-validate
document.querySelectorAll("form[data-validate]").forEach((form) => {
  // Real-time validation on blur
  form.querySelectorAll("[data-required]").forEach((field) => {
    field.addEventListener("blur", () => {
      const errorEl = field.parentElement.querySelector(".error-msg");
      field.classList.remove("error", "success");

      if (!field.value.trim()) {
        field.classList.add("error");
        if (errorEl)
          errorEl.textContent =
            field.dataset.errMsg || "Field ini wajib diisi.";
      } else {
        field.classList.add("success");
        if (errorEl) errorEl.textContent = "";
      }
    });

    field.addEventListener("input", () => {
      if (field.classList.contains("error") && field.value.trim()) {
        field.classList.remove("error");
        field.classList.add("success");
        const errorEl = field.parentElement.querySelector(".error-msg");
        if (errorEl) errorEl.textContent = "";
      }
    });
  });

  form.addEventListener("submit", (e) => {
    if (!validateForm(form)) {
      e.preventDefault();
      showToast("Mohon lengkapi semua field yang wajib diisi.", "danger");
    }
  });
});

// ---- Modal Konfirmasi Hapus ----
const modalOverlay = document.getElementById("modal-hapus");
let pendingHapusUrl = null;

document.querySelectorAll(".btn-hapus").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    pendingHapusUrl = btn.href || btn.dataset.href;
    if (modalOverlay) {
      modalOverlay.classList.add("show");
    }
  });
});

const btnBatalHapus = document.getElementById("btn-batal-hapus");
const btnKonfirmHapus = document.getElementById("btn-konfirm-hapus");

if (btnBatalHapus) {
  btnBatalHapus.addEventListener("click", () => {
    modalOverlay.classList.remove("show");
    pendingHapusUrl = null;
  });
}

if (btnKonfirmHapus) {
  btnKonfirmHapus.addEventListener("click", () => {
    if (pendingHapusUrl) window.location.href = pendingHapusUrl;
  });
}

if (modalOverlay) {
  modalOverlay.addEventListener("click", (e) => {
    if (e.target === modalOverlay) {
      modalOverlay.classList.remove("show");
    }
  });
}

// ---- Hitung Total Transaksi Otomatis ----
const beratInput = document.getElementById("berat_kg");
const layananSelect = document.getElementById("id_layanan");
const totalInput = document.getElementById("total_harga");
const calcPreview = document.getElementById("calc-preview");

function hitungTotal() {
  if (!beratInput || !layananSelect) return;
  const berat = parseFloat(beratInput.value) || 0;
  const option = layananSelect.options[layananSelect.selectedIndex];
  const harga = parseFloat(option?.dataset.harga) || 0;
  const total = berat * harga;

  if (calcPreview) {
    if (berat > 0 && harga > 0) {
      calcPreview.classList.add("show");
      document.getElementById("preview-berat").textContent = berat + " kg";
      document.getElementById("preview-harga").textContent =
        "Rp " + harga.toLocaleString("id-ID");
      document.getElementById("preview-total").textContent =
        "Rp " + total.toLocaleString("id-ID");
    } else {
      calcPreview.classList.remove("show");
    }
  }

  if (totalInput) {
    totalInput.value = total;
    totalInput.dispatchEvent(new Event("input"));
  }
}

if (beratInput) beratInput.addEventListener("input", hitungTotal);
if (layananSelect) layananSelect.addEventListener("change", hitungTotal);

// ---- Live Search Table ----
const searchInput = document.getElementById("search-input");
if (searchInput) {
  searchInput.addEventListener("input", () => {
    const q = searchInput.value.toLowerCase();
    document.querySelectorAll("tbody tr").forEach((row) => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(q) ? "" : "none";
    });

    const visibleRows = document.querySelectorAll(
      'tbody tr:not([style*="none"])',
    );
    const emptyMsg = document.getElementById("empty-search");
    if (emptyMsg) {
      emptyMsg.style.display = visibleRows.length === 0 ? "block" : "none";
    }
  });
}

// ---- Animasi Counter (Dashboard) ----
function animateCounter(el) {
  const target = parseInt(el.dataset.target, 10);
  const duration = 800;
  const start = performance.now();

  function update(time) {
    const progress = Math.min((time - start) / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.round(eased * target).toLocaleString("id-ID");
    if (progress < 1) requestAnimationFrame(update);
  }

  requestAnimationFrame(update);
}

document.querySelectorAll("[data-counter]").forEach((el) => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animateCounter(el);
        observer.disconnect();
      }
    });
  });
  observer.observe(el);
});

// ---- Staggered row animation ----
document.querySelectorAll("tbody tr").forEach((row, i) => {
  row.style.animationDelay = `${i * 0.04}s`;
  row.style.animation = "fadeUp 0.3s ease both";
});
