<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    function cek_validasi_get_data($url_access, $htmx = true)
    {
        if ($htmx) {
            // periksa htmx
            if (is_htmx()) {
                return is_htmx();
            }
        }

        $groupID = (session()->get('userGroup'));
        $allowed = action_access($url_access, $groupID);

        if (!$allowed) {
            $data['message'] = 'Tidak ada hak akses ' . $url_access;
            $data['code'] = '403';

            return  view('errors/html/error_400', $data);
        }
    }

    function cek_validasi_save($method, $controller): array
    {
        $input = null;
        $validasi = null;
        $id = null;
        if ($method === 'post') {
            $input = $this->request->getPost();
            $validasi = $this->cek_validasi_get_data($controller . '/addForm');
        } elseif ($method === 'put') {
            $input = $this->request->getRawInput();
            $id = decrypt_url($input['id_encrypt']);
            if (empty(trim((string) $id))) {
                $input = null;
                $id = null;
                $validasi = alert('tidak menemukan data untuk diubah !', ['color' => 'alert-danger']);
            } else {
                # code...
                $validasi = $this->cek_validasi_get_data($controller . '/editForm');
            }
        } else {
            $input = null;
            $validasi = alert('tidak ada akses');
        }

        return ['input' => $input, 'validasi' => $validasi, 'id' => $id];
    }

    function view_alert_error($text = 'Tidak ada data terhapus. Refresh halaman dan coba lagi !'): string
    {
        return view('layout_lte/alert/error.php', [
            'title' => 'Perhatian !',
            'text' => $text,
            'icon' => 'warning'
        ]);
    }
}
