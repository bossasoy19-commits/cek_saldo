<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // if ($this->request->isAJAX()) {
        //     return view('partial/table', $data);
        // }
        return view('welcome_message');
    }
}
