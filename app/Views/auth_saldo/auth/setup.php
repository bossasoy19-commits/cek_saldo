<div class="container-fluid min-vh-100 d-flex flex-column justify-content-center py-4 px-3 bg-body-tertiary" x-data="setupWizard()">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <!-- Stepper Progress Bar -->
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold small text-primary" x-text="'Langkah ' + step + ' dari 6'"></span>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3" x-text="stepTitle()"></span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" :style="'width: ' + (step * 16.6) + '%'"></div>
                    </div>
                </div>
            </div>

            <!-- Card Form Container -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <?php
                    echo form_open(
                        base_url(($controller ?? 'setup') . '/save_first_time'),
                        [
                            'id' => 'form_datas',
                            'class' => 'needs-validation',
                            'novalidate' => 'novalidate',
                            'autocomplete' => 'off',
                            'data-title' => 'Simpan Setup Awal',
                            'data-text' => 'Pastikan data ruang dan saldo awal sudah sesuai!',
                            'data-icon' => 'question',
                            'data-confirm' => 'Selesaikan & Simpan 🚀',
                            'data-cancel' => 'Periksa Lagi 🔍',
                            'data-method' => $hx_post ?? 'post',
                            'data-target' => '#setup_respon',
                        ]
                    );
                    ?>

                    <!-- STEP 1: BUAT RUANG KEUANGAN (ROOM) -->
                    <div x-show="step === 1">
                        <h5 class="fw-bold text-dark mb-1">1. Buat Ruang Keuangan (Room)</h5>
                        <p class="text-muted small mb-3">Tentukan nama ruang utama pencatatan Anda.</p>

                        <div class="row g-3">
                            <?php
                            echo f_input([
                                'name' => 'nama_room',
                                'label' => 'Nama Ruang Keuangan',
                                'value' => $room['nama_room'] ?? 'Keuangan Pribadi',
                                'class_div' => 'col-12'
                            ], 'required placeholder="Contoh: Keuangan Pribadi / Usaha Toko"');

                            $opt_jenis_room = [
                                '1' => 'Pribadi / Keluarga',
                                '2' => 'Bisnis / Usaha'
                            ];

                            echo f_option([
                                'name' => 'jenis_room',
                                'label' => 'Jenis Penggunaan',
                                'opt' => $opt_jenis_room,
                                'selected' => $room['jenis_room'] ?? '1',
                                'class_div' => 'col-12'
                            ], 'required');
                            ?>
                        </div>
                    </div>

                    <!-- STEP 2: TAMBAH DOMPET / REKENING -->
                    <div x-show="step === 2">
                        <h5 class="fw-bold text-dark mb-1">2. Tambah Dompet / Rekening</h5>
                        <p class="text-muted small mb-3">Di mana saja Anda menyimpan uang?</p>

                        <div class="d-flex flex-column gap-2 mb-3">
                            <template x-for="(wallet, index) in wallets" :key="index">
                                <div class="p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2 w-100">
                                        <i class="bi bi-wallet2 text-primary fs-5"></i>
                                        <input type="text" :name="'wallets[' + index + '][nama]'" x-model="wallet.nama" class="form-control border-0 fw-semibold p-0" placeholder="Nama Dompet (misal: Tunai, BRI, OVO)" required>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger p-0 ms-2" @click="removeWallet(index)" x-show="wallets.length > 1">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill w-100 py-2 fw-semibold" @click="addWallet()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Dompet
                        </button>
                    </div>

                    <!-- STEP 3: MASUKKAN SALDO AWAL -->
                    <div x-show="step === 3">
                        <h5 class="fw-bold text-dark mb-1">3. Masukkan Saldo Awal</h5>
                        <p class="text-muted small mb-3">Berapa saldo awal Anda di setiap dompet?</p>

                        <div class="d-flex flex-column gap-3 mb-3">
                            <template x-for="(wallet, index) in wallets" :key="index">
                                <div class="p-3 border rounded-3 bg-white">
                                    <label class="form-label fw-bold small text-secondary mb-1" x-text="wallet.nama || ('Dompet ' + (index + 1))"></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">Rp</span>
                                        <input type="number" :name="'wallets[' + index + '][saldo]'" x-model.number="wallet.saldo" class="form-control border-0 bg-light" placeholder="0" min="0" required>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="p-3 bg-primary-subtle rounded-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary small">Total Uang</span>
                            <span class="fw-bold text-primary fs-5" x-text="formatRupiah(totalSaldo())"></span>
                        </div>
                    </div>

                    <!-- STEP 4: PIUTANG (OPSIONAL) -->
                    <div x-show="step === 4">
                        <h5 class="fw-bold text-dark mb-1">4. Piutang (Opsional)</h5>
                        <p class="text-muted small mb-3">Apakah ada uang Anda yang dipinjam orang lain?</p>

                        <div class="d-flex flex-column gap-2 mb-3">
                            <template x-for="(item, index) in piutang" :key="index">
                                <div class="p-3 border rounded-3 bg-white">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small fw-bold text-muted" x-text="'Piutang #' + (index + 1)"></span>
                                        <button type="button" class="btn btn-link text-danger p-0" @click="removePiutang(index)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <input type="text" :name="'piutang[' + index + '][nama]'" x-model="item.nama" class="form-control mb-2" placeholder="Nama Peminjam (misal: Budi)">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">Rp</span>
                                        <input type="number" :name="'piutang[' + index + '][nominal]'" x-model.number="item.nominal" class="form-control border-0 bg-light" placeholder="0" min="0">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill w-100 py-2 fw-semibold mb-3" @click="addPiutang()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Piutang
                        </button>

                        <div class="p-3 bg-success-subtle rounded-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-success small">Total Piutang</span>
                            <span class="fw-bold text-success fs-5" x-text="formatRupiah(totalPiutang())"></span>
                        </div>
                    </div>

                    <!-- STEP 5: HUTANG (OPSIONAL) -->
                    <div x-show="step === 5">
                        <h5 class="fw-bold text-dark mb-1">5. Hutang (Opsional)</h5>
                        <p class="text-muted small mb-3">Apakah Anda memiliki hutang saat ini?</p>

                        <div class="d-flex flex-column gap-2 mb-3">
                            <template x-for="(item, index) in hutang" :key="index">
                                <div class="p-3 border rounded-3 bg-white">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small fw-bold text-muted" x-text="'Hutang #' + (index + 1)"></span>
                                        <button type="button" class="btn btn-link text-danger p-0" @click="removeHutang(index)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <input type="text" :name="'hutang[' + index + '][nama]'" x-model="item.nama" class="form-control mb-2" placeholder="Hutang Kepada (misal: Andi)">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">Rp</span>
                                        <input type="number" :name="'hutang[' + index + '][nominal]'" x-model.number="item.nominal" class="form-control border-0 bg-light" placeholder="0" min="0">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill w-100 py-2 fw-semibold mb-3" @click="addHutang()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Hutang
                        </button>

                        <div class="p-3 bg-danger-subtle rounded-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-danger small">Total Hutang</span>
                            <span class="fw-bold text-danger fs-5" x-text="formatRupiah(totalHutang())"></span>
                        </div>
                    </div>

                    <!-- STEP 6: SETUP SELESAI -->
                    <div x-show="step === 6" class="text-center py-2">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3 shadow" style="width: 64px; height: 64px;">
                            <i class="bi bi-check-lg fs-1"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Setup Selesai!</h5>
                        <p class="text-muted small mb-4" id="setup_respon">Selamat! Setup awal pencatatan keuangan berhasil disusun.</p>

                        <div class="row g-2 text-start mb-4">
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-white d-flex justify-content-between align-items-center">
                                    <span class="text-secondary small fw-semibold">Total Uang</span>
                                    <span class="fw-bold text-primary" x-text="formatRupiah(totalSaldo())"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded-3 bg-white">
                                    <span class="text-secondary small d-block">Total Piutang</span>
                                    <span class="fw-bold text-success" x-text="formatRupiah(totalPiutang())"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded-3 bg-white">
                                    <span class="text-secondary small d-block">Total Hutang</span>
                                    <span class="fw-bold text-danger" x-text="formatRupiah(totalHutang())"></span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm">
                            Mulai Gunakan Aplikasi
                        </button>
                    </div>

                    <?php echo form_close(); ?>

                    <!-- Controller Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4" x-show="step < 6">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3" @click="prevStep()" x-show="step > 1">
                            Kembali
                        </button>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold" @click="nextStep()">
                                <span x-text="step === 5 ? 'Lanjut ke Ringkasan' : 'Lanjut'"></span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function setupWizard() {
        return {
            step: 1,
            maxStep: 6,
            wallets: [{
                    nama: 'Tunai',
                    saldo: 0
                },
                {
                    nama: 'BRI',
                    saldo: 0
                },
                {
                    nama: 'OVO',
                    saldo: 0
                }
            ],
            piutang: [],
            hutang: [],

            stepTitle() {
                const titles = ['Buat Room', 'Pilih Dompet', 'Saldo Awal', 'Data Piutang', 'Data Hutang', 'Konfirmasi'];
                return titles[this.step - 1];
            },

            nextStep() {
                if (this.step === 1) {
                    const roomInput = document.getElementById('nama_room');
                    if (roomInput && !roomInput.value.trim()) {
                        alert('Mohon isi nama ruang keuangan!');
                        return;
                    }
                }

                if (this.step === 2) {
                    const hasValidWallet = this.wallets.some(w => w.nama.trim() !== '');
                    if (!hasValidWallet) {
                        alert('Mohon isi minimal satu nama dompet!');
                        return;
                    }
                }

                if (this.step < this.maxStep) {
                    this.step++;
                }
            },

            prevStep() {
                if (this.step > 1) {
                    this.step--;
                }
            },

            addWallet() {
                this.wallets.push({
                    nama: '',
                    saldo: 0
                });
            },
            removeWallet(index) {
                if (this.wallets.length > 1) {
                    this.wallets.splice(index, 1);
                }
            },
            addPiutang() {
                this.piutang.push({
                    nama: '',
                    nominal: 0
                });
            },
            removePiutang(index) {
                this.piutang.splice(index, 1);
            },
            addHutang() {
                this.hutang.push({
                    nama: '',
                    nominal: 0
                });
            },
            removeHutang(index) {
                this.hutang.splice(index, 1);
            },
            totalSaldo() {
                return this.wallets.reduce((sum, w) => sum + (Number(w.saldo) || 0), 0);
            },
            totalPiutang() {
                return this.piutang.reduce((sum, p) => sum + (Number(p.nominal) || 0), 0);
            },
            totalHutang() {
                return this.hutang.reduce((sum, h) => sum + (Number(h.nominal) || 0), 0);
            },
            formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }
        }
    }
</script>