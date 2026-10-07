<div class="container-fluid py-3 px-3 pb-5 mb-4" x-data="incomeReport()">

    <!-- HEADER & PERIODE FILTER -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold text-dark mb-0">Pemasukan</h5>
        <div class="dropdown">
            <button class="btn btn-light btn-sm shadow-sm border rounded-3 dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false" x-text="periodLabel">
                Agustus 2026
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 fs-7">
                <li><a class="dropdown-item" href="#" @click.prevent="setPeriod('this_month', 'September 2026')">September 2026</a></li>
                <li><a class="dropdown-item" href="#" @click.prevent="setPeriod('last_month', 'Agustus 2026')">Agustus 2026</a></li>
                <li><a class="dropdown-item" href="#" @click.prevent="setPeriod('this_year', 'Tahun 2026')">Tahun 2026</a></li>
            </ul>
        </div>
    </div>

    <!-- CARD TOTAL PEMASUKAN -->
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3 text-center">
            <span class="fs-8 text-muted d-block mb-1">Total Pemasukan</span>
            <h3 class="fw-bold text-dark mb-0" x-text="formatRupiah(totalIncome)">Rp 6.700.000</h3>
        </div>
    </div>

    <!-- DONUT CHART (CHART.JS) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 d-flex justify-content-center align-items-center" style="min-height: 220px;">
            <div style="width: 180px; height: 180px;" class="position-relative">
                <canvas id="incomeDonutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- RINCIAN KATEGORI PEMASUKAN (LEGEND LIST) -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <h6 class="fw-bold text-dark fs-7 mb-3">Rincian Kategori</h6>
            <div class="d-flex flex-column gap-3">
                <template x-for="(item, index) in incomeData" :key="index">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 overflow-hidden pe-2">
                            <span class="rounded-circle d-inline-block flex-shrink-0" :style="'width: 10px; height: 10px; background-color: ' + item.color"></span>
                            <span class="fs-7 text-dark text-truncate" x-text="item.category"></span>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="fw-bold fs-7 text-dark" x-text="formatRupiah(item.nominal)"></span>
                            <span class="fs-8 text-muted ms-1" x-text="'(' + getPercentage(item.nominal) + '%)'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAVIGATION BAR (SESUAI MOCKUP 5 TAB) -->
    <div class="fixed-bottom bg-white border-top py-2 px-2 shadow-lg">
        <div class="row text-center g-0">
            <div class="col">
                <a href="<?= base_url('report/posisi') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-house-door fs-5"></i>
                    <span class="fs-8">Posisi</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('report/pemasukan') ?>" class="text-primary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-arrow-down-left-circle-fill fs-5"></i>
                    <span class="fs-8 fw-bold">Pemasukan</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('report/pengeluaran') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-arrow-up-right-circle fs-5"></i>
                    <span class="fs-8">Pengeluaran</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('report/arus-kas') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-bar-chart-line fs-5"></i>
                    <span class="fs-8">Arus Kas</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('transaction/history') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-clock-history fs-5"></i>
                    <span class="fs-8">Riwayat</span>
                </a>
            </div>
        </div>
    </div>

</div>

<!-- CHART.JS INTEGRATION -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .fs-7 {
        font-size: 0.85rem !important;
    }

    .fs-8 {
        font-size: 0.75rem !important;
    }
</style>

<script>
    function incomeReport() {
        return {
            periodLabel: 'Agustus 2026',
            chartInstance: null,

            // Data dummy sesuai mockup
            incomeData: [{
                    category: 'Orang Tua',
                    nominal: 6500000,
                    color: '#3b82f6'
                },
                {
                    category: 'Kembalinya Piutang',
                    nominal: 200000,
                    color: '#10b981'
                }
            ],

            get totalIncome() {
                return this.incomeData.reduce((sum, item) => sum + Number(item.nominal), 0);
            },

            getPercentage(nominal) {
                if (!this.totalIncome) return 0;
                return ((Number(nominal) / this.totalIncome) * 100).toFixed(0);
            },

            setPeriod(key, label) {
                this.periodLabel = label;
                // Panggil fungsi reload data AJAX di sini
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            },

            initChart() {
                const ctx = document.getElementById('incomeDonutChart').getContext('2d');

                this.chartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: this.incomeData.map(item => item.category),
                        datasets: [{
                            data: this.incomeData.map(item => item.nominal),
                            backgroundColor: this.incomeData.map(item => item.color),
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            },

            init() {
                this.$nextTick(() => {
                    this.initChart();
                });
            }
        }
    }
</script>