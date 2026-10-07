<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SystemMasterSeeder extends Seeder
{
    public function run()
    {
        // 1. SEEDER MASTER PAKET (tbl_package)
        $packages = [
            [
                'nama_paket'          => 'FREE',
                'harga'               => 0.00,
                'durasi_hari'         => 0, // Selamanya
                'max_room'            => 1,
                'max_wallet_per_room' => 3,
                'max_member_per_room' => 0,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'nama_paket'          => 'PRO_MONTHLY',
                'harga'               => 29000.00,
                'durasi_hari'         => 30,
                'max_room'            => 5,
                'max_wallet_per_room' => 10,
                'max_member_per_room' => 5,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'nama_paket'          => 'PRO_YEARLY',
                'harga'               => 249000.00,
                'durasi_hari'         => 365,
                'max_room'            => 999,
                'max_wallet_per_room' => 999,
                'max_member_per_room' => 999,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('tbl_package')->upsertBatch($packages);

        // 2. SEEDER MASTER PERMISSION (tbl_permission)
        $permissions = [
            // Kategori Dompet / Akun
            ['kode_permission' => 'VIEW_WALLET_BALANCE', 'nama_permission' => 'Melihat Saldo Dompet', 'kategori' => 'Dompet', 'created_at' => date('Y-m-d H:i:s')],
            ['kode_permission' => 'CREATE_WALLET', 'nama_permission' => 'Membuat Dompet Baru', 'kategori' => 'Dompet', 'created_at' => date('Y-m-d H:i:s')],
            ['kode_permission' => 'EDIT_WALLET', 'nama_permission' => 'Mengubah Data Dompet', 'kategori' => 'Dompet', 'created_at' => date('Y-m-d H:i:s')],

            // Kategori Transaksi
            ['kode_permission' => 'VIEW_TRANSACTION', 'nama_permission' => 'Melihat Daftar Transaksi', 'kategori' => 'Transaksi', 'created_at' => date('Y-m-d H:i:s')],
            ['kode_permission' => 'CREATE_TRANSACTION', 'nama_permission' => 'Menambah Transaksi', 'kategori' => 'Transaksi', 'created_at' => date('Y-m-d H:i:s')],
            ['kode_permission' => 'EDIT_TRANSACTION', 'nama_permission' => 'Mengubah Transaksi', 'kategori' => 'Transaksi', 'created_at' => date('Y-m-d H:i:s')],
            ['kode_permission' => 'DELETE_TRANSACTION', 'nama_permission' => 'Menghapus Transaksi', 'kategori' => 'Transaksi', 'created_at' => date('Y-m-d H:i:s')],

            // Kategori Laporan
            ['kode_permission' => 'VIEW_REPORT', 'nama_permission' => 'Melihat Laporan Keuangan', 'kategori' => 'Laporan', 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('tbl_permission')->upsertBatch($permissions);
    }
}
