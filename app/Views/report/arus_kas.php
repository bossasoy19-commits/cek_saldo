<div class="container-fluid py-3 px-3 pb-5 mb-4" x-data="cashFlowReport()">

    <!-- HEADER & PERIODE FILTER -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold text-dark mb-0">Arus Kas</h5>
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

    <!-- CARD REKAP PEMASUKAN & PENGELUARAN -->
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fs-7 text-muted">Total Pemasukan</span>
                <span class="fw-bold fs-7 text-dark" x-text="formatRupiah(totalIncome)">Rp 6.700.000</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="fs-7 text-muted">Total Pengeluaran</span>
                <span class="fw-bold fs-7 text-dark" x-text="formatRupiah(totalExpense)">Rp 725.000</span>
            </div>
        </div>
    </div>

    <!-- CARD SURPLUS / DEFISIT (BOX HIJAU/MERAH) -->
    <div class="card border-0 shadow-sm rounded-4 mb-3" :class="netCashFlow >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'">
        <div class="card-body p-3 text-center">
            <span class="fs-8 fw-semibold text-uppercase d-block mb-1" x-text="netCashFlow >= 0 ? 'Selisih (Surplus)' : 'Selisih (Defisit)'"></span>
            <h3 class="fw-bold mb-0" x-text="formatRupiah(netCashFlow)">Rp 5.975.000</h3>
        </div>
    </div>

    <!-- BAR CHART (CHART.JS) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div style="height: 200px;" class="position-relative">
                <canvas id="cashFlowBarChart"></canvas>
            </div>
            <!-- LEGEND KUSTOM -->
            <div class="d-flex justify-content-center gap-4 mt-3 pt-2 border-top">
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-1 d-inline-block" style="width: 12px; height: 12px; background-color: #198754;"></span>
                    <span class="fs-8 text-dark fw-semibold">Pemasukan</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-1 d-inline-block" style="width: 12px; height: 12px; background-color: #dc3545;"></span>
                    <span class="fs-8 text-dark fw-semibold">Pengeluaran</span>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAVIGATION BAR (5 TAB) -->
    <div class="fixed-bottom bg-white border-top py-2 px-2 shadow-lg">
        <div class="row text-center g-0">
            <div class="col">
                <a href="<?= base_url('report/posisi') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-house-door fs-5"></i>
                    <span class="fs-8">Posisi</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('report/pemasukan') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-arrow-down-left-circle fs-5"></i>
                    <span class="fs-8">Pemasukan</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('report/pengeluaran') ?>" class="text-secondary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-arrow-up-right-circle fs-5"></i>
                    <span class="fs-8">Pengeluaran</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('report/arus-kas') ?>" class="text-primary text-decoration-none d-flex flex-column align-items-center">
                    <i class="bi bi-bar-chart-line-fill fs-5"></i>
                    <span class="fs-8 fw-bold">Arus Kas</span>
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
    function cashFlowReport() {
        return {
            periodLabel: 'Agustus 2026',
            chartInstance: null,

            // Data dummy sesuai mockup
            totalIncome: 6700000,
            totalExpense: 725000,

            get netCashFlow() {
                return this.totalIncome - this.totalExpense;
            },

            setPeriod(key, label) {
                this.periodLabel = label;
                // Panggil API / AJAX update data
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            },

            formatJuta(value) {
                if (value >= 1000000) {
                    return (value / 1000000) + ' Jt';
                }
                return value;
            },

            initChart() {
                const ctx = document.getElementById('cashFlowBarChart').getContext('2d');

                this.chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Pemasukan', 'Pengeluaran'],
                        datasets: [{
                            data: [this.totalIncome, this.totalExpense],
                            backgroundColor: ['#198754', '#dc3545'], // Hijau & Merah
                            borderRadius: 6,
                            barThickness: 45
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => ' ' + this.formatRupiah(context.raw)
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => this.formatJuta(value),
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: '#e9ecef'
                                }
                            },
                            x: {
                                display: false // Sembunyikan label X karena sudah digantikan Legend Kustom
                            }
                        }
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