<?php

namespace App\Controllers\sistem_utility;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;
use App\Models\sistem_utility\CabangModel;
use App\Models\sistem_utility\UserAllowCabangModel;
use App\Models\UserGroupModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Message;

class User extends BaseCrudController
{
    protected string $controller = 'user';
    protected string $title = 'User';
    protected string $folder_ = 'sistem_utility';
    protected bool $cek_minimal_data = true;
    // protected $breadcrumb;
    protected $UserGroupModel;
    protected $UserModel;
    protected $CabangModel;
    protected $UserAllowCabangModel;
    protected $IndexModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        // Load model
        $this->UserModel = new UserModel();
        $this->IndexModel = $this->UserModel;
        $this->UserGroupModel = new UserGroupModel();
        $this->CabangModel = new CabangModel();
        $this->UserAllowCabangModel = new UserAllowCabangModel();

        $this->breadcrumb = [
            ['href' => $this->controller, 'title' => $this->title]
        ];
    }

    public function form(): string
    {
        if ($validasi = $this->cek_validasi_get_data($this->controller . '/addForm')) return $validasi;
        $data['title'] = $this->title;
        $data['hx_post'] = 'post';
        $data['controller'] =  $this->controller;

        $id = decrypt_url((string)$this->request->getGet('param'));
        $user = [];
        $data['id_edit'] = '';
        if ($id) {
            // ambil data user
            $user = $this->IndexModel->find($id);
            if (!$user) {
                return   alert('Data  ' . $this->title . '  tidak ditemukan. Refresh halaman dan coba lagi !');
            }
            $data['hx_post'] = 'put';
            $data['id_edit'] = $id;
            $id_cabang_arr = $this->UserAllowCabangModel->get_id_cabang($id);
        }

        $user_group = $this->UserGroupModel
            ->where('id_about', (session()->get('id_about')))
            ->findAll();
        $data['opt_ug'] = array_column($user_group, 'groupName', 'groupID');
        $data['id_cabang_allow'] = $id_cabang_arr ?? [];
        $cabang = $this->CabangModel->cabang_by_id_about();
        $data['cabang'] = $cabang;
        $data['user'] = $user;
        return view($this->folder_ . '/' . $this->controller . '/_form', $data);
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

        $userName = $input['userName'];
        $realName = $input['realName'];

        $userGroup = decrypt_url($input['userGroup']);
        if (empty($input['id_cabang']) || !is_array($input['id_cabang'])) {
            return  alert('Pilih akses ke cabang !');
        }
        $id_cabang = $input['id_cabang'];
        $userPassword = $input['userPassword'];
        $userPasswordMatch = $input['userPasswordMatch'];

        if ($userPassword !== $userPasswordMatch) {
            return alert('Password dan ulangi password tidak sama!');
        }
        $data = [
            'realName' => $realName,
            'userName' => $userName,
            'userGroup' => $userGroup,
            'id_about' => (session()->get('id_about')),
            'dtCreated' => time(),
            'createdBy' => (session()->get('user_id')),
        ];

        if ($id) {
            $data['userID']  = $id; //wajib input id
            $cek_user = $this->IndexModel->find($id);
            if (!$cek_user) {
                return  alert('Data  ' . strtolower($this->title) . ' tidak ditemukan !');
            }
            if ($userName != $cek_user['userName']) {
                if ($cek_username_exist = $this->UserModel->cek_username_exist($userName)) {
                    return $cek_username_exist;
                }
            }
            if (!empty(trim($userPassword))) {
                $password_hash = password_hash($userPassword, PASSWORD_DEFAULT);
                $data['userPassword'] = $password_hash;
                $pesan_tambahan .= ' & password diubah ';
            } else {
                unset($data['userPassword']);
            }
        } else {
            if (empty(trim($userPassword))) {
                $userPassword = '123456';
            }
            $password_hash = password_hash($userPassword, PASSWORD_DEFAULT);
            $data['userPassword'] = $password_hash;

            if ($cek_username_exist = $this->UserModel->cek_username_exist($userName)) {
                return $cek_username_exist;
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
            $userGroup_data = $this->UserGroupModel->findAll();
            $arr_ug = array_column($userGroup_data, 'groupName', 'groupID');

            if ($id_baru) {
                //simpan akses cabang
                if ($insert_cabang = $this->UserAllowCabangModel->insert_cabang($id_cabang, $id_baru)) {
                    $pesan_tambahan .= $insert_cabang;
                }


                $data_view['pesan'] = '<i class="bi bi-check"></i> Berhasil menyimpan data '  . $pesan_tambahan . ', #' .  $realName;
                // ???!!ubah 1 jadi variable sesuaikan
                $data_view['data_row'] = ['', $realName, $userName, $arr_ug[$userGroup] ?? '-', '1', '', encrypt_url($id_baru)];
                $data_view['id_en'] = encrypt_url($id_baru);
                service('activitylog')->insert(
                    'input ' . $this->controller . ' baru ' . $realName,
                    $this->controller . '/' . __FUNCTION__
                );
            } else {
                // cek apakah akses cabang masih sama
                $id_cabang_allow = $this->UserAllowCabangModel->get_id_cabang($id);
                $id_cabang_allow = array_map('intval', $id_cabang_allow);
                $id_cabang_decrypt = array_map('intval', array_map('decrypt_url', $id_cabang));
                sort($id_cabang_allow);
                sort($id_cabang_decrypt);

                if ($id_cabang_allow !== $id_cabang_decrypt) {
                    //    hapus dan insert cabang baru,
                    if ($this->UserAllowCabangModel->where('id_user', $id)->delete()) {
                        $insert_cabang = $this->UserAllowCabangModel->insert_cabang($id_cabang, $id);
                    }
                }

                $data_view['pesan'] = '<i class="bi bi-check2-all"></i> Berhasil memperbaharui data '  . $pesan_tambahan . ', #' .  $realName;

                $data_view['data_row_update'] = ['', $realName, $userName, $arr_ug[$userGroup] ?? '-', '1', '', encrypt_url($id)];
                service('activitylog')->insert(
                    'update ' . $this->controller . ' ' . $realName,
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



    // public function delete(): string
    // {
    //     $hx_vals = $this->request->getRawInput();

    //     $id = decrypt_url($hx_vals['id']);
    //     if (!$id || !is_numeric($id)) {
    //         return view('layout_lte/alert/error.php', ['title' => 'Id tidak ditemukan']);
    //     }

    //     $count_data = $this->IndexModel->countAllResults();

    //     if ($count_data <= 1) {
    //         return $this->view_alert_error('Ups! Semua ' . strtolower($this->title)  . ' tidak boleh dihapus. Sisakan minimal 1 ' . strtolower($this->title) . ' agar aplikasi tetap berjalan.');
    //     }

    //     try {
    //         $this->IndexModel->delete($id);

    //         $jumlah = $this->IndexModel->db->affectedRows();
    //         if ($jumlah < 1) {
    //             return $this->view_alert_error();
    //         }
    //     } catch (\Throwable $th) {
    //         return th_alert($th, 'Gagal Menghapus Data ' . ucwords($this->title)  . ' !');
    //     }

    //     service('activitylog')->insert(
    //         'Delete ' . $this->controller . ' #' . $id,
    //         $this->controller . '/' . __FUNCTION__
    //     );

    //     return view('layout_lte/alert/success.php', ['title' => 'Data dihapus  🗑', 'text' => 'Data yang dipilih dihapus']);
    // }
}
