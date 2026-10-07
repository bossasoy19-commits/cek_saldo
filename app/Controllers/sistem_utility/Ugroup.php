<?php

namespace App\Controllers\sistem_utility;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;
use App\Models\sistem_utility\CabangModel;
use App\Models\sistem_utility\UserAllowCabangModel;
use App\Models\UserGroupModel;
use App\Models\UserModel;
use PhpParser\Node\Stmt\Return_;

class Ugroup extends BaseCrudController
{
    protected string $title = 'User Group';
    protected string $controller = 'ugroup';
    protected string $folder_ = 'sistem_utility';
    protected string $size_modal = 'sm';
    protected bool $cek_minimal_data = true;

    protected $UserGroupModel;
    protected $CabangModel;
    protected $UserAllowCabangModel;
    protected $IndexModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        // Load model

        $this->UserGroupModel = new UserGroupModel();
        $this->IndexModel = $this->UserGroupModel;
        $this->CabangModel = new CabangModel();
        $this->UserAllowCabangModel = new UserAllowCabangModel();

        $this->breadcrumb = [
            ['href' => $this->controller, 'title' => $this->title]
        ];
    }

    protected function prepareInputData(array $input,  $id, string $pesan_tambahan): array
    {
        $groupName = $input['groupName'];
        $groupDescription = $input['groupDescription'];
        $groupStatus = $input['groupStatus'];



        $data = [
            'id_about' => (session()->get('id_about')),
            'groupName' => $groupName,
            'groupStatus' => $groupStatus,
            'groupDescription' => $groupDescription,
            'dtCreated' => time(),
            'createdBy' => (session()->get('user_id')),
        ];
        $data['proses_kolom'] = $groupName;
        if ($id) {
            // update
            $data['groupID']  = $id; //wajib input id
            $data['data_lama'] = $this->IndexModel->find($id);
            $data['pesan_update'] = '<i class="bi bi-check"></i> Berhasil menyimpan data '  . $pesan_tambahan . ', #' .  $groupName;
            $data['data_row_update'] = ['',  $groupName, $groupStatus, '', encrypt_url($id)];
        } else {
            // inser
            $data['data_row'] = ['', $groupName, $groupStatus, ''];
            $data['pesan'] = '<i class="bi bi-check"></i> Berhasil menyimpan data '  . $pesan_tambahan . ', #' .  $groupName;
        }



        return $data;
    }

    // public function save(): string
    // {

    //     $method = strtolower($this->request->getMethod(true));
    //     $input_validasi = $this->cek_validasi_save($method, $this->controller);
    //     $validasi = $input_validasi['validasi'];
    //     $input = $input_validasi['input'];
    //     $id = $input_validasi['id']; //sudah decrypt
    //     $pesan_tambahan = $this->title;
    //     if ($validasi) {
    //         return alert($validasi);
    //     }
    //     // ============================================================

    //     $groupName = $input['groupName'];
    //     $groupDescription = $input['groupDescription'];
    //     $groupStatus = $input['groupStatus'];



    //     $data = [
    //         'id_about' => (session()->get('id_about')),
    //         'groupName' => $groupName,
    //         'groupStatus' => $groupStatus,
    //         'groupDescription' => $groupDescription,
    //         'dtCreated' => time(),
    //         'createdBy' => (session()->get('user_id')),
    //     ];

    //     if ($id) {
    //         $data['groupID']  = $id; //wajib input id
    //         $data_lama = $this->IndexModel->find($id);
    //         if (!$data_lama) {
    //             return  alert('Data ' . strtolower($this->title) . ' tidak ditemukan !');
    //         }
    //     }
    //     // save data
    //     try {
    //         $result = $this->IndexModel->save($data);
    //     } catch (\Throwable $th) {
    //         return th_no_alert($th);
    //     }

    //     if ($result) {
    //         $id_baru = $this->IndexModel->getInsertID(); //jika insert maka menghasilkan id, jika update maka 0

    //         if ($id_baru) {

    //             $data_view['pesan'] = '<i class="bi bi-check"></i> Berhasil menyimpan data '  . $pesan_tambahan . ', #' .  $groupName;

    //             $data_view['data_row'] = ['', $groupName, $groupStatus, '', encrypt_url($id_baru)];
    //             $data_view['id_en'] = encrypt_url($id_baru);
    //             service('activitylog')->insert(
    //                 'input ' . $this->controller . ' baru ' . $groupName,
    //                 $this->controller . '/' . __FUNCTION__
    //             );
    //         } else {

    //             $data_view['pesan'] = '<i class="bi bi-check2-all"></i> Berhasil memperbaharui data '  . $pesan_tambahan . ', #' .  $groupName;

    //             $data_view['data_row_update'] = ['',  $groupName, $groupStatus, '', encrypt_url($id)];
    //             service('activitylog')->insert(
    //                 'update ' . $this->controller . ' ' . $groupName,
    //                 $this->controller . '/' . __FUNCTION__
    //             );
    //         }
    //         return view('layout_lte/partials/sukses', $data_view);
    //     } else {
    //         $errors = $this->IndexModel->errors();

    //         $errorString = '<ul>';
    //         foreach ($errors as $field => $message) {
    //             $errorString .= "<li>{$message}</li>";
    //         }

    //         $errorString .= '</ul>';

    //         return alert($errorString);
    //     }
    // }
    // public function index(): string
    // {
    //     if ($validasi = $this->cek_validasi_get_data($this->controller, false)) return $validasi;

    //     $data['breadcrumb'] = $this->breadcrumb;
    //     $data['controller'] = $this->controller;
    //     $data['title'] = $this->title;
    //     $data2 = [];
    //     $data['data2'] = $data2;
    //     $data['table'] =  $this->table(false);
    //     $data['file_view'] =  'layout_lte/card_table';
    //     return view('layout_lte/section', $data);
    // }

    // public function table($cek_htmx = true): string
    // {
    //     if ($validasi = $this->cek_validasi_get_data($this->controller, $cek_htmx)) return $validasi;

    //     try {
    //         $data_table = $this->IndexModel
    //             ->select('tbl_usergroup.*')
    //             ->where('id_about',  session()->get('id_about'))
    //             ->where('tbl_usergroup.deleted', 0)
    //             ->findAll();
    //     } catch (\Throwable $th) {
    //         return th_no_alert($th);
    //     }


    //     $data['data_table'] =  $data_table;
    //     $data['OPSI'] =  var_in_arr('OPSI');
    //     $data['title'] = $this->title;
    //     $data['size_modal'] = 'sm';
    //     $data['controller'] =  $this->controller;
    //     return view($this->folder_ . '/' . $this->controller . '/table', $data);
    // }

    // protected function getTableData(): array
    // {
    //     return $this->IndexModel->getTableData();
    // }




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

    //         $jumlah = $this->IndexModel->db->affectedRows(); //jumlah terdelete
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

    //     return view('layout_lte/alert/success.php', ['title' => 'Data ' . strtolower($this->title) . ' dihapus  🗑', 'text' => 'Data yang dipilih dihapus']);
    // }
}
