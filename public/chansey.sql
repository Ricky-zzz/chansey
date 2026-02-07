-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for chansey
CREATE DATABASE IF NOT EXISTS `chansey` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `chansey`;

-- Dumping structure for table chansey.accountants
CREATE TABLE IF NOT EXISTS `accountants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accountants_employee_id_unique` (`employee_id`),
  KEY `accountants_user_id_foreign` (`user_id`),
  KEY `accountants_last_name_index` (`last_name`),
  CONSTRAINT `accountants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.accountants: ~0 rows (approximately)
DELETE FROM `accountants`;
INSERT INTO `accountants` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
	(1, 2, 'ACC-GP-001', 'Gwen', 'Perez', '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admins_user_id_foreign` (`user_id`),
  CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admins: ~0 rows (approximately)
DELETE FROM `admins`;
INSERT INTO `admins` (`id`, `user_id`, `full_name`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Super Administrator', '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.admissions
CREATE TABLE IF NOT EXISTS `admissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `admission_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `station_id` bigint unsigned DEFAULT NULL,
  `bed_id` bigint unsigned DEFAULT NULL,
  `attending_physician_id` bigint unsigned NOT NULL,
  `admitting_clerk_id` bigint unsigned NOT NULL,
  `admission_date` datetime NOT NULL,
  `discharge_date` datetime DEFAULT NULL,
  `admission_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `case_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admitted',
  `chief_complaint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_diagnosis` text COLLATE utf8mb4_unicode_ci,
  `mode_of_arrival` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_vitals` json DEFAULT NULL,
  `known_allergies` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admissions_admission_number_unique` (`admission_number`),
  KEY `admissions_patient_id_foreign` (`patient_id`),
  KEY `admissions_station_id_foreign` (`station_id`),
  KEY `admissions_bed_id_foreign` (`bed_id`),
  KEY `admissions_attending_physician_id_foreign` (`attending_physician_id`),
  KEY `admissions_admitting_clerk_id_foreign` (`admitting_clerk_id`),
  KEY `admissions_status_index` (`status`),
  CONSTRAINT `admissions_admitting_clerk_id_foreign` FOREIGN KEY (`admitting_clerk_id`) REFERENCES `users` (`id`),
  CONSTRAINT `admissions_attending_physician_id_foreign` FOREIGN KEY (`attending_physician_id`) REFERENCES `physicians` (`id`),
  CONSTRAINT `admissions_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`),
  CONSTRAINT `admissions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admissions_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admissions: ~0 rows (approximately)
DELETE FROM `admissions`;

-- Dumping structure for table chansey.admission_billing_infos
CREATE TABLE IF NOT EXISTS `admission_billing_infos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `primary_insurance_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_relationship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admission_billing_infos_admission_id_foreign` (`admission_id`),
  CONSTRAINT `admission_billing_infos_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admission_billing_infos: ~0 rows (approximately)
DELETE FROM `admission_billing_infos`;

-- Dumping structure for table chansey.appointments
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appointment_slot_id` bigint unsigned NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Booked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_appointment_slot_id_foreign` (`appointment_slot_id`),
  KEY `appointments_status_index` (`status`),
  KEY `appointments_department_id_foreign` (`department_id`),
  CONSTRAINT `appointments_appointment_slot_id_foreign` FOREIGN KEY (`appointment_slot_id`) REFERENCES `appointment_slots` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.appointments: ~0 rows (approximately)
DELETE FROM `appointments`;

-- Dumping structure for table chansey.appointment_slots
CREATE TABLE IF NOT EXISTS `appointment_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `physician_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `capacity` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_slots_physician_id_foreign` (`physician_id`),
  CONSTRAINT `appointment_slots_physician_id_foreign` FOREIGN KEY (`physician_id`) REFERENCES `physicians` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.appointment_slots: ~0 rows (approximately)
DELETE FROM `appointment_slots`;

-- Dumping structure for table chansey.beds
CREATE TABLE IF NOT EXISTS `beds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `bed_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beds_bed_code_unique` (`bed_code`),
  KEY `beds_room_id_foreign` (`room_id`),
  KEY `beds_status_index` (`status`),
  CONSTRAINT `beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.beds: ~33 rows (approximately)
DELETE FROM `beds`;
INSERT INTO `beds` (`id`, `room_id`, `bed_code`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'ER-001-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(2, 1, 'ER-001-B', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(3, 1, 'ER-001-C', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(4, 1, 'ER-001-D', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 1, 'ER-001-E', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(6, 1, 'ER-001-F', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(7, 1, 'ER-001-G', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(8, 1, 'ER-001-H', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(9, 1, 'ER-001-I', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(10, 1, 'ER-001-J', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(11, 2, 'ICU-001-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(12, 2, 'ICU-001-B', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(13, 2, 'ICU-001-C', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(14, 2, 'ICU-001-D', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(15, 2, 'ICU-001-E', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(16, 2, 'ICU-001-F', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(17, 2, 'ICU-001-G', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(18, 2, 'ICU-001-H', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(19, 3, 'MS-WARD-001-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(20, 3, 'MS-WARD-001-B', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(21, 3, 'MS-WARD-001-C', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(22, 3, 'MS-WARD-001-D', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(23, 3, 'MS-WARD-001-E', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(24, 3, 'MS-WARD-001-F', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(25, 4, 'OB-001-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(26, 4, 'OB-001-B', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(27, 4, 'OB-001-C', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(28, 4, 'OB-001-D', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(29, 5, 'PVT-001-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(30, 6, 'PVT-002-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(31, 7, 'PVT-003-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(32, 8, 'PVT-004-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(33, 9, 'PVT-005-A', 'Available', '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.billable_items
CREATE TABLE IF NOT EXISTS `billable_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medical',
  PRIMARY KEY (`id`),
  KEY `billable_items_admission_id_foreign` (`admission_id`),
  CONSTRAINT `billable_items_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.billable_items: ~0 rows (approximately)
DELETE FROM `billable_items`;

-- Dumping structure for table chansey.billings
CREATE TABLE IF NOT EXISTS `billings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `processed_by` bigint unsigned NOT NULL,
  `breakdown` json NOT NULL,
  `gross_total` decimal(10,2) NOT NULL,
  `final_total` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `change` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Paid',
  `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billings_receipt_number_unique` (`receipt_number`),
  KEY `billings_admission_id_foreign` (`admission_id`),
  KEY `billings_processed_by_foreign` (`processed_by`),
  CONSTRAINT `billings_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `billings_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.billings: ~0 rows (approximately)
DELETE FROM `billings`;

-- Dumping structure for table chansey.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table chansey.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table chansey.clinical_logs
CREATE TABLE IF NOT EXISTS `clinical_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `medical_order_id` bigint unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clinical_logs_admission_id_foreign` (`admission_id`),
  KEY `clinical_logs_user_id_foreign` (`user_id`),
  KEY `clinical_logs_medical_order_id_foreign` (`medical_order_id`),
  KEY `clinical_logs_type_index` (`type`),
  CONSTRAINT `clinical_logs_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `clinical_logs_medical_order_id_foreign` FOREIGN KEY (`medical_order_id`) REFERENCES `medical_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `clinical_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.clinical_logs: ~0 rows (approximately)
DELETE FROM `clinical_logs`;

-- Dumping structure for table chansey.daily_time_records
CREATE TABLE IF NOT EXISTS `daily_time_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  `total_hours` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ongoing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `daily_time_records_user_id_foreign` (`user_id`),
  CONSTRAINT `daily_time_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.daily_time_records: ~0 rows (approximately)
DELETE FROM `daily_time_records`;

-- Dumping structure for table chansey.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.departments: ~6 rows (approximately)
DELETE FROM `departments`;
INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Cardiology', NULL, '2026-02-05 13:11:26', '2026-02-05 13:11:26'),
	(2, 'Pediatrics', NULL, '2026-02-05 13:11:26', '2026-02-05 13:11:26'),
	(3, 'Neurology', NULL, '2026-02-05 13:11:26', '2026-02-05 13:11:26'),
	(4, 'Internal Medicine', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 'Surgery', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(6, 'OB-GYN', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table chansey.general_services
CREATE TABLE IF NOT EXISTS `general_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assigned_area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `general_services_employee_id_unique` (`employee_id`),
  KEY `general_services_last_name_first_name_index` (`last_name`,`first_name`),
  KEY `general_services_user_id_index` (`user_id`),
  KEY `general_services_last_name_index` (`last_name`),
  KEY `general_services_assigned_area_index` (`assigned_area`),
  CONSTRAINT `general_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.general_services: ~0 rows (approximately)
DELETE FROM `general_services`;
INSERT INTO `general_services` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `assigned_area`, `shift_start`, `shift_end`, `created_at`, `updated_at`) VALUES
	(1, 6, 'SVC-FM-001', 'Firan', 'Maravilla', 'Lobby / Wards', '08:00:00', '17:00:00', '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.hospital_fees
CREATE TABLE IF NOT EXISTS `hospital_fees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'per_use',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.hospital_fees: ~5 rows (approximately)
DELETE FROM `hospital_fees`;
INSERT INTO `hospital_fees` (`id`, `name`, `price`, `unit`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Ambulance Service', 2500.00, 'per_use', 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(2, 'Emergency Room Fee', 1000.00, 'flat', 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(3, 'Oxygen Tank Use', 500.00, 'per_hour', 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(4, 'Medical Certificate', 150.00, 'flat', 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 'Electricity / TV', 100.00, 'per_day', 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.inventory_items
CREATE TABLE IF NOT EXISTS `inventory_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` int NOT NULL DEFAULT '0',
  `critical_level` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_items_item_name_index` (`item_name`),
  KEY `inventory_items_category_index` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.inventory_items: ~5 rows (approximately)
DELETE FROM `inventory_items`;
INSERT INTO `inventory_items` (`id`, `item_name`, `category`, `price`, `quantity`, `critical_level`, `created_at`, `updated_at`) VALUES
	(1, 'Admission Kit', 'Hygiene', 350.00, 50, 10, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(2, 'Extra Pillow', 'Linens', 100.00, 50, 10, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(3, 'Wool Blanket', 'Linens', 150.00, 50, 10, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(4, 'Nebulizer Kit', 'Medical', 150.00, 50, 10, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 'Underpad', 'Medical', 50.00, 50, 10, '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table chansey.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table chansey.medical_orders
CREATE TABLE IF NOT EXISTS `medical_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `physician_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `medicine_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `dispensed` tinyint(1) NOT NULL DEFAULT '0',
  `dispensed_by_user_id` bigint unsigned DEFAULT NULL,
  `dispensed_at` timestamp NULL DEFAULT NULL,
  `fulfilled_by_user_id` bigint unsigned DEFAULT NULL,
  `fulfilled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_orders_admission_id_foreign` (`admission_id`),
  KEY `medical_orders_physician_id_foreign` (`physician_id`),
  KEY `medical_orders_medicine_id_foreign` (`medicine_id`),
  KEY `medical_orders_fulfilled_by_user_id_foreign` (`fulfilled_by_user_id`),
  KEY `medical_orders_type_index` (`type`),
  KEY `medical_orders_status_index` (`status`),
  KEY `medical_orders_dispensed_by_user_id_foreign` (`dispensed_by_user_id`),
  CONSTRAINT `medical_orders_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `medical_orders_dispensed_by_user_id_foreign` FOREIGN KEY (`dispensed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_orders_fulfilled_by_user_id_foreign` FOREIGN KEY (`fulfilled_by_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `medical_orders_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`),
  CONSTRAINT `medical_orders_physician_id_foreign` FOREIGN KEY (`physician_id`) REFERENCES `physicians` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.medical_orders: ~0 rows (approximately)
DELETE FROM `medical_orders`;

-- Dumping structure for table chansey.medicines
CREATE TABLE IF NOT EXISTS `medicines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `generic_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dosage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_on_hand` int NOT NULL DEFAULT '0',
  `critical_level` int NOT NULL DEFAULT '20',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medicines_generic_name_index` (`generic_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.medicines: ~5 rows (approximately)
DELETE FROM `medicines`;
INSERT INTO `medicines` (`id`, `generic_name`, `brand_name`, `dosage`, `form`, `stock_on_hand`, `critical_level`, `price`, `expiry_date`, `created_at`, `updated_at`) VALUES
	(1, 'Paracetamol', 'Biogesic', '500mg', 'Tablet', 100, 20, 5.00, '2026-01-01', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(2, 'Phenylephrine', 'Neozep', '10mg', 'Tablet', 100, 20, 7.00, '2026-01-01', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(3, 'Amoxicillin', 'Amoxil', '500mg', 'Capsule', 100, 20, 15.00, '2026-01-01', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(4, 'Carbocisteine', 'Solmux', '500mg', 'Capsule', 100, 20, 12.00, '2026-01-01', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 'Sodium Chloride', 'PNSS 1L', '1L', 'IV Bag', 100, 20, 150.00, '2026-01-01', '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.migrations: ~0 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_03_181247_create_hospital_infrastructure_tables', 1),
	(5, '2025_12_04_142800_create_shift_schedules_table', 1),
	(6, '2025_12_04_142811_create_staff_profiles_tables', 1),
	(7, '2025_12_06_152235_create_inventory_items_table', 1),
	(8, '2025_12_07_035007_create_clinical_core_tables', 1),
	(9, '2025_12_18_163940_create_medicines_table', 1),
	(10, '2025_12_29_114649_create_pharmacy_tables', 1),
	(11, '2025_12_30_172340_create_clinical_operations_tables', 1),
	(12, '2026_01_03_134355_create_nursing_care_plans_table', 1),
	(13, '2026_01_04_171514_add_lab_details_to_patient_files_table', 1),
	(14, '2026_01_06_064933_transfer_request', 1),
	(15, '2026_01_07_143409_create_billing_module_tables', 1),
	(16, '2026_01_09_174241_add_type_to_billable_items', 1),
	(17, '2026_01_11_083951_create_appointments_table', 1),
	(18, '2026_01_11_085815_create_departments_structure', 1),
	(19, '2026_02_04_174647_create_daily_time_records_table', 1),
	(20, '2026_02_05_210910_add_dispensed_to_medical_orders_table', 1);

-- Dumping structure for table chansey.nurses
CREATE TABLE IF NOT EXISTS `nurses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Clinical',
  `station_id` bigint unsigned DEFAULT NULL,
  `shift_schedule_id` bigint unsigned DEFAULT NULL,
  `is_head_nurse` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nurses_employee_id_unique` (`employee_id`),
  KEY `nurses_station_id_foreign` (`station_id`),
  KEY `nurses_shift_schedule_id_foreign` (`shift_schedule_id`),
  KEY `nurses_last_name_first_name_index` (`last_name`,`first_name`),
  KEY `nurses_user_id_index` (`user_id`),
  KEY `nurses_last_name_index` (`last_name`),
  KEY `nurses_designation_index` (`designation`),
  CONSTRAINT `nurses_shift_schedule_id_foreign` FOREIGN KEY (`shift_schedule_id`) REFERENCES `shift_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `nurses_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `nurses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.nurses: ~12 rows (approximately)
DELETE FROM `nurses`;
INSERT INTO `nurses` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `license_number`, `designation`, `station_id`, `shift_schedule_id`, `is_head_nurse`, `created_at`, `updated_at`) VALUES
	(1, 4, 'NUR-ST-001', 'Steph', 'Torres', 'RN-1001', 'Admitting', NULL, NULL, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(2, 5, 'NUR-JB-001', 'Janaih', 'Budy', 'RN-1002', 'Admitting', NULL, NULL, 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(3, 10, 'NUR-RD-101', 'Riovel', 'Dane', 'RN-101', 'Clinical', 1, NULL, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(4, 11, 'NUR-AM-102', 'Althea', 'Marie', 'RN-102', 'Clinical', 1, NULL, 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 12, 'NUR-CM-103', 'Carlos', 'Mendoza', 'RN-103', 'Clinical', 2, NULL, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(6, 13, 'NUR-MS-104', 'Maria', 'Santos', 'RN-104', 'Clinical', 2, NULL, 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(7, 14, 'NUR-AC-105', 'Angelo', 'Cruz', 'RN-105', 'Clinical', 3, NULL, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(8, 15, 'NUR-PR-106', 'Patricia', 'Reyes', 'RN-106', 'Clinical', 3, NULL, 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(9, 16, 'NUR-DF-107', 'Diana', 'Flores', 'RN-107', 'Clinical', 4, NULL, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(10, 17, 'NUR-CG-108', 'Carmen', 'Garcia', 'RN-108', 'Clinical', 4, NULL, 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(11, 18, 'NUR-JL-109', 'Jerome', 'Lim', 'RN-109', 'Clinical', 5, NULL, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(12, 19, 'NUR-BT-110', 'Beatrice', 'Tan', 'RN-110', 'Clinical', 5, NULL, 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.nursing_care_plans
CREATE TABLE IF NOT EXISTS `nursing_care_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `nurse_id` bigint unsigned NOT NULL,
  `assessment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `planning` json DEFAULT NULL,
  `interventions` json DEFAULT NULL,
  `rationale` text COLLATE utf8mb4_unicode_ci,
  `evaluation` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nursing_care_plans_admission_id_foreign` (`admission_id`),
  KEY `nursing_care_plans_nurse_id_foreign` (`nurse_id`),
  CONSTRAINT `nursing_care_plans_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nursing_care_plans_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.nursing_care_plans: ~0 rows (approximately)
DELETE FROM `nursing_care_plans`;

-- Dumping structure for table chansey.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table chansey.patients
CREATE TABLE IF NOT EXISTS `patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_unique_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by_user_id` bigint unsigned NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `sex` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `civil_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Filipino',
  `religion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_permanent` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_present` text COLLATE utf8mb4_unicode_ci,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emergency_contact_relationship` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emergency_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `philhealth_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senior_citizen_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_patient_unique_id_unique` (`patient_unique_id`),
  KEY `patients_created_by_user_id_foreign` (`created_by_user_id`),
  KEY `patients_last_name_first_name_index` (`last_name`,`first_name`),
  KEY `patients_last_name_index` (`last_name`),
  KEY `patients_date_of_birth_index` (`date_of_birth`),
  CONSTRAINT `patients_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patients: ~0 rows (approximately)
DELETE FROM `patients`;

-- Dumping structure for table chansey.patient_files
CREATE TABLE IF NOT EXISTS `patient_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `admission_id` bigint unsigned DEFAULT NULL,
  `medical_order_id` bigint unsigned DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `result_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_by_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_files_patient_id_foreign` (`patient_id`),
  KEY `patient_files_admission_id_foreign` (`admission_id`),
  KEY `patient_files_uploaded_by_id_foreign` (`uploaded_by_id`),
  KEY `patient_files_medical_order_id_foreign` (`medical_order_id`),
  CONSTRAINT `patient_files_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_files_medical_order_id_foreign` FOREIGN KEY (`medical_order_id`) REFERENCES `medical_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_files_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_files_uploaded_by_id_foreign` FOREIGN KEY (`uploaded_by_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patient_files: ~0 rows (approximately)
DELETE FROM `patient_files`;

-- Dumping structure for table chansey.patient_movements
CREATE TABLE IF NOT EXISTS `patient_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `bed_id` bigint unsigned NOT NULL,
  `room_price` decimal(10,2) NOT NULL,
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_movements_admission_id_foreign` (`admission_id`),
  KEY `patient_movements_room_id_foreign` (`room_id`),
  KEY `patient_movements_bed_id_foreign` (`bed_id`),
  CONSTRAINT `patient_movements_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_movements_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`),
  CONSTRAINT `patient_movements_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patient_movements: ~0 rows (approximately)
DELETE FROM `patient_movements`;

-- Dumping structure for table chansey.pharmacists
CREATE TABLE IF NOT EXISTS `pharmacists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pharmacists_employee_id_unique` (`employee_id`),
  KEY `pharmacists_user_id_foreign` (`user_id`),
  CONSTRAINT `pharmacists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.pharmacists: ~0 rows (approximately)
DELETE FROM `pharmacists`;
INSERT INTO `pharmacists` (`id`, `user_id`, `employee_id`, `full_name`, `license_number`, `created_at`, `updated_at`) VALUES
	(1, 3, 'PHR-GH-001', 'Gabriel Hosmillo', '0123456788', '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.physicians
CREATE TABLE IF NOT EXISTS `physicians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `physicians_employee_id_unique` (`employee_id`),
  KEY `physicians_last_name_first_name_index` (`last_name`,`first_name`),
  KEY `physicians_user_id_index` (`user_id`),
  KEY `physicians_last_name_index` (`last_name`),
  KEY `physicians_department_id_foreign` (`department_id`),
  CONSTRAINT `physicians_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `physicians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.physicians: ~3 rows (approximately)
DELETE FROM `physicians`;
INSERT INTO `physicians` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `employment_type`, `created_at`, `updated_at`, `department_id`) VALUES
	(1, 7, 'DOC-SJ-001', 'Shimi', 'Jallores', 'Consultant', '2026-02-05 13:11:27', '2026-02-05 13:11:27', 1),
	(2, 8, 'DOC-BJ-001', 'Bato', 'Jallores', 'Consultant', '2026-02-05 13:11:27', '2026-02-05 13:11:27', 2),
	(3, 9, 'DOC-LJ-001', 'Loyd', 'Jallores', 'Consultant', '2026-02-05 13:11:27', '2026-02-05 13:11:27', 3);

-- Dumping structure for table chansey.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `station_id` bigint unsigned NOT NULL,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '1',
  `price_per_night` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_room_number_unique` (`room_number`),
  KEY `rooms_station_id_foreign` (`station_id`),
  KEY `rooms_room_type_index` (`room_type`),
  KEY `rooms_status_index` (`status`),
  CONSTRAINT `rooms_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.rooms: ~9 rows (approximately)
DELETE FROM `rooms`;
INSERT INTO `rooms` (`id`, `station_id`, `room_number`, `room_type`, `capacity`, `price_per_night`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'ER-001', 'ER', 10, 1000.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(2, 2, 'ICU-001', 'ICU', 8, 5000.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(3, 3, 'MS-WARD-001', 'Ward', 6, 1500.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(4, 4, 'OB-001', 'Ward', 4, 1500.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 5, 'PVT-001', 'Private', 1, 4000.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(6, 5, 'PVT-002', 'Private', 1, 4000.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(7, 5, 'PVT-003', 'Private', 1, 4000.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(8, 5, 'PVT-004', 'Private', 1, 4000.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(9, 5, 'PVT-005', 'Private', 1, 4000.00, 'Active', '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.sessions: ~0 rows (approximately)
DELETE FROM `sessions`;

-- Dumping structure for table chansey.shift_schedules
CREATE TABLE IF NOT EXISTS `shift_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `monday` tinyint(1) NOT NULL DEFAULT '0',
  `tuesday` tinyint(1) NOT NULL DEFAULT '0',
  `wednesday` tinyint(1) NOT NULL DEFAULT '0',
  `thursday` tinyint(1) NOT NULL DEFAULT '0',
  `friday` tinyint(1) NOT NULL DEFAULT '0',
  `saturday` tinyint(1) NOT NULL DEFAULT '0',
  `sunday` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.shift_schedules: ~6 rows (approximately)
DELETE FROM `shift_schedules`;
INSERT INTO `shift_schedules` (`id`, `name`, `start_time`, `end_time`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `created_at`, `updated_at`) VALUES
	(1, 'M-W-F Morning', '08:00:00', '16:00:00', 1, 0, 1, 0, 1, 0, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(2, 'M-W-F Night', '20:00:00', '08:00:00', 1, 0, 1, 0, 1, 0, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(3, 'T-TH-S Morning', '08:00:00', '16:00:00', 0, 1, 0, 1, 0, 1, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(4, 'T-TH-S Night', '20:00:00', '08:00:00', 0, 1, 0, 1, 0, 1, 0, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 'Weekend Morning', '08:00:00', '14:00:00', 0, 0, 0, 0, 0, 1, 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(6, 'Weekend Night', '20:00:00', '02:00:00', 0, 0, 0, 0, 0, 1, 1, '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.stations
CREATE TABLE IF NOT EXISTS `stations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `station_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stations_station_name_unique` (`station_name`),
  UNIQUE KEY `stations_station_code_unique` (`station_code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.stations: ~6 rows (approximately)
DELETE FROM `stations`;
INSERT INTO `stations` (`id`, `station_name`, `station_code`, `floor_location`, `created_at`, `updated_at`) VALUES
	(1, 'Emergency Room', 'ER', 'Ground Floor', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(2, 'Intensive Care Unit', 'ICU', '2nd Floor', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(3, 'Medical-Surgical Ward', 'MS-WARD', '3rd Floor', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(4, 'OB-GYN Ward', 'OB', '3rd Floor', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(5, 'Private Wing', 'PVT', '4th Floor', '2026-02-05 13:11:27', '2026-02-05 13:11:27'),
	(6, 'Outpatient Dept / Lobby', 'OPD', 'Ground Floor', '2026-02-05 13:11:27', '2026-02-05 13:11:27');

-- Dumping structure for table chansey.transfer_requests
CREATE TABLE IF NOT EXISTS `transfer_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `medical_order_id` bigint unsigned NOT NULL,
  `requested_by_user_id` bigint unsigned NOT NULL,
  `target_station_id` bigint unsigned NOT NULL,
  `target_bed_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transfer_requests_admission_id_foreign` (`admission_id`),
  KEY `transfer_requests_medical_order_id_foreign` (`medical_order_id`),
  KEY `transfer_requests_requested_by_user_id_foreign` (`requested_by_user_id`),
  KEY `transfer_requests_target_station_id_foreign` (`target_station_id`),
  KEY `transfer_requests_target_bed_id_foreign` (`target_bed_id`),
  CONSTRAINT `transfer_requests_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transfer_requests_medical_order_id_foreign` FOREIGN KEY (`medical_order_id`) REFERENCES `medical_orders` (`id`),
  CONSTRAINT `transfer_requests_requested_by_user_id_foreign` FOREIGN KEY (`requested_by_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `transfer_requests_target_bed_id_foreign` FOREIGN KEY (`target_bed_id`) REFERENCES `beds` (`id`),
  CONSTRAINT `transfer_requests_target_station_id_foreign` FOREIGN KEY (`target_station_id`) REFERENCES `stations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.transfer_requests: ~0 rows (approximately)
DELETE FROM `transfer_requests`;

-- Dumping structure for table chansey.treatment_plans
CREATE TABLE IF NOT EXISTS `treatment_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `physician_id` bigint unsigned NOT NULL,
  `main_problem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `goals` json DEFAULT NULL,
  `interventions` json DEFAULT NULL,
  `expected_outcome` text COLLATE utf8mb4_unicode_ci,
  `evaluation` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatment_plans_admission_id_foreign` (`admission_id`),
  KEY `treatment_plans_physician_id_foreign` (`physician_id`),
  CONSTRAINT `treatment_plans_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatment_plans_physician_id_foreign` FOREIGN KEY (`physician_id`) REFERENCES `physicians` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.treatment_plans: ~0 rows (approximately)
DELETE FROM `treatment_plans`;

-- Dumping structure for table chansey.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `badge_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nurse',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_badge_id_unique` (`badge_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.users: ~19 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `badge_id`, `email`, `email_verified_at`, `password`, `user_type`, `remember_token`, `created_at`, `updated_at`, `profile_image_path`) VALUES
	(1, 'System Admin', 'ADM-001', 'admin@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'admin', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(2, 'Gwen Perez', 'ACC-GP-001', 'gwen.perez@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'accountant', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(3, 'Gabriel Hosmillo', 'PHR-GH-001', 'gabriel.hosmillo@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'pharmacist', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(4, 'Steph Torres', 'NUR-ST-001', 'steph.torres@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(5, 'Janaih Budy', 'NUR-JB-001', 'janaih.budy@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(6, 'Firan Maravilla', 'SVC-FM-001', 'firan@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'general_service', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(7, 'Dr. Shimi Jallores', 'DOC-SJ-001', 'shimi@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'physician', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(8, 'Dr. Bato Jallores', 'DOC-BJ-001', 'bato@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'physician', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(9, 'Dr. Loyd Jallores', 'DOC-LJ-001', 'loyd@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'physician', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(10, 'Riovel Dane', 'NUR-RD-101', 'riovel.dane@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(11, 'Althea Marie', 'NUR-AM-102', 'althea.marie@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(12, 'Carlos Mendoza', 'NUR-CM-103', 'carlos.mendoza@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(13, 'Maria Santos', 'NUR-MS-104', 'maria.santos@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(14, 'Angelo Cruz', 'NUR-AC-105', 'angelo.cruz@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(15, 'Patricia Reyes', 'NUR-PR-106', 'patricia.reyes@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(16, 'Diana Flores', 'NUR-DF-107', 'diana.flores@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(17, 'Carmen Garcia', 'NUR-CG-108', 'carmen.garcia@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(18, 'Jerome Lim', 'NUR-JL-109', 'jerome.lim@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL),
	(19, 'Beatrice Tan', 'NUR-BT-110', 'beatrice.tan@chansey.test', NULL, '$2y$12$OMlwlbtUck62wGaHwPMpneFBHYAoY3EvfvTDVkNHQNKGIf/S4623u', 'nurse', NULL, '2026-02-05 13:11:27', '2026-02-05 13:11:27', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
