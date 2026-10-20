<?php

namespace App\Models\room;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table            = 'tbl_room';
    protected $primaryKey       = 'room_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'room_id',
        'nama_room',
        'jenis_room',
        'owner_user_id',
        'status_room',
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
}
