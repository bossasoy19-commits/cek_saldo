<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{

    public function dataRoom($input, $userId, $now = null): array
    {
        $now = $now ?? date('Y-m-d H:i:s');
        return [
            'nama_room'     => $input['nama_room'] ?? 'Keuangan Pribadi',
            'jenis_room'    => $input['jenis_room'] ?? 1,
            'owner_user_id' => $userId,
            'status_room'   => 1,
            'created_at'    => $now,
            'created_by'    => $userId,
        ];
    }
    public function dataMember($roomId, $userId, $now = null): array
    {
        $now = $now ?? date('Y-m-d H:i:s');
        return [
            'room_id'      => $roomId,
            'user_id'      => $userId,
            'role'         => 'OWNER',
            'status_akses' => 1,
            'joined_at'    => $now,
            'created_at'   => $now,
            'created_by'   => $userId,
        ];
    }
    public function dataSaldoAwalAccount($roomId, $parentAccountId, $userId, $now = null): array
    {
        $now = $now ?? date('Y-m-d H:i:s');
        return [
            'room_id'       => $roomId,
            'kode_akun'     => '3-9000',
            'nama_akun'     => 'Modal / Saldo Awal',
            'parent_id'     => $parentAccountId['3'],
            'kelompok_akun' => 3,
            'saldo_normal'  => 'KREDIT',
            'is_wallet'     => 0,
            'created_at'    => $now,
            'created_by'    => $userId,
        ];
    }
    public function dataWalletAccount($roomId, $kode_akun, $wallet, $parentAccountId, $userId, $now = null): array
    {
        $now = $now ?? date('Y-m-d H:i:s');
        return [
            'room_id'       => $roomId,
            'kode_akun'     => $kode_akun,
            'nama_akun'     => $wallet['nama'],
            'parent_id'     => $parentAccountId['1'],
            'kelompok_akun' => 1,
            'saldo_normal'  => 'DEBIT',
            'is_wallet'     => 1,
            'jenis_wallet'  => $this->jenisWallet($wallet),
            'created_at'    => $now,
            'created_by'    => $userId,
        ];
    }
    public function dataTxHeader($roomId, $userId, $now = null): array
    {
        $now = $now ?? date('Y-m-d H:i:s');
        return [
            'room_id'           => $roomId,
            'no_referensi'      => 'INIT-' . date('YmdHis'),
            'tanggal_transaksi' => date('Y-m-d'),
            'deskripsi'         => 'Pencatatan Saldo Awal Dompet',
            'status_transaksi'  => 'POSTED',
            'created_at'        => $now,
            'created_by'        => $userId,
        ];
    }
    public function defaultSubAccounts($roomId, $parentAccountId, $userId, $now = null): array
    {
        $now = $now ?? date('Y-m-d H:i:s');
        return [
            [
                'room_id'       => $roomId,
                'kode_akun'     => '4-1001',
                'nama_akun'     => 'Pendapatan Lain-lain',
                'parent_id'     => $parentAccountId['4'],
                'kelompok_akun' => 4,
                'saldo_normal'  => 'KREDIT',
                'is_wallet'     => 0,
                'created_at'    => $now,
                'created_by'    => $userId,
            ],
            [
                'room_id'       => $roomId,
                'kode_akun'     => '5-1001',
                'nama_akun'     => 'Beban Operasional / Harian',
                'parent_id'     => $parentAccountId['5'],
                'kelompok_akun' => 5,
                'saldo_normal'  => 'DEBIT',
                'is_wallet'     => 0,
                'created_at'    => $now,
                'created_by'    => $userId,
            ],
        ];
    }
    public function dataCoaHeader($roomId, $userId, $now = null): array
    {
        $now = $now ?? date('Y-m-d H:i:s');
        return [
            [
                'room_id'       => $roomId,
                'kode_akun'     => '1-1000',
                'nama_akun'     => 'Aset Lancar',
                'parent_id'     => null,
                'kelompok_akun' => 1, // Aset
                'saldo_normal'  => 'DEBIT',
                'is_wallet'     => 0,
                'created_at'    => $now,
                'created_by'    => $userId,
            ],
            [
                'room_id'       => $roomId,
                'kode_akun'     => '2-1000',
                'nama_akun'     => 'Kewajiban / Utang',
                'parent_id'     => null,
                'kelompok_akun' => 2, // Liabilitas/Utang
                'saldo_normal'  => 'KREDIT',
                'is_wallet'     => 0,
                'created_at'    => $now,
                'created_by'    => $userId,
            ],
            [
                'room_id'       => $roomId,
                'kode_akun'     => '3-1000',
                'nama_akun'     => 'Ekuitas / Modal',
                'parent_id'     => null,
                'kelompok_akun' => 3, // Ekuitas
                'saldo_normal'  => 'KREDIT',
                'is_wallet'     => 0,
                'created_at'    => $now,
                'created_by'    => $userId,
            ],
            [
                'room_id'       => $roomId,
                'kode_akun'     => '4-1000',
                'nama_akun'     => 'Pendapatan',
                'parent_id'     => null,
                'kelompok_akun' => 4, // Pendapatan
                'saldo_normal'  => 'KREDIT',
                'is_wallet'     => 0,
                'created_at'    => $now,
                'created_by'    => $userId,
            ],
            [
                'room_id'       => $roomId,
                'kode_akun'     => '5-1000',
                'nama_akun'     => 'Beban / Pengeluaran',
                'parent_id'     => null,
                'kelompok_akun' => 5, // Beban
                'saldo_normal'  => 'DEBIT',
                'is_wallet'     => 0,
                'created_at'    => $now,
                'created_by'    => $userId,
            ],
        ];
    }

    public function parentAccountId($roomId): array
    {
        $db = \Config\Database::connect();
        $parentAset = $db->table('tbl_account')
            ->where(['room_id' => $roomId, 'kode_akun' => '1-1000'])
            ->get()->getRowArray();

        $parentLiabilitas = $db->table('tbl_account')
            ->where(['room_id' => $roomId, 'kode_akun' => '2-1000'])
            ->get()->getRowArray();
        $parentEkuitas = $db->table('tbl_account')
            ->where(['room_id' => $roomId, 'kode_akun' => '3-1000'])
            ->get()->getRowArray();

        $parentPendapatan = $db->table('tbl_account')
            ->where(['room_id' => $roomId, 'kode_akun' => '4-1000'])
            ->get()->getRowArray();
        $parentBeban = $db->table('tbl_account')
            ->where(['room_id' => $roomId, 'kode_akun' => '5-1000'])
            ->get()->getRowArray();

        $data = [
            '1' => $parentAset['account_id'],
            '2' => $parentLiabilitas['account_id'],
            '3' => $parentEkuitas['account_id'],
            '4' => $parentPendapatan['account_id'],
            '5' => $parentBeban['account_id']
        ];
        return $data;
    }

    public function jenisWallet($wallet): string
    {
        $namaLower = strtolower($wallet['nama']);
        $jenisWallet = 'CASH';
        if (str_contains($namaLower, 'ovo') || str_contains($namaLower, 'gopay') || str_contains($namaLower, 'dana')) {
            $jenisWallet = 'EWALLET';
        } elseif (str_contains($namaLower, 'bri') || str_contains($namaLower, 'bca') || str_contains($namaLower, 'mandiri') || str_contains($namaLower, 'bank')) {
            $jenisWallet = 'BANK';
        }
        return $jenisWallet;
    }
}
