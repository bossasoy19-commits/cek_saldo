<?php
echo alert($pesan ?: 'Sukses diproses !',  ['color' => 'alert-success', 'second' => '5']);
?>

<?php if (!empty($data_row)) : ?>
    <script>
        resetForm();
        htmx.process(
            init_table
            .row
            .add(<?= json_encode($data_row) ?>)
            .draw(false)
            .node()
        );
    </script>
<?php endif; ?>

<?php if (!empty($data_row_update)) : ?>
    <script>
        init_table
            .row(".selected")
            .data(<?= json_encode($data_row_update) ?>)
            .draw(false);

        htmx.process(
            init_table.row(".selected").node()
        );
    </script>
<?php endif ?>