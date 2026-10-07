document.body.addEventListener("htmx:afterSwap", function (evt) {
  // Aktifkan semua tombol
  // $(".btn_action").removeClass("disabled").prop("disabled", false);
  // if (evt.target.id === "content_htmx") {
  //   // cari semua table di dalam content HTMX
  //   initTables(evt.target);
  // }
});

// document.body.addEventListener('htmx:responseError', function (e) {

//     if (e.detail.xhr.status === 404) {
//         console.log('Halaman tidak ditemukan');
//         return;
//     }

//     return;
// });

document.body.addEventListener("submit", function (evt) {
  const form = evt.target;

  if (form.id !== "form_datas") return;

  evt.preventDefault(); // stop submit normal

  // 1. Bootstrap validation
  if (!form.checkValidity()) {
    form.classList.add("was-validated");
    return;
  }

  // 🔥 ambil semua data form
  const formData = new FormData(form);

  // 🔥 convert ke object biar enak dibaca
  const data = Object.fromEntries(formData.entries());

  console.log("DATA FORM:", data);

  // 2. Confirm
  swall(form.dataset.title, form.dataset.text, form.dataset.icon, {
    showCancelButton: true,
    confirmButtonText: form.dataset.confirm,
    cancelButtonText: form.dataset.cancel,
  }).then((result) => {
    if (!result.isConfirmed) return;

    htmx.ajax(form.dataset.method || "POST", form.action, {
      source: form,
      target: form.dataset.target || "#respon_htmx",
      swap: "innerHTML",
    });
  });
});

document.body.addEventListener("htmx:confirm", function (e) {
  const btn = e.target;

  if (!btn.classList.contains("btn_confirm")) {
    return;
  }
  $("#respon_htmx")
    .html(`<button class="btn btn-secondary" type="button" disabled>
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Please Wait....!
        </button>`);
  e.preventDefault();
  const title = btn.dataset.title || "Hapus data ?";
  const text =
    btn.dataset.text || "Data yang sudah dihapus tidak dapat dikembalikan.";
  const icon = btn.dataset.icon || "question";
  const confirm =
    btn.dataset.confirm || "Ya, Hapus <i class='bi bi-trash'></i>";
  const cancel =
    btn.dataset.cancel || "<i class='bi bi-arrow-bar-left'></i> Batal";
  Swal.fire({
    title,
    text,
    icon,
    showCancelButton: true,
    confirmButtonText: confirm,
    cancelButtonText: cancel,
    confirmButtonColor: "#dc3545",

    reverseButtons: true,
    focusCancel: true,
  }).then((result) => {
    if (result.isConfirmed) {
      e.detail.issueRequest(true);
    } else {
      hapus_semua_select_table();
    }
  });
});

function openHtmxModal(options = {}) {
  let modalEl = document.getElementById("globalModal");
  let dialog = document.getElementById("globalModalDialog");

  // ===== CONFIG DEFAULT =====
  let config = {
    url: options.url || "",
    title: options.title || "Form",
    size: options.size || "", // sm | lg | xl | ''
    static: options.static || false,
    id: options.id || null,
    mode: options.mode || null,
  };

  // ===== SIZE HANDLER =====
  dialog.classList.remove("modal-sm", "modal-lg", "modal-xl");
  if (config.size) {
    dialog.classList.add("modal-" + config.size);
  }

  // ===== TITLE =====
  document.getElementById("globalModalTitle").innerText = config.title;

  // ===== LOAD HTMX CONTENT =====
  document.getElementById("globalModalBody").innerHTML = `
        <div class="text-center p-3">
            <div class="spinner-border"></div>
        </div>
    `;

  htmx.ajax("GET", config.url, {
    target: "#globalModalBody",
    swap: "innerHTML",
    values: {
      param: config.id,
      mode: config.mode,
    },
  });

  // ===== BOOTSTRAP MODAL =====
  let modal = bootstrap.Modal.getOrCreateInstance(modalEl, {
    backdrop: config.static ? "static" : true,
    keyboard: !config.static,
  });

  modal.show();
}
