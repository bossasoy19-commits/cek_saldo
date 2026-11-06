<?php
echo swall(
    $title ?? 'Sukses',
    $text ?? '',
    $icon ?? 'success',
    ['position' => 'top-end', 'showConfirmButton' => false, 'timer' => 3000]
);
?>

<script>
    init_table.row(".selected").remove().draw(false);
    setTimeout(() => {
        hapus_semua_select_table();
    }, 300);
</script>