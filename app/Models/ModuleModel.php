<?php

namespace App\Models;

use CodeIgniter\Model;

class ModuleModel extends Model
{
    protected $table            = 'tbl_module';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['moduleGroup', 'moduleSubGroup', 'menu', 'url', 'class', 'displayInSidebar', 'order'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

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



    public function delete($id = null, bool $purge = false)
    {
        return '<div class="alert alert-danger" role="alert">
                    Delete tidak diizinkan pada model ini.
                </div>';
    }

    public function insert($data = null, bool $returnID = true)
    {
        return '<div class="alert alert-warning" role="alert">
                    Insert tidak diizinkan pada model ini.
                </div>';
    }
}
