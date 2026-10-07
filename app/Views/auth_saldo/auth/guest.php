<div class="container-fluid min-vh-100 d-flex flex-column justify-content-center py-4 px-3 bg-body-tertiary">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">

            <!-- Card Utama -->
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="card-body">

                    <!-- Icon Success -->
                    <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-check-fill fs-1"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-1">Mode Tamu Aktif!</h4>
                    <p class="text-muted small mb-3">Akun sementara Anda berhasil disiapkan.</p>

                    <!-- Info ID Guest & Banner Ketenangan -->
                    <!-- <div class="p-3 bg-light rounded-3 mb-3 d-flex align-items-center justify-content-between text-start border">
                        <div>
                            <span class="text-muted small d-block">ID Akses Tamu</span>
                            <span class="fw-mono fw-bold text-dark fs-6"><?= $guest_id ?? 'GUEST-88204' ?></span>
                        </div>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3">
                            <i class="bi bi-clock-history me-1"></i> Mode Tamu
                        </span>
                    </div> -->

                    <!-- Alert Penenang / Pesan Keamanan Data -->
                    <div class="alert alert-info border-0 rounded-3 text-start p-3 mb-4 d-flex align-items-start gap-2">
                        <i class="bi bi-shield-check-fill fs-4 text-info flex-shrink-0 mt-1"></i>
                        <div>
                            <span class="fw-bold d-block text-dark small">Jangan Khawatir!</span>
                            <span class="small text-secondary">
                                Tenang, data yang Anda catat hari ini <strong>tidak akan hilang</strong>. Anda bisa mengamankan akun kapan saja cukup dengan meragistrasikan email atau nomor WhatsApp nanti.
                            </span>
                        </div>
                    </div>

                    <!-- Ringkasan Langkah Selanjutnya -->
                    <div class="text-start mb-4">
                        <h6 class="fw-bold text-dark small mb-2">Langkah Selanjutnya di Setup Awal:</h6>
                        <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small text-secondary">
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                <span>Menentukan dompet/rekening utama (Kas, Bank, E-Wallet).</span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                <span>Memasukkan saldo awal setiap dompet.</span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                <span>Mencatat piutang & hutang jika ada (opsional).</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Tombol Aksi Utama -->
                    <a href="<?= base_url('auth/setup') ?>" class="btn btn-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <span>Lanjut ke Setup Awal</span>
                        <i class="bi bi-arrow-right-short fs-4"></i>
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>