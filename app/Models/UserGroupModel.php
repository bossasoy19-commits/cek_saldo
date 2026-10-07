<?php

namespace App\Models;

use CodeIgniter\Model;

class UserGroupModel extends Model
{
    protected $table            = 'tbl_usergroup';
    protected $primaryKey       = 'groupID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'groupName',
        'id_about',
        'groupDescription',
        'groupStatus',
        'dtCreated',
        'createdBy',
        'dtUpdated',
        'updatedBy',
        'deleted'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation

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

    // AUTO VALIDATION
    protected $validationRules = [
        'groupName' => 'required|min_length[3]',
        'groupStatus' => 'required|numeric',
    ];
    protected $validationMessages = [
        'groupName' => [
            'required' => 'Nama Group wajib diisi',
            'min_length' => 'Nama Group minimal 3 karakter'
        ],
        'groupStatus' => [
            'required' => 'Status Name wajib diisi',
            'numeric' => 'Status harus angka'
        ],

    ];

    private function query_table_data()
    {
        return $this->select('tbl_usergroup.*')
            ->where('id_about', session()->get('id_about'))
            ->where('tbl_usergroup.deleted', 0);
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
