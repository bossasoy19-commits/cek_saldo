<?php

namespace App\Controllers;

class AdminLTE extends BaseController
{
    protected $controller = 'adminLTE';
    protected $title = 'Admin LTE';
    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        // Load model

    }

    public function index(): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/index';
        return view('layout_lte/section', $data);
    }
}
