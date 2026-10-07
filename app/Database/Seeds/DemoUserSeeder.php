<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Database\Seeds\DefaultRoomSeeder;

class DemoUserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        // 1. BUAT USER DEMO (GUEST)
        $userData = [
            'nama'        => 'User Demo',
            'email'       => 'demo@saldoku.app',
            'no_wa'       => '081234567890',
            'password'    => password_hash('password123', PASSWORD_BCRYPT),
            'status_akun' => 1, // Aktif
            'created_at'  => $now,
        ];
        $db->table('tbl_user')->insert($userData);
        $userId = $db->insertID();

        // 2. BUAT ROOM DEMO
        $roomData = [
            'nama_room'     => 'Keuangan Pribadi (Demo)',
            'jenis_room'    => 1, // Personal
            'owner_user_id' => $userId,
            'status_room'   => 1,
            'created_at'    => $now,
            'created_by'    => $userId,
        ];
        $db->table('tbl_room')->insert($roomData);
        $roomId = $db->insertID();

        // 3. ASSIGN OWNER KE ROOM MEMBER
        $db->table('tbl_room_member')->insert([
            'room_id'      => $roomId,
            'user_id'      => $userId,
            'role'         => 'OWNER',
            'status_akses' => 1,
            'joined_at'    => $now,
            'created_at'   => $now,
            'created_by'   => $userId,
        ]);

        // 4. GENERATE COA DEFAULT (Menggunakan Helper Seeder Sebelumnya)
        DefaultRoomSeeder::seedAccountsForRoom($roomId, $userId);

        // Ambil ID Akun yang Baru Dibuat untuk Dummy Transaksi
        $kasTunai = $db->table('tbl_account')->where(['room_id' => $roomId, 'kode_akun' => '101'])->get()->getRow();
        $bankUtama = $db->table('tbl_account')->where(['room_id' => $roomId, 'kode_akun' => '102'])->get()->getRow();
        $modalAwal = $db->table('tbl_account')->where(['room_id' => $roomId, 'kode_akun' => '301'])->get()->getRow();
        $gaji      = $db->table('tbl_account')->where(['room_id' => $roomId, 'kode_akun' => '401'])->get()->getRow();
        $makan     = $db->table('tbl_account')->where(['room_id' => $roomId, 'kode_akun' => '501'])->get()->getRow();

        // 5. SAMPLE TRANSAKSI 1: SALDO AWAL BANK (POSTED)
        $db->table('tbl_transaction')->insert([
            'room_id'           => $roomId,
            'no_referensi'      => 'TRX-INIT-001',
            'tanggal_transaksi' => $today,
            'deskripsi'         => 'Saldo Awal Bank Utama',
            'status_transaksi'  => 'POSTED',
            'created_at'        => $now,
            'created_by'        => $userId,
        ]);
        $tx1Id = $db->insertID();

        $db->table('tbl_transaction_detail')->insertBatch([
            ['transaction_id' => $tx1Id, 'account_id' => $bankUtama->account_id, 'debet' => 5000000.00, 'kredit' => 0.00, 'keterangan' => 'Debet Bank', 'created_at' => $now, 'created_by' => $userId],
            ['transaction_id' => $tx1Id, 'account_id' => $modalAwal->account_id, 'debet' => 0.00, 'kredit' => 5000000.00, 'keterangan' => 'Kredit Modal', 'created_at' => $now, 'created_by' => $userId],
        ]);

        // 6. SAMPLE TRANSAKSI 2: PENGELUARAN MAKAN (POSTED)
        $db->table('tbl_transaction')->insert([
            'room_id'           => $roomId,
            'no_referensi'      => 'TRX-202608-001',
            'tanggal_transaksi' => $today,
            'deskripsi'         => 'Makan Siang Resto',
            'status_transaksi'  => 'POSTED',
            'created_at'        => $now,
            'created_by'        => $userId,
        ]);
        $tx2Id = $db->insertID();

        $db->table('tbl_transaction_detail')->insertBatch([
            ['transaction_id' => $tx2Id, 'account_id' => $makan->account_id, 'debet' => 50000.00, 'kredit' => 0.00, 'keterangan' => 'Beban Makan', 'created_at' => $now, 'created_by' => $userId],
            ['transaction_id' => $tx2Id, 'account_id' => $kasTunai->account_id, 'debet' => 0.00, 'kredit' => 50000.00, 'keterangan' => 'Bayar Tunai', 'created_at' => $now, 'created_by' => $userId],
        ]);

        // 7. SAMPLE TRANSAKSI 3: KAS GANTUNG (PENDING)
        $db->table('tbl_transaction')->insert([
            'room_id'           => $roomId,
            'no_referensi'      => 'TRX-202608-002',
            'tanggal_transaksi' => $today,
            'deskripsi'         => 'Beli Perlengkapan (Belum Ada Nota)',
            'status_transaksi'  => 'PENDING',
            'created_at'        => $now,
            'created_by'        => $userId,
        ]);
        $tx3Id = $db->insertID();

        $db->table('tbl_transaction_detail')->insertBatch([
            ['transaction_id' => $tx3Id, 'account_id' => $makan->account_id, 'debet' => 150000.00, 'kredit' => 0.00, 'keterangan' => 'Pending Beban', 'created_at' => $now, 'created_by' => $userId],
            ['transaction_id' => $tx3Id, 'account_id' => $kasTunai->account_id, 'debet' => 0.00, 'kredit' => 150000.00, 'keterangan' => 'Pending Kas', 'created_at' => $now, 'created_by' => $userId],
        ]);
    }
}
