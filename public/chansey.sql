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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admins: ~0 rows (approximately)
DELETE FROM `admins`;

-- Dumping structure for table chansey.admissions
CREATE TABLE IF NOT EXISTS `admissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `admission_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bed_id` bigint unsigned DEFAULT NULL,
  `attending_physician_id` bigint unsigned NOT NULL,
  `admitting_clerk_id` bigint unsigned NOT NULL,
  `admission_date` datetime NOT NULL,
  `discharge_date` datetime DEFAULT NULL,
  `admission_type` enum('Emergency','Outpatient','Inpatient','Transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `case_type` enum('New Case','Returning','Follow-up') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Admitted','Discharged','Transferred','Died') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admitted',
  `chief_complaint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_diagnosis` text COLLATE utf8mb4_unicode_ci,
  `mode_of_arrival` enum('Walk-in','Ambulance','Wheelchair','Stretcher') COLLATE utf8mb4_unicode_ci NOT NULL,
  `temp` decimal(4,1) DEFAULT NULL,
  `bp_systolic` int DEFAULT NULL,
  `bp_diastolic` int DEFAULT NULL,
  `pulse_rate` int DEFAULT NULL,
  `respiratory_rate` int DEFAULT NULL,
  `o2_sat` int DEFAULT NULL,
  `known_allergies` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admissions_admission_number_unique` (`admission_number`),
  KEY `admissions_patient_id_foreign` (`patient_id`),
  KEY `admissions_bed_id_foreign` (`bed_id`),
  KEY `admissions_attending_physician_id_foreign` (`attending_physician_id`),
  KEY `admissions_admitting_clerk_id_foreign` (`admitting_clerk_id`),
  KEY `admissions_status_index` (`status`),
  CONSTRAINT `admissions_admitting_clerk_id_foreign` FOREIGN KEY (`admitting_clerk_id`) REFERENCES `users` (`id`),
  CONSTRAINT `admissions_attending_physician_id_foreign` FOREIGN KEY (`attending_physician_id`) REFERENCES `physicians` (`id`),
  CONSTRAINT `admissions_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`),
  CONSTRAINT `admissions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admissions: ~1 rows (approximately)
DELETE FROM `admissions`;
INSERT INTO `admissions` (`id`, `patient_id`, `admission_number`, `bed_id`, `attending_physician_id`, `admitting_clerk_id`, `admission_date`, `discharge_date`, `admission_type`, `case_type`, `status`, `chief_complaint`, `initial_diagnosis`, `mode_of_arrival`, `temp`, `bp_systolic`, `bp_diastolic`, `pulse_rate`, `respiratory_rate`, `o2_sat`, `known_allergies`, `created_at`, `updated_at`) VALUES
	(1, 1, 'ADM-20251207-001', 1, 1, 4, '2025-12-07 17:03:07', NULL, 'Outpatient', 'New Case', 'Admitted', 'head Hurts', 'probably cancer', 'Walk-in', 99.0, 99, 99, 99, 99, 99, '["banana", "peanuts"]', '2025-12-07 09:03:07', '2025-12-07 09:03:07'),
	(2, 2, 'ADM-20251207-002', 2, 1, 4, '2025-12-07 17:13:14', NULL, 'Inpatient', 'New Case', 'Admitted', 'Heart hurts', 'heart cancer', 'Wheelchair', 11.0, 11, 11, 11, 11, 11, '["banana", "lina"]', '2025-12-07 09:13:14', '2025-12-07 09:13:14');

-- Dumping structure for table chansey.admission_billing_infos
CREATE TABLE IF NOT EXISTS `admission_billing_infos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `payment_type` enum('Cash','Insurance','HMO','Company') COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admission_billing_infos: ~1 rows (approximately)
DELETE FROM `admission_billing_infos`;
INSERT INTO `admission_billing_infos` (`id`, `admission_id`, `payment_type`, `primary_insurance_provider`, `policy_number`, `approval_code`, `guarantor_name`, `guarantor_relationship`, `guarantor_contact`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Cash', 'Jollibee', '18276262', '3312112313', 'Jollibee', 'Boss', '927292982982', '2025-12-07 09:03:07', '2025-12-07 09:03:07'),
	(2, 2, 'Cash', 'Jollibee', '18276262', '3312112313', 'Jollibee', 'Boss', '927292982982', '2025-12-07 09:13:14', '2025-12-07 09:13:14');

-- Dumping structure for table chansey.beds
CREATE TABLE IF NOT EXISTS `beds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `bed_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Available','Occupied','Cleaning','Maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beds_bed_code_unique` (`bed_code`),
  KEY `beds_room_id_foreign` (`room_id`),
  KEY `beds_status_index` (`status`),
  CONSTRAINT `beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.beds: ~6 rows (approximately)
DELETE FROM `beds`;
INSERT INTO `beds` (`id`, `room_id`, `bed_code`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, '101-A', 'Occupied', '2025-12-06 22:17:02', '2025-12-07 09:03:07'),
	(2, 2, '102-A', 'Occupied', '2025-12-07 07:18:33', '2025-12-07 09:13:14'),
	(3, 2, '102-B', 'Available', '2025-12-07 07:18:33', '2025-12-07 07:18:33'),
	(4, 2, '102-C', 'Available', '2025-12-07 07:18:33', '2025-12-07 07:18:33'),
	(5, 2, '102-D', 'Available', '2025-12-07 07:18:33', '2025-12-07 07:18:33'),
	(6, 2, '102-E', 'Available', '2025-12-07 07:18:33', '2025-12-07 07:18:33');

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

-- Dumping data for table chansey.general_services: ~1 rows (approximately)
DELETE FROM `general_services`;
INSERT INTO `general_services` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `assigned_area`, `shift_start`, `shift_end`, `created_at`, `updated_at`) VALUES
	(1, 2, 'SVC-FM-0001', 'Frian', 'Maravilla', 'East Wing', '13:25:00', '01:25:00', '2025-12-06 20:25:53', '2025-12-06 20:25:53');

-- Dumping structure for table chansey.inventory_items
CREATE TABLE IF NOT EXISTS `inventory_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `critical_level` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_items_item_name_index` (`item_name`),
  KEY `inventory_items_category_index` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.inventory_items: ~0 rows (approximately)
DELETE FROM `inventory_items`;

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

-- Dumping structure for table chansey.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.migrations: ~1 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_04_142811_create_staff_profiles_tables', 1),
	(5, '2025_12_05_181247_create_hospital_infrastructure_tables', 1),
	(6, '2025_12_06_152235_create_inventory_items_table', 1),
	(7, '2025_12_07_035007_create_clinical_core_tables', 1);

-- Dumping structure for table chansey.nurses
CREATE TABLE IF NOT EXISTS `nurses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` enum('Clinical','Admitting') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Clinical',
  `station_assignment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nurses_employee_id_unique` (`employee_id`),
  KEY `nurses_last_name_first_name_index` (`last_name`,`first_name`),
  KEY `nurses_user_id_index` (`user_id`),
  KEY `nurses_last_name_index` (`last_name`),
  KEY `nurses_designation_index` (`designation`),
  KEY `nurses_station_assignment_index` (`station_assignment`),
  CONSTRAINT `nurses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.nurses: ~0 rows (approximately)
DELETE FROM `nurses`;
INSERT INTO `nurses` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `license_number`, `designation`, `station_assignment`, `shift_start`, `shift_end`, `created_at`, `updated_at`) VALUES
	(1, 3, 'NUR-DA-0001', 'Dino', 'Agito', '0123456789', 'Clinical', 'Ward', '14:28:00', '01:29:00', '2025-12-06 20:26:36', '2025-12-06 20:26:36'),
	(2, 4, 'NUR-ST-0001', 'Steph', 'Torres', '0123456788', 'Admitting', 'Ward', '14:30:00', '02:30:00', '2025-12-06 20:27:03', '2025-12-06 20:27:03');

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
  `sex` enum('Male','Female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Separated') COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patients: ~1 rows (approximately)
DELETE FROM `patients`;
INSERT INTO `patients` (`id`, `patient_unique_id`, `created_by_user_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `sex`, `civil_status`, `nationality`, `religion`, `address_permanent`, `address_present`, `contact_number`, `email`, `emergency_contact_name`, `emergency_contact_relationship`, `emergency_contact_number`, `philhealth_number`, `senior_citizen_id`, `created_at`, `updated_at`) VALUES
	(1, 'P-2025-00001', 4, 'John', 'Rua', 'Doe', '2013-12-31', 'Male', 'Married', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'dino@gmail.com', 'Jina Doe', 'Spouse', '09123472928', '84417442', '22222222', '2025-12-07 09:03:07', '2025-12-07 09:03:07'),
	(2, 'P-2025-00002', 4, 'Faye', 'Carubio', 'Lina', '2008-02-28', 'Female', 'Widowed', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'dfaye@gmail.com', 'Lina Mark', 'Brother', '09123472928', '84417442', NULL, '2025-12-07 09:13:14', '2025-12-07 09:13:14');

-- Dumping structure for table chansey.patient_files
CREATE TABLE IF NOT EXISTS `patient_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `admission_id` bigint unsigned DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` enum('General Consent','Privacy Notice','PhilHealth MDR','Insurance LOA','Valid ID','Lab Result','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_by_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_files_patient_id_foreign` (`patient_id`),
  KEY `patient_files_admission_id_foreign` (`admission_id`),
  KEY `patient_files_uploaded_by_id_foreign` (`uploaded_by_id`),
  CONSTRAINT `patient_files_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_files_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_files_uploaded_by_id_foreign` FOREIGN KEY (`uploaded_by_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patient_files: ~5 rows (approximately)
DELETE FROM `patient_files`;
INSERT INTO `patient_files` (`id`, `patient_id`, `admission_id`, `file_path`, `file_name`, `document_type`, `uploaded_by_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'patient_records/1/1/id.pdf', 'id.pdf', 'Valid ID', 4, '2025-12-07 09:03:07', '2025-12-07 09:03:07'),
	(2, 1, 1, 'patient_records/1/1/loa.pdf', 'loa.pdf', 'Insurance LOA', 4, '2025-12-07 09:03:07', '2025-12-07 09:03:07'),
	(3, 1, 1, 'patient_records/1/1/SAC.pdf', 'SAC.pdf', 'General Consent', 4, '2025-12-07 09:03:07', '2025-12-07 09:03:07'),
	(4, 1, 1, 'patient_records/1/1/spc.pdf', 'spc.pdf', 'Privacy Notice', 4, '2025-12-07 09:03:07', '2025-12-07 09:03:07'),
	(5, 1, 1, 'patient_records/1/1/ph.pdf', 'ph.pdf', 'PhilHealth MDR', 4, '2025-12-07 09:03:07', '2025-12-07 09:03:07'),
	(6, 2, 2, 'patient_records/2/2/id.pdf', 'id.pdf', 'Valid ID', 4, '2025-12-07 09:13:14', '2025-12-07 09:13:14'),
	(7, 2, 2, 'patient_records/2/2/loa.pdf', 'loa.pdf', 'Insurance LOA', 4, '2025-12-07 09:13:14', '2025-12-07 09:13:14'),
	(8, 2, 2, 'patient_records/2/2/SAC.pdf', 'SAC.pdf', 'General Consent', 4, '2025-12-07 09:13:14', '2025-12-07 09:13:14'),
	(9, 2, 2, 'patient_records/2/2/spc.pdf', 'spc.pdf', 'Privacy Notice', 4, '2025-12-07 09:13:14', '2025-12-07 09:13:14'),
	(10, 2, 2, 'patient_records/2/2/ph.pdf', 'ph.pdf', 'PhilHealth MDR', 4, '2025-12-07 09:13:14', '2025-12-07 09:13:14');

-- Dumping structure for table chansey.physicians
CREATE TABLE IF NOT EXISTS `physicians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `physicians_employee_id_unique` (`employee_id`),
  KEY `physicians_last_name_first_name_index` (`last_name`,`first_name`),
  KEY `physicians_user_id_index` (`user_id`),
  KEY `physicians_last_name_index` (`last_name`),
  KEY `physicians_specialization_index` (`specialization`),
  CONSTRAINT `physicians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.physicians: ~2 rows (approximately)
DELETE FROM `physicians`;
INSERT INTO `physicians` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `specialization`, `employment_type`, `created_at`, `updated_at`) VALUES
	(1, 5, 'DOC-SJ-0001', 'Shimi', 'Jallores', 'Internal Medicine', 'Contractual', '2025-12-06 20:27:23', '2025-12-06 20:27:23'),
	(2, 6, 'DOC-BJ-0001', 'Bato', 'Jallores', 'Pediatrics', 'Full-Time', '2025-12-06 20:27:38', '2025-12-06 20:27:38');

-- Dumping structure for table chansey.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type` enum('Private','Semi-Private','Ward','ICU','ER') COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '1',
  `status` enum('Active','Maintenance','Closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_room_number_unique` (`room_number`),
  KEY `rooms_room_type_index` (`room_type`),
  KEY `rooms_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.rooms: ~0 rows (approximately)
DELETE FROM `rooms`;
INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `capacity`, `status`, `created_at`, `updated_at`) VALUES
	(1, '101', 'Private', 1, 'Active', '2025-12-06 22:17:02', '2025-12-06 22:17:02'),
	(2, '102', 'Ward', 5, 'Active', '2025-12-07 07:18:33', '2025-12-07 07:18:33');

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

-- Dumping data for table chansey.sessions: ~1 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('Dc3cKybJs1DLWmWbAgI3B2oTpvWHoZuSmInmUghw', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibTlRc0RxbkNjVjZtS2R6aWIzU3hkQllWQ3I4S0wzd0cyZTF2VjJQdSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9udXJzZS9hZG1pdHRpbmcvcGF0aWVudHMvMiI7czo1OiJyb3V0ZSI7czoyOToibnVyc2UuYWRtaXR0aW5nLnBhdGllbnRzLnNob3ciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1765133449);

-- Dumping structure for table chansey.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `badge_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` enum('admin','nurse','physician','general_service') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nurse',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_badge_id_unique` (`badge_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.users: ~6 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `badge_id`, `email`, `email_verified_at`, `password`, `user_type`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'System Admin', 'ADM-001', 'admin@chansey.test', NULL, '$2y$12$5GnGXqHjRyFFfl.POjQ2y.wqal6l1pjVS.orvN3/lLTwthzBbkodG', 'admin', NULL, '2025-12-06 20:24:37', '2025-12-06 20:24:37'),
	(2, 'Frian Maravilla', 'SVC-FM-0001', 'svc-fm-0001@chansey.local', NULL, '$2y$12$RU8bGy85VYGGKGPi.4HlDuWeKOfiKG8OmGwiNoO48xZQLS/DgKtWu', 'general_service', NULL, '2025-12-06 20:25:53', '2025-12-06 20:25:53'),
	(3, 'Dino Agito', 'NUR-DA-0001', 'nur-da-0001@chansey.local', NULL, '$2y$12$aRfLeBQ1rw7ng9owk1Bzt.xo9JK6THFTXtTDhitMI8I8sz/igytGq', 'nurse', NULL, '2025-12-06 20:26:35', '2025-12-06 20:26:35'),
	(4, 'Steph Torres', 'NUR-ST-0001', 'nur-st-0001@chansey.local', NULL, '$2y$12$1P0hACOy4p.5AmFdV8X2g.5gk1.F.8U9M6B48vOS1duV6Ti.S.ViO', 'nurse', NULL, '2025-12-06 20:27:03', '2025-12-06 20:27:03'),
	(5, 'Shimi Jallores', 'DOC-SJ-0001', 'doc-sj-0001@chansey.local', NULL, '$2y$12$CxIId97AIA4Z2h.YKIIyHucTRpTWBSGF3MAfyzTjFt4ZwUC3ExEwa', 'physician', NULL, '2025-12-06 20:27:23', '2025-12-06 20:27:23'),
	(6, 'Bato Jallores', 'DOC-BJ-0001', 'doc-bj-0001@chansey.local', NULL, '$2y$12$yB4e/1fEXq3mMppbI2I74uUNdCS6OW1Zg83lahAtpGNz3OwuJTMt.', 'physician', NULL, '2025-12-06 20:27:38', '2025-12-06 20:27:38');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
