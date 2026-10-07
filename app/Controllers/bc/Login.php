<?php

namespace App\Controllers;

use App\Models\sistem_utility\UserAllowCabangModel;
use App\Models\UserDetailModel;
use App\Models\UserModel;

class Login extends BaseController
{
    protected $controller = 'login';
    protected $title = 'Login';
    protected $UserModel;
    protected $UserDetailModel;
    protected $UserAllowCabangModel;
    public function __construct()
    {
        // Set timezone ke Indonesia
        $this->UserModel = new UserModel();
        $this->UserDetailModel = new UserDetailModel();
        $this->UserAllowCabangModel = new UserAllowCabangModel();
        date_default_timezone_set('Asia/Jakarta');
        // Load model

    }
    public function index(): string
    {
        // if ($this->request->isAJAX()) {
        //     return view('partial/table', $data);
        // }

        return view($this->controller . '/index');
    }

    public function proccess()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        if (empty(trim($username)) || empty(trim($password))) {
            $html = 'Lengkapi Username & Password !';
            return '<div class="alert alert-danger">' . $html . '</div>';
        }
        $user = $this->UserModel
            ->select('tbl_users.*, tbl_usergroup.groupName , tbl_usergroup.groupStatus')
            ->join('tbl_usergroup', 'tbl_usergroup.groupID = tbl_users.userGroup', 'left')
            ->where('tbl_users.userName', $username)
            ->where('tbl_users.deleted', '0')
            ->first();

        // ❌ user tidak ada
        if (!$user) {
            return '<div class="alert alert-danger">Username tidak ditemukan</div>';
        }

        // ❌ password salah
        if (!password_verify($password, $user['userPassword'])) {
            return '<div class="alert alert-danger">Password salah</div>';
        }

        // ✅ login sukses
        $userID = $user['userID'];
        $userStatus = $user['userStatus'];

        if ($userStatus != '1') {
            return '<div class="alert alert-danger"> <i class="fas fa-user-slash"></i> Akun ini tidak aktif !</div>';
        }
        session()->set([
            'user_id'    => $userID,
            'userName'   => $user['userName'],
            'realName'   => $user['realName'],
            'userGroup'   => $user['userGroup'],
            'id_about'   => $user['id_about'],
            'isLoggedIn' => true
        ]);

        $user_detail = $this->UserDetailModel->find($userID);
        if ($user_detail) {
            session()->set('foto_profile', $user_detail['foto_profile']);
        }

        // cari akses cabang
        $akses_cabang = $this->UserAllowCabangModel->where('id_user', (session()->get('user_id')))->where('allow', 1)->findAll();

        if ($akses_cabang) {
            $id_cabang = array_column($akses_cabang, 'id_cabang', 'id_user_allow_cabang');
            session()->set('id_cabang', $id_cabang);
        }

        // 🔥 redirect ke halaman sebelumnya
        $redirect = session()->get('redirect_url');

        if ($redirect) {
            session()->remove('redirect_url');
            return '<script>window.location.href="' . $redirect . '"</script>';
        }

        return '<script>window.location.href="/dashboard"</script>';
    }

    public function logout($ajax = '')
    {
        service('activitylog')->insert(
            'Logout',
            'Auth'
        );
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
