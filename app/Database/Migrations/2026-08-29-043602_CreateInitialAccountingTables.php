<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInitialAccountingTables extends Migration
{
    public function up()
    {
        // ==========================================
        // 1. TABEL tbl_user
        // ==========================================
        $this->forge->addField([
            'user_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
                'unique'     => true,
            ],
            'no_wa' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'status_akun' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0, // 0: Guest, 1: Aktif, 2: Nonaktif
            ],
            'email_verified_at' => ['type' => 'DATETIME', 'null' => true],
            'wa_verified_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'created_by'        => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_by'        => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'        => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('tbl_user', true);

        // ==========================================
        // 2. TABEL tbl_package
        // ==========================================
        $this->forge->addField([
            'package_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_paket' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'durasi_hari' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'max_room' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'max_wallet_per_room' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 3,
            ],
            'max_member_per_room' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('package_id', true);
        $this->forge->createTable('tbl_package', true);

        // ==========================================
        // 3. TABEL tbl_user_subscription
        // ==========================================
        $this->forge->addField([
            'subscription_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'             => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'package_id'          => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'start_date'          => ['type' => 'DATETIME'],
            'expired_date'        => ['type' => 'DATETIME'],
            'status_subscription' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1], // 1: Aktif, 0: Expired, 2: Canceled
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'created_by'          => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_by'          => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at'          => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'          => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('subscription_id', true);
        $this->forge->addForeignKey('user_id', 'tbl_user', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('package_id', 'tbl_package', 'package_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_user_subscription', true);

        // ==========================================
        // 4. TABEL tbl_room
        // ==========================================
        $this->forge->addField([
            'room_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_room'     => ['type' => 'VARCHAR', 'constraint' => '100'],
            'jenis_room'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1], // 1: Personal, 2: Keluarga, 3: UMKM
            'owner_user_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'status_room'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1], // 1: Aktif, 0: Nonaktif/Arsip
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_by'    => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_by'    => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'    => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('room_id', true);
        $this->forge->addForeignKey('owner_user_id', 'tbl_user', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_room', true);

        // ==========================================
        // 5. TABEL tbl_room_member
        // ==========================================
        $this->forge->addField([
            'room_member_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_id'      => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'user_id'      => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'role'         => ['type' => 'ENUM', 'constraint' => ['OWNER', 'VIEWER'], 'default' => 'VIEWER'],
            'status_akses' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1], // 1: Active, 0: Pending, 2: Revoked
            'joined_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'created_by'   => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('room_member_id', true);
        $this->forge->addUniqueKey(['room_id', 'user_id']);
        $this->forge->addForeignKey('room_id', 'tbl_room', 'room_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'tbl_user', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_room_member', true);

        // ==========================================
        // 6. TABEL tbl_permission
        // ==========================================
        $this->forge->addField([
            'permission_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_permission' => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => true],
            'nama_permission' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'kategori'        => ['type' => 'VARCHAR', 'constraint' => '50'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'created_by'      => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_by'      => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'      => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('permission_id', true);
        $this->forge->createTable('tbl_permission', true);

        // ==========================================
        // 7. TABEL tbl_room_member_permission
        // ==========================================
        $this->forge->addField([
            'member_permission_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_member_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'permission_id'  => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'is_allowed'     => ['type' => 'BOOLEAN', 'default' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'created_by'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_by'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at'     => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('member_permission_id', true);
        $this->forge->addForeignKey('room_member_id', 'tbl_room_member', 'room_member_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'tbl_permission', 'permission_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_room_member_permission', true);

        // ==========================================
        // 8. TABEL tbl_account (COA & Wallet)
        // ==========================================
        $this->forge->addField([
            'account_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_id'       => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'kode_akun'     => ['type' => 'VARCHAR', 'constraint' => '20'],
            'nama_akun'     => ['type' => 'VARCHAR', 'constraint' => '100'],
            'parent_id'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'kelompok_akun' => ['type' => 'TINYINT', 'constraint' => 1], // 1: Aset, 2: Kewajiban, 3: Ekuitas, 4: Pendapatan, 5: Beban
            'saldo_normal'  => ['type' => 'ENUM', 'constraint' => ['DEBIT', 'KREDIT']],
            'is_wallet'     => ['type' => 'BOOLEAN', 'default' => false],
            'jenis_wallet'  => ['type' => 'ENUM', 'constraint' => ['CASH', 'BANK', 'EWALLET'], 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_by'    => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_by'    => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'    => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('account_id', true);
        $this->forge->addForeignKey('room_id', 'tbl_room', 'room_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('parent_id', 'tbl_account', 'account_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('tbl_account', true);

        // ==========================================
        // 9. TABEL tbl_account_snapshot (Tutup Buku Bulanan)
        // ==========================================
        $this->forge->addField([
            'snapshot_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_id'       => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'account_id'    => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'periode_bulan' => ['type' => 'VARCHAR', 'constraint' => '7'], // YYYY-MM
            'saldo_akhir'   => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_by'    => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('snapshot_id', true);
        $this->forge->addForeignKey('room_id', 'tbl_room', 'room_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('account_id', 'tbl_account', 'account_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_account_snapshot', true);

        // ==========================================
        // 10. TABEL tbl_transaction (Header Jurnal)
        // ==========================================
        $this->forge->addField([
            'transaction_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_id'           => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'no_referensi'      => ['type' => 'VARCHAR', 'constraint' => '50'],
            'tanggal_transaksi' => ['type' => 'DATE'],
            'deskripsi'         => ['type' => 'TEXT', 'null' => true],
            'status_transaksi'  => ['type' => 'ENUM', 'constraint' => ['PENDING', 'POSTED'], 'default' => 'POSTED'],
            'attachment_url'    => ['type' => 'JSON', 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'created_by'        => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_by'        => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'        => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('transaction_id', true);
        $this->forge->addForeignKey('room_id', 'tbl_room', 'room_id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['room_id', 'tanggal_transaksi']);
        $this->forge->createTable('tbl_transaction', true);

        // ==========================================
        // 11. TABEL tbl_transaction_detail (Baris Jurnal Debet/Kredit)
        // ==========================================
        $this->forge->addField([
            'transaction_detail_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transaction_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'account_id'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'debet'          => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'kredit'         => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'keterangan'     => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'created_by'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_by'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'deleted_at'     => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('transaction_detail_id', true);
        $this->forge->addForeignKey('transaction_id', 'tbl_transaction', 'transaction_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('account_id', 'tbl_account', 'account_id', 'RESTRICT', 'CASCADE');
        $this->forge->addKey(['transaction_id', 'account_id']);
        $this->forge->createTable('tbl_transaction_detail', true);
    }

    public function down()
    {
        // Drop tabel dengan urutan terbalik untuk menghindari Foreign Key Constraint Fail
        $this->forge->dropTable('tbl_transaction_detail', true);
        $this->forge->dropTable('tbl_transaction', true);
        $this->forge->dropTable('tbl_account_snapshot', true);
        $this->forge->dropTable('tbl_account', true);
        $this->forge->dropTable('tbl_room_member_permission', true);
        $this->forge->dropTable('tbl_permission', true);
        $this->forge->dropTable('tbl_room_member', true);
        $this->forge->dropTable('tbl_room', true);
        $this->forge->dropTable('tbl_user_subscription', true);
        $this->forge->dropTable('tbl_package', true);
        $this->forge->dropTable('tbl_user', true);
    }
}
