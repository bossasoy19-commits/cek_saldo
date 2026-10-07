<?php

namespace App\Controllers;

abstract class BaseCrudController extends BaseController
{
    protected string $controller;
    protected string $title;
    protected string $folder_;
    protected string $size_modal = 'lg';
    protected array $breadcrumb = [];
    protected bool $cek_minimal_data = false;

    protected string $viewSection = 'layout_lte/section';
    protected string $viewTable   = 'layout_lte/card_table';
    // abstract protected function table(bool $ajax = true): string;
    protected $IndexModel;
    public function index(): string
    {
        // if ($validasi = $this->cek_validasi_get_data($this->controller, false)) {
        //     return $validasi;
        // }

        return $this->renderIndex();
    }

    protected function renderIndex(array $data = []): string
    {
        $default = [
            'breadcrumb' => $this->breadcrumb,
            'controller' => $this->controller,
            'title'      => $this->title,
            'data2'      => [],
            // 'table'      => $this->table(false, $this->size_modal),
            'file_view'  => $this->viewTable,
        ];

        return view($this->viewSection, array_merge($default, $data));
    }

    public function table(bool $cek_htmx = true, string $size_modal = 'lg'): string
    {
        // if ($validasi = $this->cek_validasi_get_data($this->controller, $cek_htmx)) {
        //     return $validasi;
        // }

        try {
            $dataTable = $this->getTableData();
        } catch (\Throwable $th) {
            return th_no_alert($th);
        }


        return view($this->folder_ . '/' . $this->controller . '/table', [
            'data_table' => $dataTable,
            'OPSI'       => var_in_arr('OPSI'),
            'title'      => $this->title,
            'size_modal' => $size_modal,
            'controller' => $this->controller,
        ]);
    }

    // abstract protected function getTableData(): array;
    private function getTableData(): array
    {
        return $this->IndexModel->getTableData();
    }

    public function form(): string
    {
        $mode = $this->request->getGet('mode');
        if ($mode == 'edit') {
            if ($validasi = $this->cek_validasi_get_data($this->controller . '/editForm')) return $validasi;
            // ambil data user
            $id = decrypt_url((string)$this->request->getGet('param'));
            if (empty(trim((string) $id)))  return   alert('Data ' . $this->title . ' ini tidak ditemukan. Refresh halaman dan coba lagi !', ['color' => 'alert-danger']);

            $data_lama = $this->data_lama($id);

            if (!$data_lama) {
                return   alert('Data ' . $this->title . ' tidak ditemukan. Refresh halaman dan coba lagi !');
            }

            $data['hx_post'] = 'put';
            $data['id_edit'] = $id;
        } else {
            if ($validasi = $this->cek_validasi_get_data($this->controller . '/addForm')) return $validasi;
            $data['hx_post'] = 'post';
            $data_lama = [];
            $data['id_edit'] = '';
        }
        $data['title'] = $this->title;
        $data['controller'] =  $this->controller;
        $data['opt_status'] = [0 => 'Tidak Aktif', 1 => 'Aktif'];
        $data['data_lama'] = $data_lama;
        // Hook
        $data = $this->prepareFormData($data);
        return view($this->folder_ . '/' . $this->controller . '/_form', $data);
    }

    // abstract protected function data_lama($id): array;
    private function data_lama($id): array
    {
        return   $this->IndexModel->find($id);;
    }

    protected function prepareFormData(array $data): array
    {
        return $data;
    }

    public function delete(): string
    {
        $hx_vals = $this->request->getRawInput();

        $id = decrypt_url($hx_vals['id']);
        if (!$id || !is_numeric($id)) {
            return view('layout_lte/alert/error.php', ['title' => 'Id tidak ditemukan']);
        }

        if ($this->cek_minimal_data) {
            $count_data = $this->IndexModel->countTableData();

            if ($count_data <= 1) {
                return $this->view_alert_error('Ups! Semua ' . strtolower($this->title)  . ' tidak boleh dihapus. Sisakan minimal 1 ' . strtolower($this->title) . ' agar aplikasi tetap berjalan.');
            }
        }


        try {
            $this->IndexModel->delete($id);

            $jumlah = $this->IndexModel->db->affectedRows(); //jumlah terdelete
            if ($jumlah < 1) {
                return $this->view_alert_error();
            }
        } catch (\Throwable $th) {
            return th_alert($th, 'Gagal Menghapus Data ' . ucwords($this->title)  . ' !');
        }

        service('activitylog')->insert(
            'Delete ' . $this->controller . ' #' . $id,
            $this->controller . '/' . __FUNCTION__
        );

        return view('layout_lte/alert/success.php', ['title' => 'Data ' . strtolower($this->title) . ' dihapus  🗑', 'text' => 'Data yang dipilih dihapus']);
    }

    // abstract protected function prepareInputData(array $input,  $id, string $pesan_tambahan): array;
    protected function prepareInputData(
        array $input,
        $id,
        string $pesan_tambahan
    ): array {
        return $input;
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

        $data = $this->prepareInputData($input,  $id,  $pesan_tambahan);
        if ($id) {
            $data_lama = $data['data_lama'];
            if (!$data_lama) {
                return alert('Data ' . strtolower($this->title) . ' tidak ditemukan !');
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

            if ($id_baru) {

                $data_view['pesan'] = $data['pesan'];
                $data_row = $data['data_row'];
                $data_row[] = encrypt_url($id_baru);
                $data_view['data_row'] = $data_row;
                $data_view['id_en'] = encrypt_url($id_baru);
                service('activitylog')->insert(
                    'input ' . $this->controller . ' baru ' . $data['proses_kolom'],
                    $this->controller . '/' . __FUNCTION__
                );
            } else {

                $data_view['pesan'] = $data['pesan_update'];
                $data_view['data_row_update'] = $data['data_row_update'];
                service('activitylog')->insert(
                    'update ' . $this->controller . ' ' . $data['proses_kolom'],
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
