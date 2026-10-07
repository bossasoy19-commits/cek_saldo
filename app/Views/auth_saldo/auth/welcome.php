<div class="container-fluid min-vh-100 d-flex flex-column justify-content-between py-4 px-3 bg-body-tertiary">

    <!-- Header / Branding -->
    <div class="text-center mt-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3 shadow" style="width: 72px; height: 72px;">
            <i class="bi bi-wallet2 fs-1"></i>
        </div>
        <h2 class="fw-bold text-dark mb-1">Saldoku</h2>
        <p class="text-muted fs-6">Pencatatan Keuangan Praktis & Transparan</p>
    </div>

    <!-- Hero / Visual Illustration Card -->
    <div class="my-auto py-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 text-center bg-white">
                <div class="mb-3">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fs-7 fw-semibold">
                        <i class="bi bi-shield-check me-1"></i> Mode Tamu Tersedia
                    </span>
                </div>
                <h5 class="fw-bold text-dark mb-2">Kelola Uang Tanpa Ribet</h5>
                <p class="text-secondary small mb-0">
                    Coba langsung pencatatan keuangan sekarang tanpa pendaftaran, atau masuk untuk sinkronisasi cloud..
                </p>
            </div>
        </div>
    </div>

    <!-- Action Buttons Container (Mobile-First Sticky Bottom Feel) -->
    <div class="w-100 d-flex flex-column gap-2 mb-2">

        <!-- Button: Mode Guest -->
        <a href="<?= base_url('auth/guest') ?>" class="btn btn-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-lightning-charge-fill"></i>
            <span>Coba Sebagai Guest</span>
        </a>

        <!-- Button: Login -->
        <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-primary btn-lg w-100 py-3 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Masuk</span>
        </a>

        <!-- Button: Register -->
        <div class="text-center mt-2">
            <span class="text-muted small">Belum punya akun?</span>


            <a class="text-primary text-decoration-none fw-semibold small ms-1" onclick="openHtmxModal({
                                    url: '<?= base_url($controller . '/registers') ?>',
                                    title: '<?= 'Form Pendaftaran' ?>',
                                    static: true,
                                    size:  '<?= $size_modal ?? 'lg' ?>'
                                })" type="button">
                Daftar Akun Baru
            </a>
        </div>

    </div>

</div>