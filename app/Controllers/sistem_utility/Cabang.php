<?php

namespace App\Controllers\sistem_utility;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;
use App\Models\sistem_utility\CabangModel;
use App\Models\sistem_utility\UserAllowCabangModel;
use App\Models\UserGroupModel;
use App\Models\UserModel;

class Cabang extends BaseCrudController
{
    protected string $title = 'Cabang';
    protected string $controller = 'cabang';
    protected string $folder_ = 'sistem_utility';
    protected $CabangModel;
    protected $IndexModel;
    protected $UserModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        // Load model
        $this->UserModel = new UserModel();
        $this->CabangModel = new CabangModel();
        $this->IndexModel = $this->CabangModel;

        $this->breadcrumb = [
            ['href' => $this->controller, 'title' => $this->title]
        ];
    }

    protected function prepareFormData(array $data): array
    {
        $user = $this->UserModel->getTableData();
        if ($user) {
            $data['opt_penanggung_jawab'] = array_column($user, 'realName', 'userID');
        }
        return $data;
    }


    public function save(): string
    {

        $method = strtolower($this->request->getMethod(true));
        $input_validasi = $this->cek_validasi_save($method, $this->controller);
        $validasi = $input_validasi['validasi'];
        $input = $input_validasi['input'];
        $id = $input_validasi['id']; //sudah decrypt
        $pesan_tambahan = $this->title;
        if ($validasi) {
            return alert($validasi);
        }
        // ============================================================
        $nama_cabang = $input['nama_cabang'];
        $alamat = $input['alamat'];
        $no_hp = $input['no_hp'];
        $email = $input['email'];
        $penanggung_jawab = decrypt_url($input['penanggung_jawab']);
        $keterangan = $input['keterangan'];
        $aktif = $input['aktif'];
        $data = [
            'id_about' => (session()->get('id_about')),
            'nama_cabang' => $nama_cabang,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'email' => $email,
            'keterangan' => $keterangan,
            'aktif' => $aktif,
            'dtCreated' => time(),
            'createdBy' => (session()->get('user_id')),
        ];

        if (!empty(trim($penanggung_jawab))) {
            $data['penanggung_jawab'] = $penanggung_jawab;
        }

        if ($id) {
            $data['id_cabang']  = $id; //wajib input id
            $data_lama = $this->IndexModel->find($id);
            if (!$data_lama) {
                return  alert('Data ' . strtolower($this->title) . ' tidak ditemukan !');
            }
        }
        // save data
        try {
            $result = $this->IndexModel->save($data);
        } catch (\Throwable $th) {
            return th_no_alert($th);
        }

        if ($result) {
            $id_baru = $this->IndexModel->getInsertID(); //jika insert maka menghasilkan id, jika update maka 0
            $penanggung_jawab_arr = $this->UserModel->where('userID', $penanggung_jawab)->findColumn('realName');
            if ($id_baru) {

                $data_view['pesan'] = '<i class="bi bi-check"></i> Berhasil menyimpan data '  . $pesan_tambahan . ', #' .  $nama_cabang;

                $data_view['data_row'] = ['', $nama_cabang, $alamat, $no_hp, $email, $penanggung_jawab_arr[0] ?? '-', $keterangan, $aktif, '', encrypt_url($id_baru)];
                $data_view['id_en'] = encrypt_url($id_baru);
                service('activitylog')->insert(
                    'input ' . $this->controller . ' baru ' . $nama_cabang,
                    $this->controller . '/' . __FUNCTION__
                );
            } else {

                $data_view['pesan'] = '<i class="bi bi-check2-all"></i> Berhasil memperbaharui data '  . $pesan_tambahan . ', #' .  $nama_cabang;

                $data_view['data_row_update'] = ['',  $nama_cabang, $alamat, $no_hp, $email, $penanggung_jawab_arr[0] ?? '-', $keterangan, $aktif, '', encrypt_url($id)];
                service('activitylog')->insert(
                    'update ' . $this->controller . ' ' . $nama_cabang,
                    $this->controller . '/' . __FUNCTION__
                );
            }
            return view('layout_lte/partials/sukses', $data_view);
        } else {
            $errors = $this->IndexModel->errors();

            $errorString = '<ul>';
            foreach ($errors as $field => $message) {
                $errorString .= "<li>{$message}</li>";
            }

            $errorString .= '</ul>';

            return alert($errorString);
        }
    }
}
