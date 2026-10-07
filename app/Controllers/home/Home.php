<?php

namespace App\Controllers\home;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;
use App\Models\coa\AccountModel;

class Home extends BaseCrudController
{
    protected string $title = 'Home';
    protected string $controller = 'Home';
    protected string $folder_ = 'home';
    protected $HomeModel;
    protected $IndexModel;
    protected $UserModel;
    protected $AccountModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        // Load model
        $this->AccountModel = new AccountModel();
        $this->breadcrumb = [
            ['href' => $this->controller, 'title' => $this->title]
        ];
    }
    public function index(): string
    {
        $user_id = session()->get('user_id');
        // cari account rekening
        $account = $this->AccountModel->where('created_by', $user_id)->findAll();
        $data['title'] = $this->title;
        $data['account'] = $account;
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/index';
        return view('layout_lte/section', $data);
    }
}
