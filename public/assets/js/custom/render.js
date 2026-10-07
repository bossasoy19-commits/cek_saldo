const Render = {
  status_aktif(data, type) {
    // Tampilan
    if (type === "display") {
      return data == 1
        ? '<span class="badge bg-success">Aktif</span>'
        : '<span class="badge bg-danger">Tidak Aktif</span>';
    }

    // Untuk pencarian
    if (type === "filter") {
      return data == 1 ? "Aktif" : "Tidak Aktif";
    }

    // Untuk sorting
    return data;
  },

  action(idField) {
    return function (data, type, row, meta) {
      if (type !== "display") {
        return "";
      }
      const tr = meta.settings.aoData[meta.row].nTr;
      // const id = tr.dataset.id;
      const id = row[idField];
      const realName = row[1];
      const controller =
        document.getElementById("htmx_target1").dataset.controller;

      return `
            <div class="btn-group btn-group-sm">

              <button
                  class="btn btn-outline-secondary text-danger btn_confirm btn_select_table"
                  hx-delete="${APP.baseUrl}${controller}/delete"
                  data-title="Hapus data 🤷‍♂️${realName} ?"
                  data-text="Data ${controller} yang sudah dihapus tidak dapat dikembalikan."
                  hx-vals='{"id":"${id}"}'
                  hx-target="#htmx_target1"
                  hx-swap="innerHTML">
                  <i class="bi bi-trash"></i>
              </button>

                 <button class="btn btn-outline-secondary btn-edit btn_select_table"
                    data-id="${id}"
                    title="Edit"
                    onclick="openHtmxModal({
                        url: '${APP.baseUrl}${controller}/form',
                        title: 'Update ${controller.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase())}',
                        static: true,
                        size: 'lg',
                        id : '${id}'
                    })">
                    <i class="bi bi-pencil"></i>
                </button>

                <div class="btn-group btn-group-sm d-none">
                    <button
                        type="button"
                        class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="bi bi-three-dots-vertical"></i>

                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item btn-reset-password"
                               href="#"
                               data-id="${id}">
                                <i class="bi bi-key me-2"></i>
                                Reset Password
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item btn-role"
                               href="#"
                               data-id="${id}">
                                <i class="bi bi-person-lock me-2"></i>
                                Hak Akses
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item btn-history"
                               href="#"
                               data-id="${id}">
                                <i class="bi bi-clock-history me-2"></i>
                                Riwayat Login
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        

                    </ul>

                </div>

            </div>
        `;
    };
  },

  action2(idField) {
    return function (data, type, row, meta) {
      if (type !== "display") {
        return "";
      }
      const tr = meta.settings.aoData[meta.row].nTr;
      // const id = tr.dataset.id;
      const id = row[idField];
      const realName = row[1];
      const controller =
        document.getElementById("htmx_target1").dataset.controller;
      const size_modal =
        document.getElementById("htmx_target1").dataset.size_modal;
      const title = document.getElementById("htmx_target1").dataset.title;

      return `
            <div class="btn-group btn-group-sm">

              <button
                  class="btn btn-outline-secondary text-danger btn_confirm btn_select_table"
                  hx-delete="${APP.baseUrl}${controller}/delete"
                  data-title="Hapus data 🤷‍♂️${realName} ?"
                  data-text="Data ${title} yang sudah dihapus tidak dapat dikembalikan."
                  hx-vals='{"id":"${id}"}'
                  hx-target="#htmx_target1"
                  hx-swap="innerHTML">
                  <i class="bi bi-trash"></i>
              </button>

                 <button class="btn btn-outline-secondary btn-edit btn_select_table"
                    data-id="${id}"
                    title="Edit"
                    onclick="openHtmxModal({
                        url: '${APP.baseUrl}${controller}/form',
                        title: 'Update ${controller.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase())}',
                        static: true,
                        size: '${size_modal}',
                        id : '${id}',
                        mode : 'edit',
                    })">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
        `;
    };
  },

  number() {
    return function (data, type, row, meta) {
      return parseInt(meta.row) + 1;
    };
  },
};

function hapus_semua_select_table() {
  init_table
    .rows(".selected")
    .nodes()
    .each((row) => row.classList.remove("selected"));
}

const DT_OPTIONS = {
  table_user: {
    columnDefs: [
      {
        targets: 0,
        data: null,
        className: "text-right",
        render: Render.number(),
      },
      {
        targets: 4,
        className: "text-center",
        render: Render.status_aktif,
      },
      {
        targets: -2,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: Render.action(6),
      },
      {
        targets: -1,
        visible: false,
        searchable: false,
        orderable: false,
      },
    ],
  },
  table_ugroup: {
    columnDefs: [
      {
        targets: 0,
        data: null,
        className: "text-right",
        render: Render.number(),
      },
      {
        targets: 2,
        className: "text-center",
        render: Render.status_aktif,
      },
      {
        targets: -2,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: Render.action2(4),
      },
      {
        targets: -1,
        visible: false,
        searchable: false,
        orderable: false,
      },
    ],
  },
  table_cabang: {
    columnDefs: [
      {
        targets: 0,
        data: null,
        className: "text-right",
        render: Render.number(),
      },
      {
        targets: 7,
        className: "text-center",
        render: Render.status_aktif,
      },
      {
        targets: -2,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: Render.action2(9),
      },
      {
        targets: -1,
        visible: false,
        searchable: false,
        orderable: false,
      },
    ],
  },
};
