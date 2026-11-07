<?php

namespace App\Controllers\transaction;

use App\Controllers\BaseController;
use App\Controllers\BaseCrudController;
use App\Models\transaction\transactionModel;
use App\Models\coa\AccountModel;
use App\Models\transaction\transactionDetailModel;

class Transaction extends BaseCrudController
{
    protected string $title = 'Transaksi';
    protected string $controller = 'transaction';
    protected string $folder_ = 'transaction';
    protected $TransactionModel;
    protected $TransactionDetailModel;
    protected $AccountModel;
    protected $IndexModel;
    protected $UserModel;

    public function __construct()
    {
        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
        $this->TransactionModel = new transactionModel();
        $this->TransactionDetailModel = new transactionDetailModel();
        $this->AccountModel = new AccountModel();
        // Load model
        $this->breadcrumb = [
            ['href' => $this->controller, 'title' => $this->title]
        ];
    }
    public function index($type = 'pemasukan'): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/index';
        return view('layout_lte/section', $data);
    }

    public function form($type = 'pemasukan'): string
    {
        $data['title'] = $this->title . ' ' . ucwords($type);
        $data2 = [];
        $akun = $this->AccountModel
            ->groupStart()
            ->where('deleted_by', null)
            ->orWhere('deleted_by', '')
            ->groupEnd()
            ->findAll();
        $wallets = [];
        $akun_pemasukan = [];
        $akun_pengeluaran = [];
        if ($akun) {
            $wallets = array_filter($akun, function ($item) {
                return $item['is_wallet'] == 1; // Ubah ke format array
            });
            $wallets = array_values($wallets);

            $akun_pemasukan = array_filter($akun, function ($item) {
                return $item['kelompok_akun'] == 4 && !empty($item['parent_id']);
            });
            $akun_pemasukan = array_values($akun_pemasukan);

            $akun_pengeluaran = array_filter($akun, function ($item) {
                return $item['kelompok_akun'] == 5 && !empty($item['parent_id']);
            });
            $akun_pengeluaran = array_values($akun_pengeluaran);
        }

        $data['data2'] = $data2;
        $data['type'] = $type;
        $data['wallets'] = $wallets;
        $data['akun_pemasukan'] = $akun_pemasukan;
        $data['akun_pengeluaran'] = $akun_pengeluaran;
        $data['file_view'] = $this->controller . '/form';
        return view('layout_lte/section', $data);
    }
    public function Xhistory(): string
    {
        $data['title'] = $this->title;
        $data2 = [];
        $room_id = session()->get('room_id');
        $formatted_transactions = [];
        if ($room_id) {
            $getHistoryByRoom = $this->TransactionModel->getHistoryByRoom($room_id, 1);
            if ($getHistoryByRoom) {
                $formatted_transactions = $this->TransactionModel->formatted_transactions($getHistoryByRoom);
            }
        }
        $data['transactions'] = $formatted_transactions;
        $data['data2'] = $data2;
        $data['file_view'] = $this->controller . '/history';
        return view('layout_lte/section', $data);
    }

    public function Xsave($type = 'pemasukan'): string
    {
        $method = strtolower($this->request->getMethod(true));
        $input_validasi = $this->cek_validasi_save($method, $this->controller);
        $validasi = $input_validasi['validasi'];
        $input = $input_validasi['input'];
        $id = $input_validasi['id']; // sudah decrypt

        if ($validasi) {
            return alert($validasi);
        }

        $user_id = session()->get('user_id');
        $room_id = session()->get('room_id');

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // 1. Tentukan Prefix No Referensi & Deskripsi Berdasarkan Tipe
            $prefix = 'TRX';
            if ($type === 'pemasukan') {
                $prefix = 'INC';
            } elseif ($type === 'pengeluaran') {
                $prefix = 'EXP';
            } elseif ($type === 'transfer') {
                $prefix = 'TRF';
            }

            // 2. Simpan Header ke tbl_transaction
            $this->TransactionModel->save([
                'room_id'           => $room_id,
                'no_referensi'      => $prefix . '-' . date('YmdHis'),
                'tanggal_transaksi' => date('Y-m-d', strtotime($input['tanggal'])),
                'deskripsi'         => $input['keterangan'] ?? ucfirst($type),
                'status_transaksi'  => 'POSTED',
                'created_by'        => $user_id
            ]);
            $transactionId = $this->TransactionModel->getInsertID();

            // 3. Logika Detail Berdasarkan Tipe Transaksi
            if ($type === 'pemasukan') {
                // A. Pemasukan: Dompet (Debit) & Pendapatan (Kredit)
                $debitAccountId  = decrypt_url($input['to_wallet_id']);
                $kreditAccountId = decrypt_url($input['category_id']);
            } elseif ($type === 'pengeluaran') {
                // B. Pengeluaran: Beban/Kategori (Debit) & Dompet Asal (Kredit)
                $debitAccountId  = decrypt_url($input['category_id']);
                $kreditAccountId = decrypt_url($input['from_wallet_id']);
            } elseif ($type === 'transfer') {
                // C. Transfer: Dompet Tujuan (Debit) & Dompet Asal (Kredit)
                $debitAccountId  = decrypt_url($input['to_wallet_id']);
                $kreditAccountId = decrypt_url($input['from_wallet_id']);
            } else {
                throw new \Exception('Tipe transaksi tidak valid.');
            }

            // 4. Insert Sisi DEBIT
            $this->TransactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'account_id'     => $debitAccountId,
                'debet'          => $input['nominal'],
                'kredit'         => 0,
                'keterangan'     => $input['keterangan'],
                'created_at'     => date('Y-m-d H:i:s'),
                'created_by'     => $user_id
            ]);

            // 5. Insert Sisi KREDIT
            $this->TransactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'account_id'     => $kreditAccountId,
                'debet'          => 0,
                'kredit'         => $input['nominal'],
                'keterangan'     => $input['keterangan'],
                'created_at'     => date('Y-m-d H:i:s'),
                'created_by'     => $user_id
            ]);

            $db->transComplete();
            return  swall(
                $title ?? 'Sukses',
                $text ?? 'menyimpan transaksi ' . $type,
                $icon ?? 'success',
                ['position' => 'top-end', 'showConfirmButton' => false, 'timer' => 2000]
            ) .
                '<script>resetForm();</script>';
        } catch (\Throwable $th) {
            return th_no_alert($th);
        }
    }

    public function history(): string
    {
        $data['title'] = $this->title;
        $room_id = session()->get('room_id');
        $formatted_transactions = [];

        if ($room_id) {
            // Ambil 5 data pertama untuk halaman awal
            $getHistoryByRoom = $this->TransactionModel->getHistoryByRoom($room_id, 5, 0);
            if ($getHistoryByRoom) {
                $formatted_transactions = $this->TransactionModel->formatted_transactions($getHistoryByRoom);
            }
        }
        $data2 = [];
        $data['data2'] = $data2;
        $data['transactions'] = $formatted_transactions;
        $data['file_view'] = $this->controller . '/history';
        return view('layout_lte/section', $data);
    }

    // Endpoint AJAX untuk Load More (Infinite Scroll)
    public function loadMore()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
        }

        $room_id = session()->get('room_id');
        $page = $this->request->getGet('page') ?? 2;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $formatted_transactions = [];
        if ($room_id) {
            $getHistoryByRoom = $this->TransactionModel->getHistoryByRoom($room_id, $limit, $offset);
            // dd(print_r($getHistoryByRoom));
            if ($getHistoryByRoom) {
                $formatted_transactions = $this->TransactionModel->formatted_transactions($getHistoryByRoom);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $formatted_transactions,
            'hasMore' => count($formatted_transactions) == $limit // Cek apakah masih ada data selanjutnya
        ]);
    }
    public function save($type = 'pemasukan'): string
    {
        $method = strtolower($this->request->getMethod(true));
        $input_validasi = $this->cek_validasi_save($method, $this->controller);
        $validasi = $input_validasi['validasi'];
        $input = $input_validasi['input'];
        $id = $input_validasi['id']; // sudah decrypt

        if ($validasi) {
            return alert($validasi);
        }

        $user_id = session()->get('user_id');
        $room_id = session()->get('room_id');

        // Deklarasikan $uploadedFiles di luar try agar bisa diakses di blok catch
        $uploadedFiles = [];
        $uploadPath = FCPATH . 'uploads/transactions/' . $user_id . '/' . $room_id . '/';

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // Cek apakah disimpan sebagai transaksi gantung (pending)
            // Mengambil nilai dari input 'is_pending' (biasanya bernilai '1' atau true jika dicentang)
            $isPending = isset($input['is_pending']) && $input['is_pending'] == '1';
            $statusTransaksi = $isPending ? 'PENDING' : 'POSTED';

            // Tangani Multiple Upload File Bukti / Nota
            $files = $this->request->getFileMultiple('attachment');

            if ($files) {
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                foreach ($files as $file) {
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        $attachmentName = $file->getRandomName();
                        $file->move($uploadPath, $attachmentName);

                        // Simpan nama file ke array pelacak
                        $uploadedFiles[] = $attachmentName;
                    }
                }
            }

            // Ubah ke JSON agar lolos check(json_valid) di database
            $attachmentString = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

            // 1. Tentukan Prefix No Referensi & Deskripsi Berdasarkan Tipe
            $prefix = 'TRX';
            if ($type === 'pemasukan') {
                $prefix = 'INC';
            } elseif ($type === 'pengeluaran') {
                $prefix = 'EXP';
            } elseif ($type === 'transfer') {
                $prefix = 'TRF';
            }

            // 2. Simpan Header ke tbl_transaction
            $savedHeader = $this->TransactionModel->save([
                'room_id'           => $room_id,
                'no_referensi'      => $prefix . '-' . date('YmdHis'),
                'tanggal_transaksi' => date('Y-m-d', strtotime($input['tanggal'])),
                'deskripsi'         => $input['keterangan'] ?? ucfirst($type),
                'status_transaksi'  => $statusTransaksi, // Dinamis berdasarkan switch
                'attachment_url'    => $attachmentString,  // Simpan nama file ke database (pastikan kolom ini ada)
                'created_by'        => $user_id
            ]);
            if (!$savedHeader) {
                // Lempar error dari model jika gagal menyimpan header
                throw new \Exception(implode(', ', $this->TransactionModel->errors()));
            }
            $transactionId = $this->TransactionModel->getInsertID();

            // 3. Logika Detail Berdasarkan Tipe Transaksi
            if ($type === 'pemasukan') {
                // A. Pemasukan: Dompet (Debit) & Pendapatan (Kredit)
                $debitAccountId  = decrypt_url($input['to_wallet_id']);
                $kreditAccountId = decrypt_url($input['category_id']);
            } elseif ($type === 'pengeluaran') {
                // B. Pengeluaran: Beban/Kategori (Debit) & Dompet Asal (Kredit)
                $debitAccountId  = decrypt_url($input['category_id']);
                $kreditAccountId = decrypt_url($input['from_wallet_id']);
            } elseif ($type === 'transfer') {
                // C. Transfer: Dompet Tujuan (Debit) & Dompet Asal (Kredit)
                $debitAccountId  = decrypt_url($input['to_wallet_id']);
                $kreditAccountId = decrypt_url($input['from_wallet_id']);
            } else {
                throw new \Exception('Tipe transaksi tidak valid.');
            }

            // 4. Insert Sisi DEBIT
            $insertDebit =  $this->TransactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'account_id'     => $debitAccountId,
                'debet'          => $input['nominal'],
                'kredit'         => 0,
                'keterangan'     => $input['keterangan'],
                'created_at'     => date('Y-m-d H:i:s'),
                'created_by'     => $user_id
            ]);

            if (!$insertDebit) {
                throw new \Exception('Gagal menyimpan detail debet: ' . implode(', ', $this->TransactionDetailModel->errors()));
            }

            // 5. Insert Sisi KREDIT
            $insertKredit =  $this->TransactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'account_id'     => $kreditAccountId,
                'debet'          => 0,
                'kredit'         => $input['nominal'],
                'keterangan'     => $input['keterangan'],
                'created_at'     => date('Y-m-d H:i:s'),
                'created_by'     => $user_id
            ]);

            if (!$insertKredit) {
                throw new \Exception('Gagal menyimpan detail kredit: ' . implode(', ', $this->TransactionDetailModel->errors()));
            }

            // Cek status transaksi database secara keseluruhan
            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \Exception('Gagal melakukan commit transaksi database.');
            }

            $db->transComplete();

            $pesanSukses = $isPending ? 'menyimpan transaksi gantung (pending)' : 'menyimpan transaksi ' . $type;

            return swall(
                $title ?? 'Sukses',
                $text ?? $pesanSukses,
                $icon ?? 'success',
                ['position' => 'top-end', 'showConfirmButton' => false, 'timer' => 2000]
            ) . '<script>resetForm();</script>';
        } catch (\Throwable $th) {
            // Jika ada error, rollback database agar data tidak parsial/setengah-setengah
            if (isset($db) && $db->transStatus() === false) {
                $db->transRollback();
            }

            // Hapus file-file yang terlanjur ter-upload ke folder server jika proses simpan gagal
            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $fileName) {
                    $filePath = $uploadPath . $fileName;
                    if (file_exists($filePath)) {
                        unlink($filePath); // Perintah PHP untuk menghapus file fisik
                    }
                }
            }
            return th_no_alert($th);
        }
    }
}
