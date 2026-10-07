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

    .border-white-10 {
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    /* Custom Styling Tab Bootstrap agar seperti Pill Button */
    .nav-pills-custom .nav-link {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
    }

    .nav-pills-custom .nav-link.active.tab-expense {
        background-color: #ffffff !important;
        color: #dc3545 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        font-weight: 700;
    }

    .nav-pills-custom .nav-link.active.tab-income {
        background-color: #ffffff !important;
        color: #198754 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        font-weight: 700;
    }
</style>

<div class="container-fluid py-3 px-3 pb-5 mb-4" x-data="financialReport()">

    <!-- HEADER & PERIODE FILTER -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light rounded-circle p-0 shadow-sm border d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-left fs-5 text-dark"></i>
            </a>
            <h6 class="fw-bold text-dark mb-0">Laporan Keuangan</h6>
        </div>
        <button class="btn btn-light rounded-circle p-0 shadow-sm border d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" data-bs-toggle="modal" data-bs-target="#periodModal">
            <i class="bi bi-calendar3 fs-6 text-primary"></i>
        </button>
    </div>

    <!-- SUMMARY CARD (CASH FLOW REKAP) -->
    <div class="card border-0 shadow-sm rounded-4 bg-primary text-white mb-3 overflow-hidden position-relative">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fs-8 text-white-50 text-uppercase fw-semibold tracking-wider" x-text="periodLabel"></span>
                <span class="badge bg-white text-primary fs-8 rounded-pill px-2 py-1" x-text="netStatusText"></span>
            </div>

            <div class="mb-3">
                <span class="fs-8 text-white-50 d-block">Arus Kas Bersih (Net Flow)</span>
                <h4 class="fw-bold mb-0" x-text="formatRupiah(netCashFlow)"></h4>
            </div>

            <div class="row g-2 pt-2 border-top border-white-10">
                <!-- Total Pemasukan -->
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                            <i class="bi bi-arrow-down-left text-white fs-8"></i>
                        </div>
                        <div class="overflow-hidden">
                            <span class="fs-8 text-white-50 d-block text-truncate">Pemasukan</span>
                            <span class="fw-semibold fs-7 text-truncate d-block" x-text="formatRupiah(totalIncome)"></span>
                        </div>
                    </div>
                </div>
                <!-- Total Pengeluaran -->
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                            <i class="bi bi-arrow-up-right text-white fs-8"></i>
                        </div>
                        <div class="overflow-hidden">
                            <span class="fs-8 text-white-50 d-block text-truncate">Pengeluaran</span>
                            <span class="fw-semibold fs-7 text-truncate d-block" x-text="formatRupiah(totalExpense)"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB NAVIGATOR (BOOTSTRAP NATIVE TAB) -->
    <ul class="nav nav-pills nav-pills-custom nav-justified p-1 bg-light rounded-3 border mb-3" id="reportTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link tab-expense active w-100" id="expense-tab" data-bs-toggle="tab" data-bs-target="#expense-tab-pane" type="button" role="tab">
                <i class="bi bi-pie-chart-fill me-1"></i> Pengeluaran
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link tab-income w-100" id="income-tab" data-bs-toggle="tab" data-bs-target="#income-tab-pane" type="button" role="tab">
                <i class="bi bi-graph-up-arrow me-1"></i> Pemasukan
            </button>
        </li>
    </ul>

    <!-- DATA DUMMY LAPORAN -->
    <?php
    $raw_expenses = $expense_categories ?? [
        ['category' => 'Makanan & Minuman', 'nominal' => 1250000, 'count' => 14, 'color' => 'bg-danger'],
        ['category' => 'Belanja Harian', 'nominal' => 850000, 'count' => 5, 'color' => 'bg-warning'],
        ['category' => 'Transportasi & Bensin', 'nominal' => 350000, 'count' => 8, 'color' => 'bg-info'],
        ['category' => 'Tagihan & Utilitas', 'nominal' => 250000, 'count' => 2, 'color' => 'bg-primary'],
        ['category' => 'Lain-lain', 'nominal' => 120000, 'count' => 3, 'color' => 'bg-secondary'],
    ];

    $raw_incomes = $income_categories ?? [
        ['category' => 'Gaji & Utama', 'nominal' => 7500000, 'count' => 1, 'color' => 'bg-success'],
        ['category' => 'Hasil Usaha', 'nominal' => 1500000, 'count' => 3, 'color' => 'bg-teal'],
        ['category' => 'Bonus / Tunjangan', 'nominal' => 500000, 'count' => 1, 'color' => 'bg-info'],
    ];
    ?>

    <!-- TAB CONTENT CONTAINER -->
    <div class="tab-content" id="reportTabContent">

        <!-- TAB 1: PENGELUARAN -->
        <div class="tab-pane fade show active" id="expense-tab-pane" role="tabpanel" tabindex="0">
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center px-1 mb-1">
                    <span class="fw-bold text-dark fs-7">Rincian Pengeluaran</span>
                    <span class="fs-8 text-muted" x-text="expenseCategories.length + ' Kategori'"></span>
                </div>

                <template x-for="item in expenseCategories" :key="'exp-' + item.category">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="overflow-hidden flex-grow-1 pe-2">
                                    <h6 class="fw-bold text-dark fs-7 mb-0 text-truncate" x-text="item.category"></h6>
                                    <span class="fs-8 text-muted" x-text="item.count + ' Transaksi'"></span>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="fw-bold text-danger fs-7 d-block" x-text="formatRupiah(item.nominal)"></span>
                                    <span class="fs-8 text-muted" x-text="getPercentage(item.nominal, totalExpense) + '%'"></span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar rounded-pill" :class="item.color" role="progressbar" :style="'width: ' + getPercentage(item.nominal, totalExpense) + '%'"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- TAB 2: PEMASUKAN -->
        <div class="tab-pane fade" id="income-tab-pane" role="tabpanel" tabindex="0">
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center px-1 mb-1">
                    <span class="fw-bold text-dark fs-7">Rincian Pemasukan</span>
                    <span class="fs-8 text-muted" x-text="incomeCategories.length + ' Kategori'"></span>
                </div>

                <template x-for="item in incomeCategories" :key="'inc-' + item.category">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="overflow-hidden flex-grow-1 pe-2">
                                    <h6 class="fw-bold text-dark fs-7 mb-0 text-truncate" x-text="item.category"></h6>
                                    <span class="fs-8 text-muted" x-text="item.count + ' Transaksi'"></span>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="fw-bold text-success fs-7 d-block" x-text="formatRupiah(item.nominal)"></span>
                                    <span class="fs-8 text-muted" x-text="getPercentage(item.nominal, totalIncome) + '%'"></span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-success rounded-pill" role="progressbar" :style="'width: ' + getPercentage(item.nominal, totalIncome) + '%'"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>

    <!-- MODAL PILIH PERIODE LAPORAN -->
    <div class="modal fade" id="periodModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">Pilih Periode Laporan</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="list-group list-group-flush border-0">
                        <button type="button" class="list-group-item list-group-item-action border-0 rounded-3 py-2 fs-7 d-flex justify-content-between align-items-center" :class="selectedPeriod === 'this_month' ? 'bg-primary-subtle text-primary fw-bold' : ''" @click="setPeriod('this_month', 'September 2026')" data-bs-dismiss="modal">
                            <span>Bulan Ini (September 2026)</span>
                            <i class="bi bi-check2" x-show="selectedPeriod === 'this_month'"></i>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action border-0 rounded-3 py-2 fs-7 d-flex justify-content-between align-items-center" :class="selectedPeriod === 'last_month' ? 'bg-primary-subtle text-primary fw-bold' : ''" @click="setPeriod('last_month', 'Agustus 2026')" data-bs-dismiss="modal">
                            <span>Bulan Lalu (Agustus 2026)</span>
                            <i class="bi bi-check2" x-show="selectedPeriod === 'last_month'"></i>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action border-0 rounded-3 py-2 fs-7 d-flex justify-content-between align-items-center" :class="selectedPeriod === 'this_year' ? 'bg-primary-subtle text-primary fw-bold' : ''" @click="setPeriod('this_year', 'Tahun 2026')" data-bs-dismiss="modal">
                            <span>Tahun Ini (2026)</span>
                            <i class="bi bi-check2" x-show="selectedPeriod === 'this_year'"></i>
                        </button>
                    </div>
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
                <a href="<?= base_url('transaction/history') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-clock-history fs-5"></i>
                    <span class="fs-8">Riwayat</span>
                </a>
            </div>
            <div class="col-3">
                <a href="<?= base_url('report') ?>" class="text-primary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-bar-chart-line-fill fs-5"></i>
                    <span class="fs-8 fw-semibold">Laporan</span>
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

<script>
    function financialReport() {
        return {
            selectedPeriod: 'this_month',
            periodLabel: 'September 2026',

            expenseCategories: <?= json_encode($raw_expenses) ?>,
            incomeCategories: <?= json_encode($raw_incomes) ?>,

            get totalExpense() {
                return this.expenseCategories.reduce((sum, item) => sum + Number(item.nominal), 0);
            },

            get totalIncome() {
                return this.incomeCategories.reduce((sum, item) => sum + Number(item.nominal), 0);
            },

            get netCashFlow() {
                return this.totalIncome - this.totalExpense;
            },

            get netStatusText() {
                return this.netCashFlow >= 0 ? 'Surplus' : 'Defisit';
            },

            getPercentage(nominal, total) {
                if (!total || total === 0) return 0;
                return ((Number(nominal) / Number(total)) * 100).toFixed(1);
            },

            setPeriod(key, label) {
                this.selectedPeriod = key;
                this.periodLabel = label;
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }
        }
    }
</script>