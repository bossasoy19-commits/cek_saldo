<?php

namespace App\Models\sistem_utility;

use CodeIgniter\Model;

class UserAllowCabangModel extends Model
{
    protected $table            = 'tbl_user_allow_cabang';
    protected $primaryKey       = 'id_user_allow_cabang';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user_allow_cabang',
        'id_user',
        'id_cabang',
        'allow',
        'update_by',
        'update_at',
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

    public function get_id_cabang(int $id_user): array
    {
        return $this->where('id_user', $id_user)
            ->findColumn('id_cabang') ?? [];
    }

    public function insert_cabang($id_cabang, $id_baru)
    {
        $pesan_tambahan = '';

        if (empty($id_cabang || !is_array($id_cabang))) {
            return;
        }

        try {
            foreach ($id_cabang as $key => $id_cab) {
                $data_allow_cabang = [
                    'id_user' => $id_baru,
                    'id_cabang' => decrypt_url($id_cab),
                    'allow' => 1,
                    'update_by' => (session()->get('user_id')),
                    'update_at' => time(),
                ];
                $this->insert($data_allow_cabang);
            }
        } catch (\Throwable $th) {
            if (ENVIRONMENT != 'production') {
                $pesan_tambahan .= '<br>⚠ Ups, akses cabang belum berhasil dibuat. Silakan buat akses cabang terlebih dahulu.';
            } else {
                // Development
                $text = sprintf(
                    "1. Message : %s\n2. File : %s\n3. Line : %d\n4. Trace :\n%s",
                    $th->getMessage(),
                    $th->getFile(),
                    $th->getLine(),
                    $th->getTraceAsString()
                );

                $pesan_tambahan .= '<br>' . (nl2br($text));
            }
        }

        return $pesan_tambahan;
    }
}
