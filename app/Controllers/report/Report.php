<?php

namespace App\Controllers\report;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;

class Report extends BaseCrudController
{
    protected string $title = 'Laporan';
    protected string $controller = 'report';
    protected string $folder_ = 'report';
    protected $ReportModel;
    protected $IndexModel;
    protected $UserModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        // Load model
        $this->breadcrumb = [
            ['href' => $this->controller, 'title' => $this->title]
        ];
    }
    public function index(): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/index';
        return view('layout_lte/section', $data);
    }
    public function pemasukan(): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/pemasukan';
        return view('layout_lte/section', $data);
    }
    public function arus_kas(): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/arus_kas';
        return view('layout_lte/section', $data);
    }
}
