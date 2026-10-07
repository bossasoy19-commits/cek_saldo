<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefaultRoomSeeder extends Seeder
{
    /**
     * Method helper untuk dipanggil dari Controller/Model saat create room baru
     */
    public static function seedAccountsForRoom($roomId, $userId = null)
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $defaultAccounts = [
            // KELOMPOK 1: ASET (Termasuk Dompet)
            ['kode_akun' => '101', 'nama_akun' => 'Kas Tunai', 'kelompok_akun' => 1, 'saldo_normal' => 'DEBIT', 'is_wallet' => 1, 'jenis_wallet' => 'CASH'],
            ['kode_akun' => '102', 'nama_akun' => 'Bank Utama', 'kelompok_akun' => 1, 'saldo_normal' => 'DEBIT', 'is_wallet' => 1, 'jenis_wallet' => 'BANK'],
            ['kode_akun' => '103', 'nama_akun' => 'E-Wallet', 'kelompok_akun' => 1, 'saldo_normal' => 'DEBIT', 'is_wallet' => 1, 'jenis_wallet' => 'EWALLET'],
            ['kode_akun' => '104', 'nama_akun' => 'Piutang', 'kelompok_akun' => 1, 'saldo_normal' => 'DEBIT', 'is_wallet' => 0, 'jenis_wallet' => null],

            // KELOMPOK 2: KEWAJIBAN / UTANG
            ['kode_akun' => '201', 'nama_akun' => 'Utang Pinjaman', 'kelompok_akun' => 2, 'saldo_normal' => 'KREDIT', 'is_wallet' => 0, 'jenis_wallet' => null],

            // KELOMPOK 3: EKUITAS / MODAL
            ['kode_akun' => '301', 'nama_akun' => 'Modal Awal', 'kelompok_akun' => 3, 'saldo_normal' => 'KREDIT', 'is_wallet' => 0, 'jenis_wallet' => null],

            // KELOMPOK 4: PENDAPATAN
            ['kode_akun' => '401', 'nama_akun' => 'Pendapatan Gaji', 'kelompok_akun' => 4, 'saldo_normal' => 'KREDIT', 'is_wallet' => 0, 'jenis_wallet' => null],
            ['kode_akun' => '402', 'nama_akun' => 'Pendapatan Usaha', 'kelompok_akun' => 4, 'saldo_normal' => 'KREDIT', 'is_wallet' => 0, 'jenis_wallet' => null],
            ['kode_akun' => '403', 'nama_akun' => 'Pemasukan Lain-lain', 'kelompok_akun' => 4, 'saldo_normal' => 'KREDIT', 'is_wallet' => 0, 'jenis_wallet' => null],

            // KELOMPOK 5: BEBAN / PENGELUARAN
            ['kode_akun' => '501', 'nama_akun' => 'Makan & Minum', 'kelompok_akun' => 5, 'saldo_normal' => 'DEBIT', 'is_wallet' => 0, 'jenis_wallet' => null],
            ['kode_akun' => '502', 'nama_akun' => 'Transportasi & Bensin', 'kelompok_akun' => 5, 'saldo_normal' => 'DEBIT', 'is_wallet' => 0, 'jenis_wallet' => null],
            ['kode_akun' => '503', 'nama_akun' => 'Tagihan & Listrik', 'kelompok_akun' => 5, 'saldo_normal' => 'DEBIT', 'is_wallet' => 0, 'jenis_wallet' => null],
            ['kode_akun' => '504', 'nama_akun' => 'Belanja Harian', 'kelompok_akun' => 5, 'saldo_normal' => 'DEBIT', 'is_wallet' => 0, 'jenis_wallet' => null],
        ];

        foreach ($defaultAccounts as &$acc) {
            $acc['room_id']    = $roomId;
            $acc['created_at'] = $now;
            $acc['created_by'] = $userId;
        }

        $db->table('tbl_account')->insertBatch($defaultAccounts);
    }

    public function run()
    {
        // Method run kosong atau bisa diisi contoh dummy room id 1 jika ingin ditest
    }
}
