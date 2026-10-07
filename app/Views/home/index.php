<style>
    .decorative-circle {
        width: 140px;
        height: 140px;
        background: rgba(255, 255, 255, 0.5);
    }
</style>
<?php helper('number'); ?>
<div class="container-fluid min-vh-100 pb-5 px-3 bg-body-tertiary">

    <!-- Top App Bar / User Greeting -->
    <div class="d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                <?= strtoupper(substr($user['nama'] ?? 'Guest', 0, 1)) ?>
            </div>
            <div>
                <span class="text-muted small d-block mb-0">Halo,</span>
                <h6 class="fw-bold text-dark mb-0"><?= $user['nama'] ?? 'Andi' ?> 👋</h6>
            </div>
        </div>
        <a href="<?= base_url('notifications') ?>" class="btn btn-light rounded-circle p-2 shadow-sm border position-relative">
            <i class="bi bi-bell fs-5 text-secondary"></i>
            <!-- Indikator notifikasi jika ada transaksi gantung -->
            <?php if (!empty($has_pending_trx)) : ?>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                </span>
            <?php endif; ?>
        </a>
    </div>

    <!-- BANNER KHUSUS GUEST (Jika status_akun == 0) -->
    <?php if (isset($user['status_akun']) && $user['status_akun'] == 0) : ?>
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-3 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-exclamation text-warning fs-4"></i>
                    <span class="fw-bold text-dark small">Mode Tamu (Guest)</span>
                </div>
                <span class="badge bg-warning text-dark rounded-pill px-2 py-1 fs-8">Belum Aman</span>
            </div>
            <p class="text-secondary small mb-2">
                Amankan data keuangan Anda agar tidak hilang saat aplikasi dibersihkan.
            </p>
            <a href="<?= base_url('auth/claim_account') ?>" class="btn btn-warning btn-sm w-100 rounded-3 fw-semibold text-dark shadow-sm">
                <i class="bi bi-shield-lock-fill me-1"></i> Klaim / Amankan Akun Saya
            </a>
        </div>
    <?php endif; ?>

    <!-- MAIN CARD: TOTAL UANG & RINGKASAN UTANG/PIUTANG -->
    <div class="card border-0 bg-primary text-white rounded-4 shadow mb-4 overflow-hidden position-relative">
        <div class="card-body p-4 position-relative z-1">
            <span class="text-white-50 small d-block mb-1">Total Uang</span>
            <h2 class="fw-bold mb-3"><?= number_to_currency($total_uang ?? 7000000, 'IDR', 'id_ID', 0) ?></h2>

            <div class="row g-2 pt-2 border-top border-white-10">
                <div class="col-6">
                    <div class="p-2 bg-white bg-opacity-10 rounded-3">
                        <span class="text-white-50 fs-7 d-block">Piutang</span>
                        <span class="fw-semibold text-white small"><?= number_to_currency($total_piutang ?? 200000, 'IDR', 'id_ID', 0) ?></span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-white bg-opacity-10 rounded-3">
                        <span class="text-white-50 fs-7 d-block">Hutang</span>
                        <span class="fw-semibold text-white small"><?= number_to_currency($total_hutang ?? 500000, 'IDR', 'id_ID', 0) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative Background Circle -->
        <div class="position-absolute bottom-0 end-0 rounded-circle me-n4 mb-n4 decorative-circle"></div>
    </div>

    <!-- QUICK ACTION BUTTONS: TRANSAKSI -->
    <div class="row g-2 mb-4">
        <div class="col-4">
            <a href="<?= base_url('transaction/form/pemasukan') ?>" class="btn btn-success w-100 py-3 rounded-4 shadow-sm d-flex flex-column align-items-center gap-1">
                <i class="bi bi-plus-circle-fill fs-4"></i>
                <span class="fw-semibold fs-7">Pemasukan</span>
            </a>
        </div>
        <div class="col-4">
            <a href="<?= base_url('transaction/form/pengeluaran') ?>" class="btn btn-danger w-100 py-3 rounded-4 shadow-sm d-flex flex-column align-items-center gap-1">
                <i class="bi bi-dash-circle-fill fs-4"></i>
                <span class="fw-semibold fs-7">Pengeluaran</span>
            </a>
        </div>
        <div class="col-4">
            <a href="<?= base_url('transaction/form/transfer') ?>" class="btn btn-primary w-100 py-3 rounded-4 shadow-sm d-flex flex-column align-items-center gap-1">
                <i class="bi bi-arrow-left-right fs-4"></i>
                <span class="fw-semibold fs-7">Transfer</span>
            </a>
        </div>
    </div>

    <!-- LIST SALDO PER DOMPET -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold text-dark mb-0">Saldo per Dompet</h6>
        <a href="<?= base_url('account') ?>" class="text-primary text-decoration-none small fw-semibold">Kelola</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-2">
            <div class="d-flex flex-column gap-1">
                <?php

                $wallet = array_filter($account, function ($item) {
                    return $item['is_wallet'] == 1;
                });
                foreach ($wallet as $dompet) : ?>
                    <?php
                    $icon = match ($dompet['jenis_wallet'] ?? null) {
                        'CASH'    => 'bi-cash-stack',
                        'BANK'    => 'bi-bank',
                        'EWALLET' => 'bi-wallet2',
                        default   => 'bi-wallet2',
                    };
                    ?>

                    <div class="p-3 rounded-3 d-flex align-items-center justify-content-between hover-bg-light">

                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 bg-light rounded-3 text-primary">
                                <i class="bi <?= $icon ?> fs-5"></i>
                            </div>

                            <span class="fw-semibold text-dark">
                                <?= esc($dompet['nama_akun']) ?>
                            </span>
                        </div>

                        <span class="fw-bold text-dark">
                            <?= number_to_currency($dompet['saldo'] ?? 0, 'IDR', 'id_ID', 0) ?>
                        </span>

                    </div>
                <?php endforeach ?>


            </div>
        </div>
    </div>



</div>