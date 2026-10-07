<?= $this->extend('layout_lte/main') ?>
<?= $this->section('content') ?>

<!--begin::Container-->
<?= view($file_view, $data2) ?>
<!--end::Container-->

<!-- BOTTOM NAVIGATION BAR (FIXED MOBILE) -->
<div class="fixed-bottom bg-white border-top py-2 px-3 shadow-lg">
    <div class="row text-center g-0">
        <div class="col-3">
            <a href="<?= base_url('dashboard') ?>" class="text-primary text-decoration-none d-flex flex-column align-items-center">
                <i class="bi bi-house-door-fill fs-5"></i>
                <span class="fs-8 fw-semibold">Dashboard</span>
            </a>
        </div>
        <div class="col-3">
            <a href="<?= base_url('transaction/history') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                <i class="bi bi-clock-history fs-5"></i>
                <span class="fs-8">Riwayat</span>
            </a>
        </div>
        <div class="col-3">
            <a href="<?= base_url('report') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                <i class="bi bi-bar-chart-line fs-5"></i>
                <span class="fs-8">Laporan</span>
            </a>
        </div>
        <div class="col-3">
            <a href="<?= base_url('settings') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                <i class="bi bi-three-dots fs-5"></i>
                <span class="fs-8">Lainnya</span>
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>