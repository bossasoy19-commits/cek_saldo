<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDetailModel extends Model
{
    protected $table            = 'tbl_users_detail';
    protected $primaryKey       = 'userIDdetail';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'userIDdetail',
        'tempat_lahir',
        'tgl_lahir',
        'status_kawin',
        'status_keluarga',
        'email',
        'telp',
        'no_hp',
        'kode_pos',
        'alamat',
        'kota',
        'provinsi',
        'kewarganegaraan',
        'bio',
        'hobi',
        'bahasa',
        'notifikasi',
        'thema',
        'foto_profile',
        'tanda_tangan_digital',
        'ktp',
        'nomor_keanggotaan',
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
    protected $validationRules = [
        'userIDdetail' => 'required|numeric|is_not_unique[tbl_users.userID]', //is_not_unique[users.userID] → cek apakah userID ada di tabel users
        // 'status_kawin' => 'numeric',
        // 'status_keluarga' => 'numeric',
        // 'kode_pos' => 'numeric',
        // 'bahasa' => 'numeric',
        // 'notifikasi' => 'numeric',
        // 'thema' => 'numeric',
        // 'email' => 'valid_email',
    ];
    protected $validationMessages = [
        'userIDdetail' => [
            'required' => 'userID wajib diisi',
            'numeric' => 'userID Harus Angka',
            'is_not_unique' => 'User ID tidak ditemukan dalam database.',

        ],
        // 'status_kawin' => [
        //     'numeric' => 'Status Kawin Harus Angka',
        // ],
        // 'status_kawin' => [
        //     'numeric' => 'Status Kawin Harus Angka',
        // ],
        // 'status_keluarga' => [
        //     'numeric' => 'Status Keluarga Harus Angka',
        // ],
        // 'bahasa' => [
        //     'numeric' => 'Bahasa Harus Angka',
        // ],
        // 'notifikasi' => [
        //     'numeric' => 'Notifikasi Harus Angka',
        // ],
        // 'thema' => [
        //     'numeric' => 'Thema Harus Angka',
        // ],
        // 'email' => [
        //     'valid_email' => 'Email harus yang valid !',
        // ],

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
