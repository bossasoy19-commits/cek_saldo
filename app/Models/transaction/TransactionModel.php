<?php

namespace App\Models\transaction;

use CodeIgniter\Model;

class transactionModel extends Model
{
    protected $table            = 'tbl_transaction';
    protected $primaryKey       = 'transaction_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_id',
        'room_id',
        'no_referensi',
        'tanggal_transaksi',
        'deskripsi',
        'status_transaksi',
        'attachment_url',
        'created_at ',
        'created_by ',
        'updated_at ',
        'updated_by ',
        'deleted_at ',
        'deleted_by ',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'update_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getHistoryByRoom($room_id)
    {
        // Kita ambil data master transaksi
        $builder = $this->db->table('tbl_transaction t');
        $builder->select('
            t.transaction_id as id,
            t.tanggal_transaksi as tanggal,
            t.deskripsi as keterangan,
            t.status_transaksi,
            td.transaction_detail_id as td_id,
            td.debet,
            td.kredit,
            acc.nama_akun as category,
            acc.is_wallet,
            acc.jenis_wallet
        ');
        $builder->join('tbl_transaction_detail td', 'td.transaction_id = t.transaction_id');
        $builder->join('tbl_account acc', 'acc.account_id = td.account_id');
        $builder->where('t.room_id', $room_id);
        $builder->orderBy('t.tanggal_transaksi', 'DESC');

        $results = $builder->get()->getResultArray();
        return $results;
    }

    public function formatted_transactions($results): array
    {
        // Mapping agar formatnya mirip dengan data dummy sebelumnya
        $formatted_transactions = [];
        foreach ($results as $row) {
            // Tentukan tipe (pemasukan/pengeluaran) berdasarkan debet/kredit atau saldo normal akun
            // Logika sederhana: jika ada nilai kredit pada akun pendapatan, atau debet pada akun pengeluaran, dll.
            $type = ($row['debet'] > 0) ? 'pemasukan' : 'pengeluaran';
            $nominal = ($row['debet'] > 0) ? $row['debet'] : $row['kredit'];

            $formatted_transactions[] = [
                'id'         => $row['td_id'],
                'type'       => $type,
                'keterangan' => $row['keterangan'],
                'category'   => $row['category'],
                'nominal'    => $nominal,
                'wallet'     => $row['is_wallet'] == 1 ? $row['category'] : 'Umum', // Sesuaikan jika ada relasi wallet khusus
                'tanggal'    => $row['tanggal'],
                'is_pending' => ($row['status_transaksi'] === 'PENDING') ? 1 : 0
            ];
        }

        return $formatted_transactions;
    }
}
