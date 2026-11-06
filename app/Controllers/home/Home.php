<?php

namespace App\Controllers\home;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;
use App\Models\coa\AccountModel;
use App\Models\room\RoomModel;

class Home extends BaseCrudController
{
    protected string $title = 'Dashboard';
    protected string $controller = 'Home';
    protected string $folder_ = 'home';
    protected $HomeModel;
    protected $IndexModel;
    protected $UserModel;
    protected $AccountModel;
    protected $RoomModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        // Load model
        $this->AccountModel = new AccountModel();
        $this->RoomModel = new RoomModel();
        $this->breadcrumb = [
            ['href' => $this->controller, 'title' => $this->title]
        ];
    }
    public function index(): string
    {
        $user_id = session()->get('user_id');
        // cari account rekening
        $room = $this->RoomModel->where('owner_user_id', $user_id)->first();
        $data['title'] = $this->title;
        if ($room) {
            $room_id = $room['room_id'];
            session()->set('room_id', $room_id);
            $dompet = $this->AccountModel->getWalletBalances($room_id);
            $data['dompet'] = $dompet;
            // 2. Hitung total keseluruhan saldo dari semua dompet
            $data['total_saldo'] = array_sum(array_column($dompet, 'saldo'));
        }
        $data['room'] = $room;

        // dd($data['dompet']);
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/index';
        return view('layout_lte/section', $data);
    }
}
