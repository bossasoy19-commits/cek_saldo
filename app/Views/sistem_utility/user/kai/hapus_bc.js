let deleteMode = false;

//  btn_hapus_chk click

function mode_hapus(chkCols, btn) {
  chkCols.forEach((el) => el.classList.remove("d-none"));

  btn.innerHTML = `Batal`;

  btn.classList.remove("btn-danger");
  btn.classList.add("btn-secondary");

  document.getElementById("btn_exec_hapus").classList.remove("d-none");
  $(".dataTables_paginate").hide();
  $("#multi-filter-select_length").hide();
}

function mode_batal(chkCols, chkItems, btn) {
  chkCols.forEach((el) => el.classList.add("d-none"));

  chkItems.forEach((cb) => (cb.checked = false));
  document.getElementById("chk_all").checked = false;

  btn.innerHTML = `Hapus ?`;

  btn.classList.remove("btn-secondary");
  btn.classList.add("btn-danger");
  document.getElementById("btn_exec_hapus").classList.add("d-none");
  $(".dataTables_paginate").show();
  $("#multi-filter-select_length").show();
}
document.getElementById("btn_hapus_chk").addEventListener("click", function () {
  deleteMode = !deleteMode;

  let btn = this;

  // semua elemen checkbox
  let chkCols = document.querySelectorAll(".chk_hps");
  let chkItems = document.querySelectorAll(".chk_item");

  if (deleteMode) {
    // 👉 MASUK MODE HAPUS
    mode_hapus(chkCols, btn);
  } else {
    // 👉 BATAL / RESET
    mode_batal(chkCols, chkItems, btn);
  }
});

// 4. SELECT ALL
document.getElementById("chk_all").addEventListener("change", function () {
  let checked = this.checked;
  document.querySelectorAll(".chk_item").forEach((cb) => {
    cb.checked = checked;
  });
});

// btn_exec_hapus
document
  .getElementById("btn_exec_hapus")
  .addEventListener("click", function () {
    let selected = [];

    document.querySelectorAll(".chk_item:checked").forEach((cb) => {
      selected.push(cb.value);
    });

    if (selected.length === 0) {
      swall_init(
        "Data belum dipilih.",
        "Hanya data yang dicentang yang akan dihapus.",
      );
      return;
    }
    console.log(selected);
    // return;

    swal({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      type: "warning",
      buttons: {
        cancel: {
          visible: true,
          text: "No, cancel!",
          className: "btn btn-danger",
        },
        confirm: {
          text: "Yes, delete it!",
          className: "btn btn-success",
        },
      },
    }).then((willDelete) => {
      if (willDelete) {
        // kirim ke controller pakai HTMX
        htmx.ajax("POST", "<?= base_url('user/hapusBulk') ?>", {
          values: {
            chk_hapus: JSON.stringify(selected),
          },
          target: "#info_result",
          swap: "innerHTML",
        });
      }
      //  else {
      //     swal("Your imaginary file is safe!", {
      //         buttons: {
      //             confirm: {
      //                 className: "btn btn-success",
      //             },
      //         },
      //     });
      // }
    });
  });

setInterval(() => {
  let count = document.querySelectorAll(".chk_item:checked").length;
  document.getElementById("btn_exec_hapus").innerText = "Hapus (" + count + ")";
}, 200);
