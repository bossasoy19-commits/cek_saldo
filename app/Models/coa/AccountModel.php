<?php

namespace App\Models\coa;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table            = 'tbl_account';
    protected $primaryKey       = 'account_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'room_id',
        'kode_akun',
        'nama_akun',
        'parent_id',
        'kelompok_akun',
        'saldo_normal',
        'is_wallet',
        'jenis_wallet',
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
    public function getWalletBalances($room_id)
    {
        return $this->select('tbl_account.account_id, tbl_account.nama_akun, tbl_account.kode_akun, tbl_account.jenis_wallet, 
                COALESCE(SUM(td.debet), 0) - COALESCE(SUM(td.kredit), 0) as saldo')
            ->join('tbl_transaction_detail td', 'td.account_id = tbl_account.account_id', 'left')
            ->join('tbl_transaction t', 't.transaction_id = td.transaction_id AND t.deleted_at IS NULL', 'left')
            ->where('tbl_account.room_id', $room_id)
            ->where('tbl_account.is_wallet', 1)
            ->where('tbl_account.deleted_at', null)
            ->groupBy('tbl_account.account_id')
            ->findAll();
    }
}
