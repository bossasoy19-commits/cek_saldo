<?php

use CodeIgniter\HTTP\ResponseInterface;


if (!function_exists('is_htmx')) {
    function is_htmx()
    {
        $request = \Config\Services::request();
        $htmx =  $request->getHeaderLine('HX-Request') === 'true';
        if (!$htmx) {
            $data['message1'] = '<a href="' . base_url('') . '">Back to dashboard</a>';
            $data['code'] = '403';

            return  view('errors/html/error_400', $data);
        }
    }
}

if (!function_exists('is_url_htmx')) {
    function is_url_htmx()
    {
        $request = \Config\Services::request();
        $htmx =  $request->getHeaderLine('HX-Request') === 'true';
        return $htmx;
    }
}
