<script>
    // EFEK ROW HILANG(opsional keren😎)
    var table = $('#multi-filter-select').DataTable();

    document.querySelectorAll(".chk_item:checked").forEach(cb => {
        table
            .row(cb.closest("tr"))
            .remove();
    });

    table.draw();
    $('#btn_hapus_chk').click();

    swal("Berhasil menghapus semua data yang dipilih !", {
        icon: "success",
        buttons: {
            confirm: {
                className: "btn btn-success",
            },
        },
    });
</script>