/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - cek_saldo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cek_saldo` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;

USE `cek_saldo`;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(3,'2026-08-29-043602','App\\Database\\Migrations\\CreateInitialAccountingTables','default','App',1787982141,1);

/*Table structure for table `tbl_account` */

DROP TABLE IF EXISTS `tbl_account`;

CREATE TABLE `tbl_account` (
  `account_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `kode_akun` varchar(20) NOT NULL,
  `nama_akun` varchar(100) NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `kelompok_akun` tinyint(1) NOT NULL,
  `saldo_normal` enum('DEBIT','KREDIT') NOT NULL,
  `is_wallet` tinyint(1) NOT NULL DEFAULT 0,
  `jenis_wallet` enum('CASH','BANK','EWALLET') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  KEY `tbl_account_room_id_foreign` (`room_id`),
  KEY `tbl_account_parent_id_foreign` (`parent_id`),
  CONSTRAINT `tbl_account_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `tbl_account` (`account_id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `tbl_account_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `tbl_room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_account` */

insert  into `tbl_account`(`account_id`,`room_id`,`kode_akun`,`nama_akun`,`parent_id`,`kelompok_akun`,`saldo_normal`,`is_wallet`,`jenis_wallet`,`created_at`,`created_by`,`updated_at`,`updated_by`,`deleted_at`,`deleted_by`) values 
(32,4,'1-1000','Aset Lancar',NULL,1,'DEBIT',0,NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(33,4,'2-1000','Kewajiban / Utang',NULL,2,'KREDIT',0,NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(34,4,'3-1000','Ekuitas / Modal',NULL,3,'KREDIT',0,NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(35,4,'4-1000','Pendapatan',NULL,4,'KREDIT',0,NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(36,4,'5-1000','Beban / Pengeluaran',NULL,5,'DEBIT',0,NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(37,4,'3-9000','Modal / Saldo Awal',34,3,'KREDIT',0,NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(38,4,'4-1001','Pendapatan Lain-lain',35,4,'KREDIT',0,NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(39,4,'5-1001','Beban Operasional / Harian',36,5,'DEBIT',0,NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(40,4,'1-1001','Tunai',32,1,'DEBIT',1,'CASH','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(41,4,'1-1002','BRI',32,1,'DEBIT',1,'BANK','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(42,4,'1-1003','OVO',32,1,'DEBIT',1,'EWALLET','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(43,4,'1-1004','Dompet Lemari',32,1,'DEBIT',1,'CASH','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL);

/*Table structure for table `tbl_account_snapshot` */

DROP TABLE IF EXISTS `tbl_account_snapshot`;

CREATE TABLE `tbl_account_snapshot` (
  `snapshot_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `account_id` bigint(20) unsigned NOT NULL,
  `periode_bulan` varchar(7) NOT NULL,
  `saldo_akhir` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`snapshot_id`),
  KEY `tbl_account_snapshot_room_id_foreign` (`room_id`),
  KEY `tbl_account_snapshot_account_id_foreign` (`account_id`),
  CONSTRAINT `tbl_account_snapshot_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `tbl_account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_account_snapshot_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `tbl_room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_account_snapshot` */

/*Table structure for table `tbl_log` */

DROP TABLE IF EXISTS `tbl_log`;

CREATE TABLE `tbl_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) DEFAULT NULL,
  `modul` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=286 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tbl_log` */

insert  into `tbl_log`(`id`,`user_id`,`aktivitas`,`modul`,`ip_address`,`user_agent`,`created_at`) values 
(265,20,'Login Berhasil','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:22:26'),
(266,20,'logout','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:22:41'),
(267,20,'Login Berhasil','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:26:37'),
(268,20,'logout','auth','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:29:24'),
(269,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:33:12'),
(270,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:33:22'),
(271,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:34:19'),
(272,20,'logout nama:William Johnson','auth','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:35:32'),
(273,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:35:43'),
(274,20,'logout nama:William Johnson','auth','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:37:45'),
(275,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:38:24'),
(276,20,'logout nama:William Johnson','auth','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:38:27'),
(277,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:39:15'),
(278,20,'logout nama:William Johnson','auth/logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:43:42'),
(279,NULL,'Register William Jones / 08123456789 / bossasoy19@gmail.com- berhasil','auth/save','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-09-30 10:44:10'),
(280,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-04 06:23:19'),
(281,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-10-22 05:57:41'),
(282,NULL,'logout nama:','auth/logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-10-22 06:22:08'),
(283,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-10-22 06:27:12'),
(284,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-10-31 07:37:20'),
(285,20,'Login Berhasil, username :085296071772','auth/proccess','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-06 08:21:49');

/*Table structure for table `tbl_package` */

DROP TABLE IF EXISTS `tbl_package`;

CREATE TABLE `tbl_package` (
  `package_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_paket` varchar(50) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `durasi_hari` int(11) NOT NULL DEFAULT 0,
  `max_room` int(11) NOT NULL DEFAULT 1,
  `max_wallet_per_room` int(11) NOT NULL DEFAULT 3,
  `max_member_per_room` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_package` */

insert  into `tbl_package`(`package_id`,`nama_paket`,`harga`,`durasi_hari`,`max_room`,`max_wallet_per_room`,`max_member_per_room`,`created_at`,`created_by`,`updated_at`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'FREE',0.00,0,1,3,0,'2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(2,'PRO_MONTHLY',29000.00,30,5,10,5,'2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(3,'PRO_YEARLY',249000.00,365,999,999,999,'2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `tbl_permission` */

DROP TABLE IF EXISTS `tbl_permission`;

CREATE TABLE `tbl_permission` (
  `permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_permission` varchar(50) NOT NULL,
  `nama_permission` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `kode_permission` (`kode_permission`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_permission` */

insert  into `tbl_permission`(`permission_id`,`kode_permission`,`nama_permission`,`kategori`,`created_at`,`created_by`,`updated_at`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'VIEW_WALLET_BALANCE','Melihat Saldo Dompet','Dompet','2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(2,'CREATE_WALLET','Membuat Dompet Baru','Dompet','2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(3,'EDIT_WALLET','Mengubah Data Dompet','Dompet','2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(4,'VIEW_TRANSACTION','Melihat Daftar Transaksi','Transaksi','2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(5,'CREATE_TRANSACTION','Menambah Transaksi','Transaksi','2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(6,'EDIT_TRANSACTION','Mengubah Transaksi','Transaksi','2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(7,'DELETE_TRANSACTION','Menghapus Transaksi','Transaksi','2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL),
(8,'VIEW_REPORT','Melihat Laporan Keuangan','Laporan','2026-08-29 05:42:27',NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `tbl_room` */

DROP TABLE IF EXISTS `tbl_room`;

CREATE TABLE `tbl_room` (
  `room_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_room` varchar(100) NOT NULL,
  `jenis_room` tinyint(1) NOT NULL DEFAULT 1,
  `owner_user_id` bigint(20) unsigned NOT NULL,
  `status_room` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`room_id`),
  KEY `tbl_room_owner_user_id_foreign` (`owner_user_id`),
  CONSTRAINT `tbl_room_owner_user_id_foreign` FOREIGN KEY (`owner_user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_room` */

insert  into `tbl_room`(`room_id`,`nama_room`,`jenis_room`,`owner_user_id`,`status_room`,`created_at`,`created_by`,`updated_at`,`updated_by`,`deleted_at`,`deleted_by`) values 
(4,'Keuangan Pribadi Ane',1,20,1,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL);

/*Table structure for table `tbl_room_member` */

DROP TABLE IF EXISTS `tbl_room_member`;

CREATE TABLE `tbl_room_member` (
  `room_member_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` enum('OWNER','VIEWER') NOT NULL DEFAULT 'VIEWER',
  `status_akses` tinyint(1) NOT NULL DEFAULT 1,
  `joined_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`room_member_id`),
  UNIQUE KEY `room_id_user_id` (`room_id`,`user_id`),
  KEY `tbl_room_member_user_id_foreign` (`user_id`),
  CONSTRAINT `tbl_room_member_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `tbl_room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_room_member_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_room_member` */

insert  into `tbl_room_member`(`room_member_id`,`room_id`,`user_id`,`role`,`status_akses`,`joined_at`,`created_at`,`created_by`) values 
(4,4,20,'OWNER',1,'2026-10-22 05:59:30','2026-10-22 05:59:30',20);

/*Table structure for table `tbl_room_member_permission` */

DROP TABLE IF EXISTS `tbl_room_member_permission`;

CREATE TABLE `tbl_room_member_permission` (
  `member_permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_member_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `is_allowed` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`member_permission_id`),
  KEY `tbl_room_member_permission_room_member_id_foreign` (`room_member_id`),
  KEY `tbl_room_member_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `tbl_room_member_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `tbl_permission` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_room_member_permission_room_member_id_foreign` FOREIGN KEY (`room_member_id`) REFERENCES `tbl_room_member` (`room_member_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_room_member_permission` */

/*Table structure for table `tbl_transaction` */

DROP TABLE IF EXISTS `tbl_transaction`;

CREATE TABLE `tbl_transaction` (
  `transaction_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `no_referensi` varchar(50) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_transaksi` enum('PENDING','POSTED') NOT NULL DEFAULT 'POSTED',
  `attachment_url` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachment_url`)),
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `room_id_tanggal_transaksi` (`room_id`,`tanggal_transaksi`),
  CONSTRAINT `tbl_transaction_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `tbl_room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_transaction` */

insert  into `tbl_transaction`(`transaction_id`,`room_id`,`no_referensi`,`tanggal_transaksi`,`deskripsi`,`status_transaksi`,`attachment_url`,`created_at`,`created_by`,`updated_at`,`updated_by`,`deleted_at`,`deleted_by`) values 
(6,4,'INIT-20261022055930','2026-10-22','Pencatatan Saldo Awal Dompet','POSTED',NULL,'2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL);

/*Table structure for table `tbl_transaction_detail` */

DROP TABLE IF EXISTS `tbl_transaction_detail`;

CREATE TABLE `tbl_transaction_detail` (
  `transaction_detail_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned NOT NULL,
  `account_id` bigint(20) unsigned NOT NULL,
  `debet` decimal(15,2) NOT NULL DEFAULT 0.00,
  `kredit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`transaction_detail_id`),
  KEY `tbl_transaction_detail_account_id_foreign` (`account_id`),
  KEY `transaction_id_account_id` (`transaction_id`,`account_id`),
  CONSTRAINT `tbl_transaction_detail_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `tbl_account` (`account_id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_transaction_detail_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `tbl_transaction` (`transaction_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_transaction_detail` */

insert  into `tbl_transaction_detail`(`transaction_detail_id`,`transaction_id`,`account_id`,`debet`,`kredit`,`keterangan`,`created_at`,`created_by`,`updated_at`,`updated_by`,`deleted_at`,`deleted_by`) values 
(15,6,40,1000.00,0.00,'Saldo Awal Tunai','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(16,6,41,25000.00,0.00,'Saldo Awal BRI','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(17,6,42,10000.00,0.00,'Saldo Awal OVO','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(18,6,43,1500.00,0.00,'Saldo Awal Dompet Lemari','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL),
(19,6,37,0.00,37500.00,'Total Kontribusi Saldo Awal','2026-10-22 05:59:30',20,NULL,NULL,NULL,NULL);

/*Table structure for table `tbl_user_subscription` */

DROP TABLE IF EXISTS `tbl_user_subscription`;

CREATE TABLE `tbl_user_subscription` (
  `subscription_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `package_id` bigint(20) unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `expired_date` datetime NOT NULL,
  `status_subscription` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`subscription_id`),
  KEY `tbl_user_subscription_user_id_foreign` (`user_id`),
  KEY `tbl_user_subscription_package_id_foreign` (`package_id`),
  CONSTRAINT `tbl_user_subscription_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `tbl_package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_user_subscription_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_user_subscription` */

/*Table structure for table `tbl_users` */

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status_akun` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` datetime DEFAULT NULL,
  `wa_verified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `no_wa` (`no_wa`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_users` */

insert  into `tbl_users`(`user_id`,`nama`,`email`,`no_wa`,`password`,`status_akun`,`email_verified_at`,`wa_verified_at`,`created_at`,`created_by`,`updated_at`,`updated_by`,`deleted_at`,`deleted_by`) values 
(20,'William Johnson','jhonibambangsutejo@gmail.com','085296071772','$2y$10$JpTddUE5f9hFwxQg9xUg9elAEitfAn.vMjj6d1OmdupP55dYw0n72',1,NULL,NULL,'0000-00-00 00:00:00',159753,NULL,NULL,NULL,NULL),
(21,'William Jones','bossasoy19@gmail.com','08123456789','$2y$10$ReRtZQL.PuyrMYpprbJuG.FuKos8.RuG0voA8SZDwo/wgJDxX.3vC',1,NULL,NULL,'0000-00-00 00:00:00',159753,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
