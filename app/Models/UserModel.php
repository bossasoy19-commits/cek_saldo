<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'tbl_users'; // nama tabel di database
    protected $primaryKey = 'user_id';    // primary key tabel

    protected $allowedFields = [
        'nama',
        'email',
        'no_wa',
        'password',
        'status_akun',
        'email_verified_at',
        'wa_verified_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    // Optional: kalo mau aktifin created_at sama updated_at otomatis
    protected $useTimestamps = false; // karena di tabel kamu manual, bukan format datetime
    // AUTO VALIDATION
    protected $validationRules = [
        'nama' => 'required|min_length[5]',
        'email' => 'required|valid_email|min_length[10]',
        'no_wa' => 'required|min_length[10]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama wajib diisi',
            'min_length' => 'Nama minimal 3 karakter'
        ],
        'email' => [
            'required'   => 'Email wajib diisi',
            'valid_email' => 'Format email tidak valid',
            'min_length' => 'Email minimal 10 karakter'
        ],
        'no_wa' => [
            'required' => 'Nomor Whatsapp wajib dipilih',
            'min_length' => 'Nomor Whatsapp minimal 10 digit'
        ],
        'password' => [
            'required' => 'Password gak boleh kosong',
            'min_length' => 'Password minimal 6 karakter',
        ]
    ];

    protected $skipValidation = false; // wajib false agar validasi model aktif


    public function getTableData(): array
    {
        return $this->query_table_data()->findAll();
    }
    public function countTableData(): int
    {
        return $this->query_table_data()->countAllResults();
    }

    public function cek_username_exist($userName): string
    {
        $cek_username = $this->where('userName', $userName)->first();
        if ($cek_username) {
            return  alert('username sudah dipakai, gunakan username yang lain !');
        }
        return '';
    }
}
