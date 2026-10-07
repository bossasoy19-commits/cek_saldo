$(document).ready(function () {});
let init_table;
// DataTableFilter
function initDataTable(
  tableId,
  skipColumns = [],
  numColumns = [],
  page_length = 25,
  coloum_search = false,
  options = {},
) {
  const config = {
    responsive: true,
    pageLength: page_length,
    layout: {
      topStart: ["pageLength"],
      topEnd: [
        {
          buttons: [
            {
              extend: "copy",
              text: '<i class="bi bi-copy"></i> Copy',
              exportOptions: {
                columns: ":visible:not(:last-child)",
              },
            },
            {
              extend: "excel",
              text: '<i class="bi bi-file-earmark-excel"></i> Excel',
              exportOptions: {
                columns: ":visible:not(:last-child)",
              },
            },
            {
              extend: "pdf",
              text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
              exportOptions: {
                columns: ":visible:not(:last-child)",
              },
            },
            {
              extend: "print",
              text: '<i class="bi bi-printer"></i> Print',
              exportOptions: {
                columns: ":visible:not(:last-child)",
              },
            },
          ],
        },
        "search",
      ],
      bottomStart: "info",
      bottomEnd: "paging",
    },

    initComplete: function () {
      this.api()
        .columns()
        .every(function (index) {
          if (skipColumns.includes(index) || coloum_search === false) return;
          let column = this;
          let title = column.footer().textContent;

          // Create input element
          let input = document.createElement("input");
          input.placeholder = title;
          input.style.width = "100%";
          column.footer().replaceChildren(input);

          // Event listener for user input
          input.addEventListener("keyup", () => {
            if (column.search() !== this.value) {
              column.search(input.value).draw();
            }
          });
        });
    },
  };
  // console.log("options2:");
  // console.log(options);
  init_table = new DataTable(tableId, {
    ...config,
    ...options,
  });

  // tandai yang di clik  btn_select_table
  init_table.on("click", "tbody tr", (e) => {
    let classList = e.currentTarget.classList;

    // Hanya jika klik berasal dari tombol yang mengizinkan select
    if (!e.target.closest(".btn_select_table")) {
      hapus_semua_select_table();
      return;
    }

    if (classList.contains("selected")) {
      // classList.remove("selected");
    } else {
      hapus_semua_select_table();
      classList.add("selected");
    }
  });
  // HTMX: Memproses Elemen Dinamis dengan htmx.process()
  // htmx.process(document.querySelector(tableId));
  $(".btn_action").removeClass("disabled").prop("disabled", false);
  return init_table;
}

function initTables(container) {
  $(container)
    .find("table.datatable")
    .each(function () {
      let tableId = "#" + $(this).attr("id");

      let key = $(this).data("options");
      let options = DT_OPTIONS[key] || {};

      let skip = $(this).data("skip") || [];
      let num_coloumn = $(this).data("num_coloumn") || [];
      let page_length = $(this).data("page_length") || 15;
      let coloum_search = $(this).data("coloum_search") || false;

      if (typeof skip === "string") {
        skip = JSON.parse(skip);
      }

      initDataTable(
        tableId,
        skip,
        num_coloumn,
        page_length,
        coloum_search,
        options,
      );
    });
}

function updateNumber(table) {
  let info = table.page.info();

  table
    .column(0, { page: "current" })
    .nodes()
    .each(function (cell, i) {
      cell.innerHTML = info.start + i + 1;
    });
}

function focusRowByPage(tableId, id, page) {
  let table = window.dtInstances[tableId];
  if (!table) return;

  let targetPage = page - 1;

  console.log("➡️ pindah ke page:", targetPage);

  table.page(targetPage).draw("page"); // 🔥 penting
  setTimeout(() => {
    focusRowNow(table, id);
  }, 300);
}

function focusRowNow(table, id) {
  let foundNode = null;

  table.rows().every(function () {
    let node = this.node();

    if ($(node).data("id") == id) {
      foundNode = node;
    }
  });

  // console.log("cari:", id);

  if (foundNode) {
    // console.log("✅ ketemu!");

    $(foundNode).find("td").css({
      "background-color": "#ffeb3b",
      color: "#000",
      "font-weight": "bold",
    });
  } else {
    console.log("❌ tidak ketemu:", id);
  }
}

$(document).on("click", ".toggle-password", function () {
  console.log("asdsad");
  const wrapper = $(this).closest(".password-wrapper");
  const input = wrapper.find(".password-input");

  if (input.attr("type") === "password") {
    input.attr("type", "text");
    $(this).text("🙈");
  } else {
    input.attr("type", "password");
    $(this).text("👁");
  }
});

let hideTimer;
let countdownTimer;
let seconds;

function startAutoHide(second = 15) {
  const respon = $("#respon_htmx");

  if (!respon.length) return;

  seconds = second;

  clearTimeout(hideTimer);
  clearInterval(countdownTimer);

  $("#countdown").text(seconds);

  countdownTimer = setInterval(() => {
    seconds--;
    $("#countdown").text(seconds);

    if (seconds <= 0) {
      clearInterval(countdownTimer);
    }
  }, 1000);

  hideTimer = setTimeout(() => {
    respon.empty();
  }, seconds * 1000);

  respon.off(".autoHide");

  respon.on("mouseenter.autoHide", function () {
    clearTimeout(hideTimer);
    clearInterval(countdownTimer);
  });

  respon.on("mouseleave.autoHide", function () {
    startAutoHideResume();
  });
}

function startAutoHideResume() {
  $("#countdown").text(seconds);

  clearTimeout(hideTimer);
  clearInterval(countdownTimer);

  countdownTimer = setInterval(() => {
    seconds--;
    $("#countdown").text(seconds);

    if (seconds <= 0) {
      clearInterval(countdownTimer);
    }
  }, 1000);

  hideTimer = setTimeout(() => {
    $("#respon_htmx").empty();
  }, seconds * 1000);
}

function resetForm(id = "form_datas") {
  const form = document.getElementById(id);

  if (!form) return;

  form.reset();
  form.classList.remove("was-validated");
}
