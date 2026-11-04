<?php

namespace App\Controllers\transaction;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;
use App\Models\transaction\transactionModel;
use App\Models\coa\AccountModel;

class Transaction extends BaseCrudController
{
    protected string $title = 'Transaksi';
    protected string $controller = 'transaction';
    protected string $folder_ = 'transaction';
    protected $TransactionModel;
    protected $AccountModel;
    protected $IndexModel;
    protected $UserModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        $this->TransactionModel = new transactionModel();
        $this->AccountModel = new AccountModel();
        // Load model
        $this->breadcrumb = [
            ['href' => $this->controller, 'title' => $this->title]
        ];
    }
    public function index($type = 'pemasukan'): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/index';
        return view('layout_lte/section', $data);
    }

    public function form($type = 'pemasukan'): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $akun = $this->AccountModel
            ->groupStart()
            ->where('deleted_by', null)
            ->orWhere('deleted_by', '')
            ->groupEnd()
            ->findAll();
        $wallets = [];
        $akun_pemasukan = [];
        $akun_pengeluaran = [];
        if ($akun) {
            $wallets = array_filter($akun, function ($item) {
                return $item['is_wallet'] == 1; // Ubah ke format array
            });
            $wallets = array_values($wallets);

            $akun_pemasukan = array_filter($akun, function ($item) {
                return $item['kelompok_akun'] == 4 && !empty($item['parent_id']);
            });
            $akun_pemasukan = array_values($akun_pemasukan);

            $akun_pengeluaran = array_filter($akun, function ($item) {
                return $item['kelompok_akun'] == 5 && !empty($item['parent_id']);
            });
            $akun_pengeluaran = array_values($akun_pengeluaran);
        }

        $data['data2'] = $data2;
        $data['type'] = $type;
        $data['wallets'] = $wallets;
        $data['akun_pemasukan'] = $akun_pemasukan;
        $data['akun_pengeluaran'] = $akun_pengeluaran;
        $data['file_view'] = $this->controller . '/form';
        return view('layout_lte/section', $data);
    }
    public function history(): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $room_id = session()->get('room_id');
        $formatted_transactions = [];
        if ($room_id) {
            $getHistoryByRoom = $this->TransactionModel->getHistoryByRoom($room_id);
            if ($getHistoryByRoom) {
                $formatted_transactions = $this->TransactionModel->formatted_transactions($getHistoryByRoom);
            }
        }
        $data['transactions'] = $formatted_transactions;
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/history';
        return view('layout_lte/section', $data);
    }

    public function save($type = 'pemasukan'): string
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

        return d($input);
    }
}
