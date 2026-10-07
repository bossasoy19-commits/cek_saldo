<?php
// Default jenis transaksi jika tidak dilempar dari controller: 'pemasukan', 'pengeluaran', atau 'transfer'
$trx_type = $type ?? 'pemasukan';
?>

<div class="container-fluid py-3 px-3" x-data="transactionForm('<?= $trx_type ?>')">

    <!-- HEADER NAVIGATION & TIPE SWITCHER -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('home') ?>" class="btn btn-light rounded-circle p-2 shadow-sm border d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="bi bi-arrow-left fs-5 text-dark"></i>
            </a>
            <h6 class="fw-bold text-dark mb-0" x-text="headerTitle()"></h6>
        </div>

        <!-- Badge Status Tipe -->
        <span class="badge px-3 py-2 rounded-pill fw-semibold" :class="badgeClass()">
            <i class="bi me-1" :class="badgeIcon()"></i>
            <span x-text="typeLabel()"></span>
        </span>
    </div>

    <!-- MAIN FORM CARD -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <?php
            echo form_open(
                base_url(($controller ?? 'transaction') . '/save'),
                [
                    'id' => 'form_transaction',
                    'class' => 'needs-validation',
                    'novalidate' => 'novalidate',
                    'autocomplete' => 'off',
                    'enctype' => 'multipart/form-data',
                    'data-title' => 'Simpan Transaksi',
                    'data-text' => 'Pastikan nominal dan detail transaksi sudah sesuai!',
                    'data-icon' => 'question',
                    'data-confirm' => 'Ya, Simpan 🚀',
                    'data-cancel' => 'Batal',
                    'data-method' => $hx_post ?? 'post',
                ]
            );
            ?>

            <!-- Hidden Field untuk Tipe Transaksi -->
            <input type="hidden" name="type" x-model="type">

            <!-- 1. INPUT NOMINAL DYNAMICAL COLOR -->
            <div class="mb-4 text-center p-3 rounded-4 border" :class="nominalCardClass()">
                <label class="form-label fw-semibold small mb-1" :class="nominalTextClass()" x-text="'Nominal ' + typeLabel()"></label>
                <div class="input-group input-group-lg justify-content-center">
                    <span class="input-group-text bg-transparent border-0 fw-bold fs-3" :class="nominalTextClass()">Rp</span>
                    <input type="number" name="nominal" x-model.number="nominal" class="form-control border-0 bg-transparent fw-bold fs-2 text-start p-0 shadow-none" :class="nominalTextClass()" placeholder="0" min="1" required style="max-width: 220px;">
                </div>
            </div>

            <div class="row g-3">

                <!-- 2. TANGGAL & WAKTU -->
                <?php
                echo f_input([
                    'name' => 'tanggal',
                    'type' => 'datetime-local',
                    'label' => 'Tanggal & Waktu',
                    'value' => date('Y-m-d\TH:i'),
                    'class_div' => 'col-12'
                ], 'required');
                ?>

                <!-- 3A. DOMPET ASAL (Muncul pada Pengeluaran & Transfer) -->
                <div class="col-12" x-show="type === 'pengeluaran' || type === 'transfer'">
                    <label class="form-label fw-semibold text-dark small mb-1">
                        <span x-text="type === 'transfer' ? 'Dari Dompet (Sumber)' : 'Pilih Dompet'"></span>
                        <span class="text-danger">*</span>
                    </label>
                    <select name="from_wallet_id" class="form-select form-select-lg rounded-3 fs-6" :required="type === 'pengeluaran' || type === 'transfer'">
                        <option value="" disabled selected>-- Pilih Dompet --</option>
                        <?php
                        $wallets_opt = $wallets ?? [
                            ['id' => 1, 'nama' => 'Tunai', 'saldo' => 200000],
                            ['id' => 2, 'nama' => 'BRI', 'saldo' => 6500000],
                            ['id' => 3, 'nama' => 'OVO', 'saldo' => 300000],
                        ];
                        foreach ($wallets_opt as $w) :
                        ?>
                            <option value="<?= $w['id'] ?>">
                                <?= $w['nama'] ?> (Saldo: Rp <?= number_format($w['saldo'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 3B. DOMPET TUJUAN (Muncul pada Pemasukan & Transfer) -->
                <div class="col-12" x-show="type === 'pemasukan' || type === 'transfer'">
                    <label class="form-label fw-semibold text-dark small mb-1">
                        <span x-text="type === 'transfer' ? 'Ke Dompet (Tujuan)' : 'Masuk ke Dompet'"></span>
                        <span class="text-danger">*</span>
                    </label>
                    <select name="to_wallet_id" class="form-select form-select-lg rounded-3 fs-6" :required="type === 'pemasukan' || type === 'transfer'">
                        <option value="" disabled selected>-- Pilih Dompet --</option>
                        <?php foreach ($wallets_opt as $w) : ?>
                            <option value="<?= $w['id'] ?>">
                                <?= $w['nama'] ?> (Saldo: Rp <?= number_format($w['saldo'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 4. KATEGORI (Hanya Pemasukan & Pengeluaran) -->
                <div class="col-12" x-show="type !== 'transfer'">
                    <label class="form-label fw-semibold text-dark small mb-1">Kategori Transaksi <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select form-select-lg rounded-3 fs-6" :required="type !== 'transfer'">
                        <option value="" disabled selected>-- Pilih Kategori --</option>

                        <!-- Optgroup Pemasukan -->
                        <template x-if="type === 'pemasukan'">
                            <g>
                                <option value="101">Gaji / Penghasilan Utama</option>
                                <option value="102">Bonus / Tunjangan</option>
                                <option value="103">Hasil Usaha / Penjualan</option>
                                <option value="104">Lain-lain</option>
                            </g>
                        </template>

                        <!-- Optgroup Pengeluaran -->
                        <template x-if="type === 'pengeluaran'">
                            <g>
                                <option value="201">Makanan & Minuman</option>
                                <option value="202">Belanja / Kebutuhan Harian</option>
                                <option value="203">Transportasi / Bensin</option>
                                <option value="204">Tagihan & Utilitas</option>
                                <option value="205">Lain-lain</option>
                            </g>
                        </template>
                    </select>
                </div>

                <!-- 5. KETERANGAN / CATATAN -->
                <?php
                echo f_input([
                    'name' => 'keterangan',
                    'label' => 'Catatan / Keterangan',
                    'class_div' => 'col-12'
                ], 'placeholder="Contoh: Beli bensin, Gaji bulanan, Pindah saldo"');
                ?>

                <!-- 6. KAS GANTUNG & BUKTI / KUITANSI -->
                <div class="col-12">
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="switchPending" x-model="isPending" name="is_pending" value="1">
                            <label class="form-check-label fw-semibold text-dark small" for="switchPending">
                                Simpan sebagai Transaksi Gantung (Pending)
                            </label>
                        </div>
                        <p class="text-muted fs-7 mb-2" x-show="isPending">
                            <i class="bi bi-info-circle text-warning me-1"></i> Transaksi pending tidak akan mempengaruhi saldo dompet sampai disetujui / dikonfirmasi.
                        </p>

                        <div class="mt-2" x-show="!isPending">
                            <label class="form-label fw-semibold text-dark small mb-1">Bukti / Nota (Opsional)</label>
                            <input type="file" name="attachment" class="form-control form-control-sm" accept="image/*,.pdf">
                        </div>
                    </div>
                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex gap-2 mt-4">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-light py-2 px-4 rounded-3 fw-semibold border">
                    Batal
                </a>
                <button type="submit" class="btn py-2 px-4 rounded-3 fw-semibold w-100 shadow-sm text-white" :class="submitBtnClass()">
                    <i class="bi bi-check-circle me-1"></i> Simpan Transaksi
                </button>
            </div>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>

<script>
    function transactionForm(initialType) {
        return {
            type: initialType || 'pemasukan', // pemasukan, pengeluaran, transfer
            nominal: '',
            isPending: false,

            headerTitle() {
                if (this.type === 'pemasukan') return 'Tambah Pemasukan';
                if (this.type === 'pengeluaran') return 'Tambah Pengeluaran';
                return 'Transfer Antar Dompet';
            },
            typeLabel() {
                if (this.type === 'pemasukan') return 'Pemasukan';
                if (this.type === 'pengeluaran') return 'Pengeluaran';
                return 'Transfer';
            },
            badgeClass() {
                if (this.type === 'pemasukan') return 'bg-success-subtle text-success';
                if (this.type === 'pengeluaran') return 'bg-danger-subtle text-danger';
                return 'bg-primary-subtle text-primary';
            },
            badgeIcon() {
                if (this.type === 'pemasukan') return 'bi-plus-circle';
                if (this.type === 'pengeluaran') return 'bi-dash-circle';
                return 'bi-arrow-left-right';
            },
            nominalCardClass() {
                if (this.type === 'pemasukan') return 'bg-success-subtle border-success-subtle';
                if (this.type === 'pengeluaran') return 'bg-danger-subtle border-danger-subtle';
                return 'bg-primary-subtle border-primary-subtle';
            },
            nominalTextClass() {
                if (this.type === 'pemasukan') return 'text-success';
                if (this.type === 'pengeluaran') return 'text-danger';
                return 'text-primary';
            },
            submitBtnClass() {
                if (this.type === 'pemasukan') return 'btn-success';
                if (this.type === 'pengeluaran') return 'btn-danger';
                return 'btn-primary';
            }
        }
    }
</script>