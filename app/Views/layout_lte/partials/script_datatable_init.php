<?= view('layout_lte/partials/script_datatable'); ?>

<?= $this->section('script_custom') ?>
<script>
    initTables('#content_htmx');
</script>
<script>
    // init_table.search("Tania").draw();
</script>
<?= $this->endSection() ?>