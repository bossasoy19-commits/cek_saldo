<?php

namespace App\Models;

use CodeIgniter\Model;

class VariableModel extends Model
{
    protected $table            = 'tbl_variable';
    protected $primaryKey       = 'varID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'varID',
        'varGroup',
        'varName',
        'description',
        'notes',
        'tampil',
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
    protected $validationRules = [
        'varGroup' => 'required|min_length[2]',
        'varName' => 'required|numeric',
        'description' => 'required|min_length[3]',
        'tampil' => 'required|numeric',
    ];

    protected $validationMessages = [
        'varGroup' => [
            'required' => 'Group Variabel belum dipilih',
            'min_length' => 'Group Variabel minimal 3 karakter'
        ],
        'varName' => [
            'required' => 'Nama variabel wajib diisi',
            'numeric' => 'Nama variabel harus angka'
        ],
        'description' => [
            'required' => 'Deskripsi wajib dipilih',
            'min_length' => 'Deskripsi minimal 3 karakter'
        ],
        'tampil' => [
            'required' => 'Tampil wajib dipilih',
            'numeric' => 'Tampil harus angka'
        ],
    ];
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
}
