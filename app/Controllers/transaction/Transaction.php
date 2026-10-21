<?php

namespace App\Controllers\transaction;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;
use App\Models\transaction\transactionModel;

class Transaction extends BaseCrudController
{
    protected string $title = 'Transaksi';
    protected string $controller = 'transaction';
    protected string $folder_ = 'transaction';
    protected $TransactionModel;
    protected $IndexModel;
    protected $UserModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        $this->TransactionModel = new transactionModel();
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
        $data['data2'] = $data2;
        $data['type'] = $type;
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
}
