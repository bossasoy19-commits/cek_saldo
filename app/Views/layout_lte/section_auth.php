<?= $this->extend('layout_lte/main_auth') ?>
<?= $this->section('content') ?>

<!--begin::Container-->
<?= view($file_view, $data2) ?>
<!--end::Container-->

<?= $this->endSection() ?>