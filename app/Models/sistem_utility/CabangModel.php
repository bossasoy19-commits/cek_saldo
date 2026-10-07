<?php

namespace App\Models\sistem_utility;

use CodeIgniter\Model;

class CabangModel extends Model
{
    protected $table            = 'tbl_cabang';
    protected $primaryKey       = 'id_cabang';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_cabang',
        'id_about',
        'nama_cabang',
        'alamat',
        'no_hp',
        'email',
        'penanggung_jawab',
        'keterangan',
        'aktif',
        'dtCreated',
        'createdBy',
        'dtUpdated',
        'updatedBy',
        'deleted',
        'deletedBy',
        'dtDeleted',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
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

    public function cabang_by_id_about($aktif = null): array
    {
        $cabang = $this->where('deleted', '0');

        $cabang = $cabang->where('id_about', (session()->get('id_about')));
        if ($aktif != '') {
            $cabang = $cabang->where('aktif', $aktif);
        }

        $cabang = $cabang->findAll();
        return $cabang;
    }

    private function query_table_data()
    {
        return $this->select('tbl_cabang.*, u.realName')
            ->join('tbl_users u', 'u.userID=tbl_cabang.penanggung_jawab', 'left')
            ->where('tbl_cabang.id_about', session()->get('id_about'))
            ->where('tbl_cabang.deleted', '0');
    }

    public function getTableData(): array
    {
        return $this->query_table_data()->findAll();
    }
    public function countTableData(): int
    {
        return $this->query_table_data()->countAllResults();
    }
}
