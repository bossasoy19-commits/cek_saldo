<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Additional extends BaseConfig
{
    // Deklarasi variabel global
    public $app_name = 'App Akuntansi';
    public $app_name_view = 'App Akuntansi';



    public $group_module_kai = [
        1 => ["Sistem Utility", "fas fa-cogs", 1],
        2 => ["Settings", "fas fa-cog", 2],
        3 => ["Master", "fas fa-coins", 3],
        9 => ["Laporan", "fas fa-clipboard", 9],

    ];
    public $group_module = [
        1 => ["Sistem Utility", "bi bi-activity", 1],
        2 => ["Settings", "bi bi-building-gear", 2],
        3 => ["Master", "bi bi-database", 3],
        9 => ["Laporan", "bi bi-clipboard-data", 9],

    ];



    public $status_invoice = [
        0 => '<span class="badge bg-danger">Menunggu pembayaran  <i class=" ti ti-clock"></i></span>',
        1 => '<span class="badge bg-warning">Menunggu pembayaran  <i class=" ti ti-clock"></i></span>',
        2 => '<span class="badge bg-success">Lunas <i class=" ti ti-check"></i></span>',
        3 => '<span class="badge bg-primary">Perlu ditinjau  <i class="ti ti-loader-quarter"></i></span>',
        4 => '<span class="badge bg-dark">Dibatalkan   <i class="ti ti-x"></i></span>',

    ];

    public $status_invoice_pdf = [
        0 => '<span class="badge bg-danger">Menunggu pembayaran ⏳</span>',
        1 => '<span class="badge bg-warning">Menunggu pembayaran ⏳</span>',
        2 => '<span class="badge bg-success">Lunas ✔</span>',
        3 => '<span class="badge bg-primary">Perlu ditinjau 🔄</span>',
        4 => '<span class="badge bg-dark">Dibatalkan ❌</span>',
    ];




    public $status_pendaftaran = [
        0 => '<span class="badge bg-warning"><i class=" ti ti-clock"></i> Pending</span>',
        1 => '<span class="badge bg-primary"><i class=" ti ti-circle-check"> Terdaftar</i></span>',
        2 => '<span class="badge bg-success"><i class=" ti ti-certificate"> Selesai</span>',
        3 => '<span class="badge bg-danger"><i class=" ti ti-zoom-cancel"> Dibatalkan</span>',

    ];
}
