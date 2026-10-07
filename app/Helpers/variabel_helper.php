<?php

use App\Models\VariableModel;

if (!function_exists('var_in_arr')) {
    function var_in_arr($varGroup,  $order = 'description', $hasil = 'description', $key = 'varName')
    {
        $VariabelModel = model(VariableModel::class);
        $data = $VariabelModel
            ->where('deleted', '0')
            ->where('varGroup', $varGroup)
            ->where('tampil', '1')
            ->orderBy($order)
            ->findAll();

        if (!$data) {
            return null;
        }

        $result = array_column($data, $hasil, $key);

        return $result;
    }
}

if (!function_exists('log_json')) {
    function log_json($variable)
    {
        return log_message('info', 'log_json : ' . json_encode($variable, JSON_PRETTY_PRINT));
    }
}

if (!function_exists('generate_kode')) {
    function generate_kode($number, $prefix = '', $length = 4)
    {

        $numberFormatted = str_pad($number, $length, '0', STR_PAD_LEFT); // 14 → 0014

        if ($prefix != '') {
            return $numberFormatted . '/' . $prefix;
        }

        return $numberFormatted;
    }
}
if (!function_exists('array_cari')) {
    function array_cari($data, $kolom, $cari): array
    {
        if (!is_array($data) || !$data) {
            return [];
        }
        $hasil = array_filter($data, function ($row) use ($cari, $kolom) {
            return $row[$kolom] == $cari;
        });

        return $hasil;
    }
}


if (!function_exists('FilterKolom')) {
    function FilterKolom($builder, $kolom_lainnya, $value_kolom, $filter_like)
    {
        switch ($filter_like) {
            case 'contains':
                $builder->like($kolom_lainnya, $value_kolom);
                break;

            case 'starts_with':
                $builder->like($kolom_lainnya, $value_kolom, 'after');
                break;

            case 'ends_with':
                $builder->like($kolom_lainnya, $value_kolom, 'before');
                break;

            case 'equals':
                $builder->where($kolom_lainnya, $value_kolom);
                break;

            case 'not_contains':
                $builder->notLike($kolom_lainnya, $value_kolom);
                break;

            case 'is_empty':
                $builder->groupStart()
                    ->where($kolom_lainnya, '')
                    ->orWhere($kolom_lainnya . ' IS NULL', null, false)
                    ->groupEnd();
                break;

            case 'is_not_empty':
                $builder->groupStart()
                    ->where($kolom_lainnya . ' <>', '')
                    ->where($kolom_lainnya . ' IS NOT NULL', null, false)
                    ->groupEnd();
                break;
        }

        return $builder; // biar bisa chaining
    }
}

function filter_lainnya($value_kolom, $filter_like): string
{

    $searchOptions = [
        'contains'      => 'Mengandung',
        'starts_with'   => 'Diawali dengan',
        'ends_with'     => 'Diakhiri dengan',
        'equals'        => 'Persis sama',
        'not_contains'  => 'Tidak mengandung',
        'is_empty'      => 'Kosong',
        'is_not_empty'  => 'Ada isinya',
    ];
    $filter = ($value_kolom == '' ? ' <span class="text text-muted">NULL</span> '  : $value_kolom) . ' <span class="text small text-muted"> (' . $searchOptions[$filter_like]  . ')</span>';
    return $filter;
}


if (!function_exists('json_no_data')) {
    function json_no_data($message = 'Tidak ada data ditemukan !')
    {
        return service('response')->setJSON([
            'html' => '<div class="alert alert-warning" role="alert"> ' .  $message . ' </div>',
        ]);
    }
}
if (!function_exists('rout')) {
    function rout($routes, $controller)
    {
        $routes->get('/', $controller . '::index');
        // $routes->get('table', $controller . '::table');
        $routes->get('form', $controller . '::form');
        // $routes->get('form/(:segment)', $controller . '::form/$1');
        $routes->post('save', $controller . '::save');
        $routes->put('save', $controller . '::save');
        $routes->delete('delete', $controller . '::delete');
        // $routes->delete('delete/(:segment)', $controller . '::delete/$1');
    }
}

function th_alert($th, $text_swall = 'Terjadi kesalahan saat memproses data. Coba refresh halaman dan ulangi lagi', $title_swall = 'ERROR !'): string
{
    if (ENVIRONMENT == 'production') {
        return view('layout_lte/alert/error.php', [
            'title' => $title_swall,
            'text' => $text_swall
        ]);
    }

    // Development
    $text = sprintf(
        "1. Message : %s\n2. File : %s\n3. Line : %d\n4. Trace :\n%s",
        $th->getMessage(),
        $th->getFile(),
        $th->getLine(),
        $th->getTraceAsString()
    );

    return alert('<h3 class="text text-danger">Error</h3>' . nl2br($text));
}
function th_no_alert($th, $text = '<h4>Terjadi kesalahan saat memproses.</h4> Coba refresh halaman dan ulangi lagi.', $title = '<h2 class="text text-danger">⚠ Error</h2>'): string
{
    if (ENVIRONMENT == 'production') {
        return alert($title  . $text, ['color' => 'alert-danger']);
    }

    // Development
    $text = sprintf(
        "1. Message : %s\n2. File : %s\n3. Line : %d\n4. Trace :\n%s",
        $th->getMessage(),
        $th->getFile(),
        $th->getLine(),
        $th->getTraceAsString()
    );

    return alert($title  . (db_connect()->getLastQuery() ?? '') . '<br><br>' . nl2br($text));
}
