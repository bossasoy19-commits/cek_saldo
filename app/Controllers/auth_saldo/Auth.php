<?php

namespace App\Controllers\auth_saldo;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $controller = 'auth';
    protected string $folder_ = 'auth_saldo';
    protected $title = 'Login';
    protected $UserModel;
    protected $AuthModel;
    protected $IndexModel;
    public function __construct()
    {
        // Set timezone ke Indonesia
        $this->UserModel = new UserModel();
        $this->AuthModel = new AuthModel();
        $this->IndexModel = $this->UserModel;
        date_default_timezone_set('Asia/Jakarta');
        // Load model

    }
    public function index($form = "welcome"): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $data['data2'] = $data2;
        $data['controller'] = $this->controller;
        $data['hx_post'] = 'post';
        $data['file_view'] = $this->folder_ . '/' . $this->controller . '/' . $form;
        return view('layout_lte/section_auth', $data);
    }
    public function login()
    {
        return $this->index('login');
    }
    public function create_guest()
    {
        $nama = 'GUEST-88204';
        $data = [
            'nama' => $nama,
            'password' => '123456',
            'status_akun' => 1,
            'created_at' => time(),
            'created_by' => 'guest',
        ];
        // cek email dan password yang sama
        // ====????
        // save data
        try {
            $result = $this->IndexModel->save($data);
            service('activitylog')->insert(
                'Register ' . $nama .  '-' . ($result ? ' berhasil' : ' gagal'),
                $this->controller . '/' . __FUNCTION__
            );
            if ($result === false) {
                return alert(
                    implode('<br>', $this->IndexModel->errors())
                );
            }
        } catch (\Throwable $th) {
            return th_no_alert($th);
        }

        $user_id = $this->IndexModel->getInsertID();

        // 🔥 redirect ke halaman sebelumnya
        $redirect = session()->get('redirect_url');
        $pesan = '';
        return alert($pesan ?: 'Sukses diproses !',  ['color' => 'alert-success', 'second' => '5']);
    }
    public function setup(): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->folder_ . '/' . $this->controller . '/setup';
        return view('layout_lte/section_auth', $data);
    }

    public function registers()
    {
        $data['title'] = $this->title;
        $data['hx_post'] = 'post';
        $data['controller'] =  $this->controller;
        return view($this->folder_ . '/' . $this->controller . '/register', $data);
    }

    public function proccess()
    {
        $username = (string)$this->request->getPost('identity');
        $password = (string)$this->request->getPost('password');
        if (empty(trim($username)) || empty(trim($password))) {
            $html = 'Lengkapi Username & Password !';
            return '<div class="alert alert-danger">' . $html . '</div>';
        }
        try {
            $user = $this->UserModel
                ->select('tbl_users.*')
                ->groupStart()
                ->where('tbl_users.email', $username)
                ->orWhere('tbl_users.no_wa', $username)
                ->orWhere('tbl_users.no_wa', hapus_62($username))
                ->groupEnd()
                ->groupStart()
                ->where('tbl_users.deleted_at', '')
                ->orWhere('tbl_users.deleted_at', null)
                ->groupEnd()
                ->first();

            // echo $this->UserModel->getLastQuery();
            // die;
        } catch (\Throwable $th) {
            return th_no_alert($th);
        }


        // ❌ user tidak ada
        if (!$user) {
            return '<div class="alert alert-danger">Username tidak ditemukan</div>';
        }

        // ❌ password salah
        if (!password_verify($password, $user['password'])) {
            return '<div class="alert alert-danger">Password salah</div>';
        }

        // ✅ login sukses
        $user_id = $user['user_id'];
        $status_akun = $user['status_akun'];
        // ❌ status tidak aktif
        if ($status_akun != '1') {
            return '<div class="alert alert-danger"> <i class="fas fa-user-slash"></i> Akun ini tidak aktif !</div>';
        }
        session()->set([
            'user_id'    => $user_id,
            'userName'   => $username,
            'nama'   => $user['nama'],
            'isLoggedIn' => true
        ]);




        // 🔥 redirect ke halaman sebelumnya
        $redirect = session()->get('redirect_url');
        service('activitylog')->insert(
            'Login Berhasil, username :' . $username,
            $this->controller . '/' . __FUNCTION__
        );
        if ($redirect) {
            session()->remove('redirect_url');
            return '<script>window.location.href="' . $redirect . '"</script>';
        }

        return '<script>window.location.href="/home"</script>';
    }

    public function logout($ajax = '')
    {
        service('activitylog')->insert(
            __FUNCTION__ . ' nama:' . session()->get('nama'),
            $this->controller . '/' . __FUNCTION__
        );
        session()->destroy();
        return redirect()->to(base_url('login'));
    }

    public function save_first_time(): string
    {
        $input = $this->request->getPost();

        $wallets = $input['wallets'];

        if (!$input) {
            return alert('tidak ada post !',  ['color' => 'alert-danger', 'second' => '5']);
        }
        if (!$wallets) {
            return alert('Tentukan dompet minimal 1 !',  ['color' => 'alert-danger', 'second' => '5']);
        }

        // Mock user_id (atau sesuaikan dengan session: session()->get('user_id'))
        $userId = 20;

        $db = \Config\Database::connect();

        // 1. Mulai Database Transaction
        $db->transBegin();

        try {
            $now = date('Y-m-d H:i:s');
            // STEP 1: SIMPAN ROOM (tbl_room)
            // ============================================================
            $db->table('tbl_room')->insert($this->AuthModel->dataRoom($input, $userId, $now));
            $roomId = $db->insertID();
            // STEP 2: SIMPAN MEMBER ROOM AS OWNER (tbl_room_member)
            // ============================================================
            $db->table('tbl_room_member')->insert($this->AuthModel->dataMember($roomId, $userId, $now));

            // STEP 3: GENERATE COA / AKUN DEFAULT LENGKAP (tbl_account)
            // ============================================================
            // 3a. Akun Induk Utama (5 Kelompok Standar Akuntansi)
            $db->table('tbl_account')->insertBatch($this->AuthModel->dataCoaHeader($roomId, $userId, $now));
            // 3b. Ambil ID Akun Induk yang baru dibuat
            $parentAccountId = $this->AuthModel->parentAccountId($roomId);
            // 3c. Buat Sub-Akun Standar Default
            // 1) Akun Penyeimbang Saldo Awal (di bawah Ekuitas)
            $db->table('tbl_account')->insert($this->AuthModel->dataSaldoAwalAccount($roomId, $parentAccountId, $userId, $now));
            $saldoAwalAccountId = $db->insertID();
            // 2) Kategori Pendapatan & Beban Umum (Opsional untuk onboarding)
            $db->table('tbl_account')->insertBatch($this->AuthModel->defaultSubAccounts($roomId, $parentAccountId, $userId, $now));

            // STEP 4: SIMPAN WALLETS & JURNAL SALDO AWAL
            // ============================================================
            $totalSaldoAwal = 0;
            $walletTxDetails = [];

            $counter = 1;
            foreach ($wallets as $wallet) {
                $saldo = (float) ($wallet['saldo'] ?? 0);

                // Deteksi jenis wallet otomatis dari nama (opsional)
                $jenisWallet = $this->AuthModel->jenisWallet($wallet);

                // Insert ke tbl_account sebagai Wallet

                $db->table('tbl_account')->insert($this->AuthModel->dataWalletAccount(
                    $roomId,
                    '1-100' . $counter,
                    $wallet,
                    $parentAccountId,
                    $userId,
                    $now
                ));
                $walletAccountId = $db->insertID();
                // Jika dompet memiliki saldo awal > 0, siapkan detail jurnal Debit
                if ($saldo > 0) {
                    $totalSaldoAwal += $saldo;
                    $walletTxDetails[] = [
                        'account_id' => $walletAccountId,
                        'debet'      => $saldo,
                        'kredit'     => 0,
                        'keterangan' => 'Saldo Awal ' . $wallet['nama'],
                        'created_at' => $now,
                        'created_by' => $userId,
                    ];
                }

                $counter++;
            }

            // STEP 5: JIKA ADA SALDO AWAL, BUAT TRANSAKSI JURNAL UNTUK SALDO AWAL
            // ============================================================
            if ($totalSaldoAwal > 0 && !empty($walletTxDetails)) {
                // 5a. Buat Header Transaksi Saldo Awal


                $db->table('tbl_transaction')->insert($this->AuthModel->dataTxHeader($roomId, $userId, $now));
                $transactionId = $db->insertID();

                // 5b. Susun Detail Debit (Dompet-Dompet) & Kredit (Modal Awal)
                $finalDetails = [];

                // Masukkan baris Debit untuk tiap dompet yang ada saldonya
                foreach ($walletTxDetails as $detail) {
                    $detail['transaction_id'] = $transactionId;
                    $finalDetails[] = $detail;
                }

                // Masukkan baris Kredit untuk menyeimbangkan jurnal (Modal Awal)
                $finalDetails[] = [
                    'transaction_id' => $transactionId,
                    'account_id'     => $saldoAwalAccountId,
                    'debet'          => 0,
                    'kredit'         => $totalSaldoAwal,
                    'keterangan'     => 'Total Kontribusi Saldo Awal',
                    'created_at'     => $now,
                    'created_by'     => $userId,
                ];

                $db->table('tbl_transaction_detail')->insertBatch($finalDetails);
            }

            // Jika status transaksi gagal (terjadi kesalahan query)
            if ($db->transStatus() === false) {
                // 1. Ambil detail error dari database
                $error = $db->error(); // Mengembalikan array ['code' => ..., 'message' => ...]
                $db->transRollback();
                throw new \Exception($error['message'] ?: 'Terjadi kesalahan pada transaksi database.');
            }

            $db->transCommit();
            $pesan = '';
            return alert($pesan ?: 'Sukses diproses !',  ['color' => 'alert-success', 'second' => '5']);
        } catch (\Throwable $th) {
            $db->transRollback();
            return th_no_alert($th);
        }
    }


    public function save(): string
    {

        $input = $this->request->getPost();

        // ============================================================

        $email = $input['email'];
        $nama = $input['nama'];
        $no_wa = $input['no_wa'];
        $password = $input['password'];
        $password_match = $input['password_match'];




        if ($password !== $password_match) {
            return alert('Password dan ulangi password tidak sama!');
        }
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $data = [
            'nama' => $nama,
            'email' => $email,
            'no_wa' => hapus_62($no_wa),
            'password' => $password_hash,
            'email' => $email,
            'status_akun' => 1,
            'created_at' => time(),
            'created_by' => '159753',
        ];
        // cek email dan password yang sama
        // ====????
        // save data
        try {
            $result = $this->IndexModel->save($data);
            service('activitylog')->insert(
                'Register ' . $nama . ' / ' . $no_wa . ' / ' . $email . '-' . ($result ? ' berhasil' : ' gagal'),
                $this->controller . '/' . __FUNCTION__
            );
            if ($result === false) {
                return alert(
                    implode('<br>', $this->IndexModel->errors())
                );
            }
        } catch (\Throwable $th) {
            return th_no_alert($th);
        }

        $user_id = $this->IndexModel->getInsertID();

        // 🔥 redirect ke halaman sebelumnya
        $redirect = session()->get('redirect_url');


        // $pesan = '<h5 class="fw-bold mb-2">
        //             <i class="bi bi-person-check-fill text-success"></i>
        //             Selamat!
        //         </h5>

        //         <p class="mb-0 text-muted">
        //             Akun Anda berhasil terdaftar.
        //             <br>
        //             Silahkan login! 👋
        //         </p>';
        $pesan = '';
        return alert($pesan ?: 'Sukses diproses !',  ['color' => 'alert-success', 'second' => '5']);
    }
}
