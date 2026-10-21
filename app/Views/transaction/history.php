<div class="container-fluid py-3 px-3 pb-5 mb-4" x-data="transactionHistoryGrouped()">

    <!-- HEADER & SEARCH BAR -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light rounded-circle p-0 shadow-sm border d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-left fs-5 text-dark"></i>
            </a>
            <h6 class="fw-bold text-dark mb-0">Riwayat Transaksi</h6>
        </div>
        <button class="btn btn-light rounded-circle p-0 shadow-sm border d-flex align-items-center justify-content-center position-relative" style="width: 36px; height: 36px;" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-sliders fs-6 text-secondary"></i>
            <span x-show="sortBy !== 'tanggal_desc' || filterType !== 'all' || filterWallet !== 'all'" class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-light rounded-circle"></span>
        </button>
    </div>

    <!-- SEARCH INPUT -->
    <div class="mb-3">
        <div class="input-group shadow-sm rounded-3">
            <span class="input-group-text bg-white border-end-0 text-muted ps-3 py-2">
                <i class="bi bi-search small"></i>
            </span>
            <input type="text" x-model="searchQuery" class="form-control border-start-0 ps-0 shadow-none py-2 fs-7" placeholder="Cari keterangan..." aria-label="Cari transaksi">
            <button class="btn bg-white border-start-0 text-muted pe-3" type="button" x-show="searchQuery" @click="searchQuery = ''">
                <i class="bi bi-x-circle-fill small"></i>
            </button>
        </div>
    </div>

    <!-- BADGE SUMMARY FILTER -->
    <div class="d-flex align-items-center gap-2 mb-3 overflow-auto pb-1" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
        <span class="badge bg-white text-dark border fw-normal py-2 px-3 rounded-pill shadow-xs fs-8">
            Urutan: <strong x-text="sortLabel()"></strong>
        </span>
        <template x-if="filterType !== 'all'">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-2 px-3 rounded-pill fs-8">
                Tipe: <strong x-text="filterType"></strong>
                <i class="bi bi-x ms-1 cursor-pointer" @click="filterType = 'all'"></i>
            </span>
        </template>
        <template x-if="filterWallet !== 'all'">
            <span class="badge bg-success-subtle text-success border border-success-subtle py-2 px-3 rounded-pill fs-8">
                Dompet: <strong x-text="filterWallet"></strong>
                <i class="bi bi-x ms-1 cursor-pointer" @click="filterWallet = 'all'"></i>
            </span>
        </template>
    </div>

    <!-- DATA DUMMY TRANSAKSI -->
    <?php
    $raw_transactions = $transactions ?? [
        [
            'id' => 1,
            'type' => 'pemasukan',
            'keterangan' => 'Gaji Bulanan PT Maju Bersama',
            'category' => 'Gaji & Pendapatan',
            'nominal' => 7500000,
            'wallet' => 'BRI',
            'tanggal' => '2026-09-27 08:30:00',
            'is_pending' => 0
        ],
        [
            'id' => 2,
            'type' => 'pengeluaran',
            'keterangan' => 'Belanja Mingguan Supermarket',
            'category' => 'Belanja Harian',
            'nominal' => 450000,
            'wallet' => 'Tunai',
            'tanggal' => '2026-09-27 10:15:00',
            'is_pending' => 0
        ],
        [
            'id' => 3,
            'type' => 'transfer',
            'keterangan' => 'Topup Saldo OVO dari BRI',
            'category' => 'Transfer',
            'nominal' => 200000,
            'wallet' => 'BRI ➔ OVO',
            'tanggal' => '2026-09-26 14:00:00',
            'is_pending' => 0
        ],
        [
            'id' => 4,
            'type' => 'pengeluaran',
            'keterangan' => 'Beli Bensin Pertamax',
            'category' => 'Transportasi',
            'nominal' => 50000,
            'wallet' => 'Tunai',
            'tanggal' => '2026-09-26 16:45:00',
            'is_pending' => 0
        ],
        [
            'id' => 5,
            'type' => 'pengeluaran',
            'keterangan' => 'Servis Motor Matic (Pending Nota)',
            'category' => 'Pemeliharaan',
            'nominal' => 180000,
            'wallet' => 'OVO',
            'tanggal' => '2026-09-25 11:20:00',
            'is_pending' => 1
        ],
    ];
    ?>

    <!-- LIST TRANSAKSI DI-GRUP PER TANGGAL -->
    <div class="d-flex flex-column gap-3">

        <template x-for="(group, groupDate) in groupedTransactions()" :key="groupDate">
            <div>
                <!-- HEADER GRUP TANGGAL -->
                <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                    <span class="fw-bold text-secondary fs-8 text-uppercase tracking-wider" x-text="formatGroupHeader(groupDate)"></span>
                    <span class="badge bg-light text-muted border fs-8 fw-normal" x-text="group.length + ' Transaksi'"></span>
                </div>

                <!-- ITEM TRANSAKSI DALAM GRUP -->
                <div class="d-flex flex-column gap-2">
                    <template x-for="item in group" :key="item.id">
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between gap-2">

                                    <!-- ICON & DETAILS (LEFT) -->
                                    <div class="d-flex align-items-center gap-2 overflow-hidden flex-grow-1" style="min-width: 0;">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" :class="{
                                                'bg-success-subtle text-success': item.type === 'pemasukan',
                                                'bg-danger-subtle text-danger': item.type === 'pengeluaran',
                                                'bg-primary-subtle text-primary': item.type === 'transfer'
                                            }" style="width: 38px; height: 38px;">
                                            <i class="bi fs-6" :class="{
                                                'bi-arrow-down-left-circle-fill': item.type === 'pemasukan',
                                                'bi-arrow-up-right-circle-fill': item.type === 'pengeluaran',
                                                'bi-arrow-left-right': item.type === 'transfer'
                                            }"></i>
                                        </div>

                                        <div class="overflow-hidden flex-grow-1" style="min-width: 0;">
                                            <h6 class="fw-bold text-dark mb-0 fs-7 text-truncate" x-text="item.keterangan"></h6>
                                            <div class="d-flex align-items-center gap-1 mt-1 text-truncate">
                                                <span class="badge bg-light text-secondary border fs-8 px-1 py-0 text-truncate" style="max-width: 90px;" x-text="item.wallet"></span>
                                                <span class="text-muted fs-8 text-nowrap" x-text="'• ' + formatTime(item.tanggal)"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NOMINAL & STATUS (RIGHT) -->
                                    <div class="text-end flex-shrink-0 ps-1">
                                        <span class="fw-bold d-block fs-7 text-nowrap" :class="{
                                                'text-success': item.type === 'pemasukan',
                                                'text-danger': item.type === 'pengeluaran',
                                                'text-primary': item.type === 'transfer'
                                            }" x-text="(item.type === 'pemasukan' ? '+ ' : (item.type === 'pengeluaran' ? '- ' : '')) + formatRupiah(item.nominal)">
                                        </span>
                                        <template x-if="item.is_pending == 1">
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-8 rounded-pill mt-1 px-2 py-0">
                                                <i class="bi bi-clock me-1"></i> Pending
                                            </span>
                                        </template>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <!-- EMPTY STATE -->
        <div x-show="Object.keys(groupedTransactions()).length === 0" class="text-center py-5">
            <i class="bi bi-receipt text-muted display-6 d-block mb-2"></i>
            <h6 class="fw-bold text-dark mb-1 fs-7">Tidak Ada Transaksi</h6>
            <p class="text-muted fs-8">Tidak ditemukan data transaksi yang sesuai.</p>
        </div>

    </div>

    <!-- MODAL FILTER & SORTING -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">Filter & Urutkan</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1 fs-7">Urutkan Berdasarkan</label>
                        <select class="form-select rounded-3 fs-7 py-2" x-model="sortBy">
                            <option value="tanggal_desc">Tanggal (Terbaru ➔ Terlama)</option>
                            <option value="tanggal_asc">Tanggal (Terlama ➔ Terbaru)</option>
                            <option value="nominal_desc">Nominal (Terbesar ➔ Terkecil)</option>
                            <option value="nominal_asc">Nominal (Terkecil ➔ Terbesar)</option>
                            <option value="wallet_asc">Akun Dompet (A ➔ Z)</option>
                            <option value="type_asc">Tipe Transaksi</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1 fs-7">Tipe Transaksi</label>
                        <select class="form-select rounded-3 fs-7 py-2" x-model="filterType">
                            <option value="all">Semua Tipe</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark small mb-1 fs-7">Akun Dompet</label>
                        <select class="form-select rounded-3 fs-7 py-2" x-model="filterWallet">
                            <option value="all">Semua Dompet</option>
                            <option value="BRI">BRI</option>
                            <option value="Tunai">Tunai</option>
                            <option value="OVO">OVO</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-3 w-50 fw-semibold fs-7" @click="resetFilter()">Reset</button>
                    <button type="button" class="btn btn-primary rounded-3 w-50 fw-semibold fs-7" data-bs-dismiss="modal">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAVIGATION BAR (FIXED MOBILE) -->
    <div class="fixed-bottom bg-white border-top py-2 px-3 shadow-lg">
        <div class="row text-center g-0">
            <div class="col-3">
                <a href="<?= base_url('dashboard') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-house-door fs-5"></i>
                    <span class="fs-8">Dashboard</span>
                </a>
            </div>
            <div class="col-3">
                <a href="<?= base_url('transaction/history') ?>" class="text-primary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-clock-history-fill fs-5"></i>
                    <span class="fs-8 fw-semibold">Riwayat</span>
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

</div>

<style>
    .fs-7 {
        font-size: 0.85rem !important;
    }

    .fs-8 {
        font-size: 0.75rem !important;
    }

    .tracking-wider {
        letter-spacing: 0.05em;
    }
</style>

<script>
    function transactionHistoryGrouped() {
        return {
            searchQuery: '',
            sortBy: 'tanggal_desc',
            filterType: 'all',
            filterWallet: 'all',
            items: <?= json_encode($raw_transactions) ?>,

            // Menghasilkan Array Terfilter & Tersortir
            getFilteredList() {
                let list = [...this.items];

                if (this.searchQuery.trim() !== '') {
                    const query = this.searchQuery.toLowerCase();
                    list = list.filter(item => item.keterangan.toLowerCase().includes(query));
                }

                if (this.filterType !== 'all') {
                    list = list.filter(item => item.type === this.filterType);
                }

                if (this.filterWallet !== 'all') {
                    list = list.filter(item => item.wallet.includes(this.filterWallet));
                }

                list.sort((a, b) => {
                    if (this.sortBy === 'tanggal_desc') return new Date(b.tanggal) - new Date(a.tanggal);
                    if (this.sortBy === 'tanggal_asc') return new Date(a.tanggal) - new Date(b.tanggal);
                    if (this.sortBy === 'nominal_desc') return b.nominal - a.nominal;
                    if (this.sortBy === 'nominal_asc') return a.nominal - b.nominal;
                    if (this.sortBy === 'wallet_asc') return a.wallet.localeCompare(b.wallet);
                    if (this.sortBy === 'type_asc') return a.type.localeCompare(b.type);
                    return 0;
                });

                return list;
            },

            // Mengelompokkan Data berdasarkan YYYY-MM-DD
            groupedTransactions() {
                const list = this.getFilteredList();
                const groups = {};

                list.forEach(item => {
                    const dateKey = item.tanggal.split(' ')[0]; // Ambil YYYY-MM-DD
                    if (!groups[dateKey]) {
                        groups[dateKey] = [];
                    }
                    groups[dateKey].push(item);
                });

                return groups;
            },

            // Formatter Label Tanggal Grup (Hari Ini, Kemarin, atau 25 Sep 2026)
            formatGroupHeader(dateStr) {
                const dateObj = new Date(dateStr);
                const today = new Date('2026-09-27');
                const yesterday = new Date('2026-09-26');

                const isToday = dateObj.toDateString() === today.toDateString();
                const isYesterday = dateObj.toDateString() === yesterday.toDateString();

                if (isToday) return 'Hari Ini';
                if (isYesterday) return 'Kemarin';

                return dateObj.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            },

            sortLabel() {
                const labels = {
                    'tanggal_desc': 'Terbaru',
                    'tanggal_asc': 'Terlama',
                    'nominal_desc': 'Nominal Terbesar',
                    'nominal_asc': 'Nominal Terkecil',
                    'wallet_asc': 'Nama Dompet',
                    'type_asc': 'Tipe Transaksi'
                };
                return labels[this.sortBy] || 'Terbaru';
            },

            resetFilter() {
                this.searchQuery = '';
                this.sortBy = 'tanggal_desc';
                this.filterType = 'all';
                this.filterWallet = 'all';
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            },

            formatTime(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                }).replace('.', ':');
            }
        }
    }
</script>