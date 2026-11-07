<?php

namespace App\Models\transaction;

use CodeIgniter\Model;

class transactionModel extends Model
{
    protected $table            = 'tbl_transaction';
    protected $primaryKey       = 'transaction_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_id',
        'room_id',
        'no_referensi',
        'tanggal_transaksi',
        'deskripsi',
        'status_transaksi',
        'attachment_url',
        'created_at ',
        'created_by ',
        'updated_at ',
        'updated_by ',
        'deleted_at ',
        'deleted_by ',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'update_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function XgetHistoryByRoom($room_id, $is_wallet = null, $limit = 5, $offset = 0)
    {
        $builder = $this->db->table('tbl_transaction t');
        $builder->select('
        t.transaction_id as id,
        t.tanggal_transaksi as tanggal,
        t.deskripsi as keterangan,
        t.status_transaksi,
        td.transaction_detail_id as td_id,
        td.debet,
        td.kredit,
        acc.nama_akun as category,
        acc.is_wallet,
        acc.jenis_wallet
    ');
        $builder->join('tbl_transaction_detail td', 'td.transaction_id = t.transaction_id');
        $builder->join('tbl_account acc', 'acc.account_id = td.account_id');
        $builder->where('t.room_id', $room_id);
        if ($is_wallet !== null) {
            $builder->where('acc.is_wallet', $is_wallet);
        }
        $builder->orderBy('t.tanggal_transaksi', 'DESC');
        $builder->orderBy('t.transaction_id', 'DESC'); // Urutan sekunder agar stabil

        // Batasi jumlah data (Limit & Offset)
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    public function Xformatted_transactions($results): array
    {
        // Mapping agar formatnya mirip dengan data dummy sebelumnya
        $formatted_transactions = [];

        foreach ($results as $row) {
            // Tentukan tipe (pemasukan/pengeluaran) berdasarkan debet/kredit atau saldo normal akun
            // Logika sederhana: jika ada nilai kredit pada akun pendapatan, atau debet pada akun pengeluaran, dll.
            $type = ($row['debet'] > 0) ? 'pemasukan' : 'pengeluaran';
            $nominal = ($row['debet'] > 0) ? $row['debet'] : $row['kredit'];

            $formatted_transactions[] = [
                'id'         => $row['td_id'],
                'type'       => $type,
                'keterangan' => $row['keterangan'],
                'category'   => $row['category'],
                'nominal'    => $nominal,
                'wallet'     => $row['is_wallet'] == 1 ? $row['category'] : 'Umum', // Sesuaikan jika ada relasi wallet khusus
                'tanggal'    => $row['tanggal'],
                'is_pending' => ($row['status_transaksi'] === 'PENDING') ? 1 : 0
            ];
        }

        return $formatted_transactions;
    }

    public function getHistoryByRoom($room_id, $limit = 5, $offset = 0)
    {
        // 1. Ambil master transaksi dengan limit & offset (Paginasi)
        $transactions = $this->db->table('tbl_transaction')
            ->where('room_id', $room_id)
            ->where('deleted_at IS NULL')
            ->orderBy('tanggal_transaksi', 'DESC')
            ->orderBy('transaction_id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        if (empty($transactions)) {
            return [];
        }

        $transactionIds = array_column($transactions, 'transaction_id');

        // 2. Ambil seluruh detail akun untuk transaksi-transaksi tersebut (baik wallet maupun non-wallet)
        $details = $this->db->table('tbl_transaction_detail td')
            ->select('
            td.transaction_detail_id as td_id,
            td.transaction_id as id,
            td.debet,
            td.kredit,
            td.keterangan as detail_keterangan,
            acc.nama_akun as category,
            acc.is_wallet,
            acc.jenis_wallet
        ')
            ->join('tbl_account acc', 'acc.account_id = td.account_id')
            ->whereIn('td.transaction_id', $transactionIds)
            ->get()
            ->getResultArray();

        // 3. Gabungkan header transaksi dengan baris detailnya
        $resultData = [];
        foreach ($transactions as $t) {
            $txId = $t['transaction_id'];
            $matchedDetails = array_filter($details, fn ($d) => $d['id'] == $txId);

            $resultData[] = [
                'transaction_id'   => $txId,
                'tanggal'          => $t['tanggal_transaksi'],
                'keterangan'       => $t['deskripsi'],
                'status_transaksi' => $t['status_transaksi'],
                'details'          => array_values($matchedDetails)
            ];
        }

        return $resultData;
    }

    public function formatted_transactions($transactions): array
    {
        $formatted_transactions = [];

        foreach ($transactions as $tx) {
            $details = $tx['details'];
            $walletRows = array_filter($details, fn ($d) => $d['is_wallet'] == 1);
            $nonWalletRows = array_filter($details, fn ($d) => $d['is_wallet'] == 0);

            $totalDebetWallet = array_sum(array_column($walletRows, 'debet'));
            $totalKreditWallet = array_sum(array_column($walletRows, 'kredit'));

            // KASUS: TRANSFER ANTAR DOMPET (Tepat 2 akun wallet: 1 debet, 1 kredit)
            if (count($walletRows) == 2 && $totalDebetWallet > 0 && $totalKreditWallet > 0) {
                $sumber = null;
                $tujuan = null;
                $nominal = 0;

                foreach ($walletRows as $w) {
                    if ($w['kredit'] > 0) {
                        $sumber = $w['category'];
                        $nominal = $w['kredit'];
                    }
                    if ($w['debet'] > 0) {
                        $tujuan = $w['category'];
                    }
                }

                $formatted_transactions[] = [
                    'id'         => $tx['transaction_id'],
                    'type'       => 'transfer',
                    'keterangan' => $tx['keterangan'] ?: 'Pindah Saldo',
                    'category'   => 'Transfer',
                    'nominal'    => $nominal,
                    'wallet'     => ($sumber && $tujuan) ? "$sumber ➔ $tujuan" : 'Transfer',
                    'tanggal'    => $tx['tanggal'],
                    'is_pending' => ($tx['status_transaksi'] === 'PENDING') ? 1 : 0
                ];
            }
            // KASUS LAINNYA (Pemasukan, Pengeluaran, atau Saldo Awal Multi-Wallet): Tampilkan per dompet
            else {
                foreach ($walletRows as $w) {
                    $type = ($w['debet'] > 0) ? 'pemasukan' : 'pengeluaran';
                    $nominal = ($w['debet'] > 0) ? $w['debet'] : $w['kredit'];

                    if ($nominal <= 0) continue;

                    // Prioritaskan keterangan spesifik dari detail database (misal: "Saldo Awal Tunai"), jika kosong ambil dari header/kategori
                    $keteranganItem = !empty($w['detail_keterangan']) ? $w['detail_keterangan'] : ($tx['keterangan'] ?: ucwords($type)  . ' ' .  $w['category']);
                    $categoryName = !empty($nonWalletRows) ? reset($nonWalletRows)['category'] : $w['category'];

                    $formatted_transactions[] = [
                        'id'         => $tx['transaction_id'], // Unik per baris detail
                        'type'       => $type,
                        'keterangan' => $keteranganItem,
                        'category'   => $categoryName,
                        'nominal'    => $nominal,
                        'wallet'     => $w['category'], // Menampilkan nama dompet aslinya (Tunai, BRI, OVO, dll)
                        'tanggal'    => $tx['tanggal'],
                        'is_pending' => ($tx['status_transaksi'] === 'PENDING') ? 1 : 0
                    ];
                }
            }
        }

        return $formatted_transactions;
    }

    public function findTransactionWithDetails($transaction_id)
    {
        // 1. Ambil master transaksi
        $transaction = $this->db->table('tbl_transaction')
            ->where('transaction_id', $transaction_id)
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$transaction) {
            return null;
        }

        // 2. Ambil detail akun terkait transaksi ini
        $details = $this->db->table('tbl_transaction_detail td')
            ->select('
            td.transaction_detail_id as td_id,
            td.transaction_id as id,
            td.debet,
            td.kredit,
            td.keterangan as detail_keterangan,
            acc.nama_akun as category,
            acc.is_wallet,
            acc.jenis_wallet
        ')
            ->join('tbl_account acc', 'acc.account_id = td.account_id')
            ->where('td.transaction_id', $transaction_id)
            ->get()
            ->getResultArray();

        $transaction['details'] = $details;

        return $transaction;
    }
}
