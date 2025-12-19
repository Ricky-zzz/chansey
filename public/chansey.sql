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
DROP DATABASE IF EXISTS `chansey`;
CREATE DATABASE IF NOT EXISTS `chansey` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `chansey`;

-- Dumping structure for table chansey.admins
DROP TABLE IF EXISTS `admins`;
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

-- Dumping data for table chansey.admins: ~1 rows (approximately)
DELETE FROM `admins`;
INSERT INTO `admins` (`id`, `user_id`, `full_name`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Super Administrator', '2025-12-18 08:50:49', '2025-12-18 08:50:49');

-- Dumping structure for table chansey.admissions
DROP TABLE IF EXISTS `admissions`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admissions: ~2 rows (approximately)
DELETE FROM `admissions`;
INSERT INTO `admissions` (`id`, `patient_id`, `admission_number`, `station_id`, `bed_id`, `attending_physician_id`, `admitting_clerk_id`, `admission_date`, `discharge_date`, `admission_type`, `case_type`, `status`, `chief_complaint`, `initial_diagnosis`, `mode_of_arrival`, `temp`, `bp_systolic`, `bp_diastolic`, `pulse_rate`, `respiratory_rate`, `o2_sat`, `known_allergies`, `created_at`, `updated_at`) VALUES
	(1, 1, 'ADM-20251218-001', 2, 5, 1, 2, '2025-12-18 19:20:43', NULL, 'Inpatient', 'New Case', 'Admitted', 'head hurst', 'must be brain cancer', 'Walk-in', 12.0, 12, 12, 12, 12, 12, '["peanuts"]', '2025-12-18 11:20:43', '2025-12-18 11:20:43'),
	(2, 2, 'ADM-20251218-002', 2, 7, 1, 2, '2025-12-18 19:24:03', NULL, 'Inpatient', 'New Case', 'Admitted', 'dizziness', 'must be tubercolosis', 'Walk-in', 32.0, 32, 32, 32, 32, 32, '["peanuts"]', '2025-12-18 11:24:03', '2025-12-18 11:24:03');

-- Dumping structure for table chansey.admission_billing_infos
DROP TABLE IF EXISTS `admission_billing_infos`;
CREATE TABLE IF NOT EXISTS `admission_billing_infos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- Dumping data for table chansey.admission_billing_infos: ~2 rows (approximately)
DELETE FROM `admission_billing_infos`;
INSERT INTO `admission_billing_infos` (`id`, `admission_id`, `payment_type`, `primary_insurance_provider`, `policy_number`, `approval_code`, `guarantor_name`, `guarantor_relationship`, `guarantor_contact`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Cash', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-18 11:20:43', '2025-12-18 11:20:43'),
	(2, 2, 'Cash', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-18 11:24:03', '2025-12-18 11:24:03');

-- Dumping structure for table chansey.beds
DROP TABLE IF EXISTS `beds`;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.beds: ~19 rows (approximately)
DELETE FROM `beds`;
INSERT INTO `beds` (`id`, `room_id`, `bed_code`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'NW-101-A', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 1, 'NW-101-B', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 1, 'NW-101-C', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(4, 1, 'NW-101-D', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(5, 2, 'EW-201-A', 'Occupied', '2025-12-18 08:50:49', '2025-12-18 11:20:43'),
	(6, 2, 'EW-201-B', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(7, 2, 'EW-201-C', 'Occupied', '2025-12-18 08:50:49', '2025-12-18 11:24:03'),
	(8, 2, 'EW-201-D', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(9, 3, 'WW-301-A', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(10, 3, 'WW-301-B', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(11, 3, 'WW-301-C', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(12, 3, 'WW-301-D', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(13, 4, 'SW-401-A', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(14, 4, 'SW-401-B', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(15, 4, 'SW-401-C', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(16, 4, 'SW-401-D', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(17, 5, 'EW-High Class-A', 'Available', '2025-12-18 09:16:23', '2025-12-18 09:16:23'),
	(18, 6, 'EW-ICU1-A', 'Available', '2025-12-18 10:44:33', '2025-12-18 10:44:33'),
	(19, 7, 'EW-E1-A', 'Available', '2025-12-18 10:45:06', '2025-12-18 10:45:06');

-- Dumping structure for table chansey.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table chansey.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table chansey.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
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
DROP TABLE IF EXISTS `general_services`;
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
	(1, 3, 'SVC-FM-001', 'Firan', 'Maravilla', 'Lobby / Wards', '08:00:00', '17:00:00', '2025-12-18 08:50:49', '2025-12-18 08:50:49');

-- Dumping structure for table chansey.inventory_items
DROP TABLE IF EXISTS `inventory_items`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.inventory_items: ~0 rows (approximately)
DELETE FROM `inventory_items`;

-- Dumping structure for table chansey.jobs
DROP TABLE IF EXISTS `jobs`;
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
DROP TABLE IF EXISTS `job_batches`;
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

-- Dumping structure for table chansey.medicines
DROP TABLE IF EXISTS `medicines`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.medicines: ~0 rows (approximately)
DELETE FROM `medicines`;

-- Dumping structure for table chansey.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.migrations: ~8 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_03_181247_create_hospital_infrastructure_tables', 1),
	(5, '2025_12_04_142811_create_staff_profiles_tables', 1),
	(6, '2025_12_06_152235_create_inventory_items_table', 1),
	(7, '2025_12_07_035007_create_clinical_core_tables', 1),
	(8, '2025_12_18_163940_create_medicines_table', 1);

-- Dumping structure for table chansey.nurses
DROP TABLE IF EXISTS `nurses`;
CREATE TABLE IF NOT EXISTS `nurses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Clinical',
  `station_id` bigint unsigned DEFAULT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nurses_employee_id_unique` (`employee_id`),
  KEY `nurses_station_id_foreign` (`station_id`),
  KEY `nurses_last_name_first_name_index` (`last_name`,`first_name`),
  KEY `nurses_user_id_index` (`user_id`),
  KEY `nurses_last_name_index` (`last_name`),
  KEY `nurses_designation_index` (`designation`),
  CONSTRAINT `nurses_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `nurses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.nurses: ~2 rows (approximately)
DELETE FROM `nurses`;
INSERT INTO `nurses` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `license_number`, `designation`, `station_id`, `shift_start`, `shift_end`, `created_at`, `updated_at`) VALUES
	(1, 2, 'NUR-ST-001', 'Steph', 'Torres', 'RN-1001', 'Admitting', NULL, '06:00:00', '14:00:00', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 8, 'NUR-RR-002', 'Riovel', 'Roallos', '3232323', 'Clinical', 2, '01:09:00', '14:10:00', '2025-12-18 09:09:18', '2025-12-18 09:09:18');

-- Dumping structure for table chansey.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table chansey.patients
DROP TABLE IF EXISTS `patients`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patients: ~2 rows (approximately)
DELETE FROM `patients`;
INSERT INTO `patients` (`id`, `patient_unique_id`, `created_by_user_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `sex`, `civil_status`, `nationality`, `religion`, `address_permanent`, `address_present`, `contact_number`, `email`, `emergency_contact_name`, `emergency_contact_relationship`, `emergency_contact_number`, `philhealth_number`, `senior_citizen_id`, `created_at`, `updated_at`) VALUES
	(1, 'P-2025-00001', 2, 'Faye', 'Carubio', 'Lina', '2004-12-19', 'Female', 'Single', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'dino@gmail.com', 'Lina Mark', 'Brother', '09123472928', NULL, NULL, '2025-12-18 11:20:43', '2025-12-18 11:20:43'),
	(2, 'P-2025-00002', 2, 'John', 'Rua', 'Doe', '1999-12-02', 'Male', 'Married', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'dino@gmail.com', 'Lina Mark', 'Brother', '09123472928', NULL, NULL, '2025-12-18 11:24:03', '2025-12-18 11:24:03');

-- Dumping structure for table chansey.patient_files
DROP TABLE IF EXISTS `patient_files`;
CREATE TABLE IF NOT EXISTS `patient_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `admission_id` bigint unsigned DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patient_files: ~0 rows (approximately)
DELETE FROM `patient_files`;

-- Dumping structure for table chansey.physicians
DROP TABLE IF EXISTS `physicians`;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.physicians: ~3 rows (approximately)
DELETE FROM `physicians`;
INSERT INTO `physicians` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `specialization`, `employment_type`, `created_at`, `updated_at`) VALUES
	(1, 4, 'DOC-SJ-001', 'Shimi', 'Jallores', 'Cardiology', 'Consultant', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 5, 'DOC-BJ-001', 'Bato', 'Jallores', 'Pediatrics', 'Consultant', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 6, 'DOC-LJ-001', 'Loyd', 'Jallores', 'Neurology', 'Consultant', '2025-12-18 08:50:49', '2025-12-18 08:50:49');

-- Dumping structure for table chansey.rooms
DROP TABLE IF EXISTS `rooms`;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.rooms: ~7 rows (approximately)
DELETE FROM `rooms`;
INSERT INTO `rooms` (`id`, `station_id`, `room_number`, `room_type`, `capacity`, `price_per_night`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, '101', 'Ward', 4, 0.00, 'Active', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 2, '201', 'Ward', 4, 0.00, 'Active', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 3, '301', 'Ward', 4, 0.00, 'Active', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(4, 4, '401', 'Ward', 4, 0.00, 'Active', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(5, 2, 'High Class', 'Private', 1, 7000.00, 'Active', '2025-12-18 09:16:23', '2025-12-18 09:16:23'),
	(6, 2, 'ICU1', 'ICU', 1, 30000.00, 'Active', '2025-12-18 10:44:33', '2025-12-18 10:44:33'),
	(7, 2, 'E1', 'ER', 1, 12000.00, 'Active', '2025-12-18 10:45:06', '2025-12-18 10:45:06');

-- Dumping structure for table chansey.sessions
DROP TABLE IF EXISTS `sessions`;
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

-- Dumping data for table chansey.sessions: ~2 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('0Jzo2GBN5V0qSlUm6aGlEEXR165pRzni3KqYku6a', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTGliZE9LVDN0QjhlV0lrZW0wajExVjFFV3drQnlQZ3djQWVzZktQSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9udXJzZS9jbGluaWNhbC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjQ6Im51cnNlLmNsaW5pY2FsLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjg7fQ==', 1766087806),
	('dfSk8GSN3bR9Mc0u85rE5TXNHIzTdifHIZUv5ci1', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoibzVoVHZtT3dtWHFBdTdGVkRKZFBEb0k3Q2NOck1uY3JZaTNoSWVHSiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbWFpbnRlbmFuY2Uvcm9vbXMiO3M6NToicm91dGUiO3M6NDI6ImZpbGFtZW50Lm1haW50ZW5hbmNlLnJlc291cmNlcy5yb29tcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRhTkMzTWt5ck5wSG1VZVNqeUhmMWxlWG04ck5HYm1iLnVqQ25tcTlCczVNM3ZBVlRHeGpTNiI7czo2OiJ0YWJsZXMiO2E6MTp7czo0MDoiN2MwZTNmNTg1YWZlNzAwOWI0YmEwY2I4NTMwZmM0ZGNfY29sdW1ucyI7YTo3OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MjA6InN0YXRpb24uc3RhdGlvbl9jb2RlIjtzOjU6ImxhYmVsIjtzOjc6IlN0YXRpb24iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJyb29tX251bWJlciI7czo1OiJsYWJlbCI7czoxMToiUm9vbSBudW1iZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6InJvb21fdHlwZSI7czo1OiJsYWJlbCI7czo5OiJSb29tIHR5cGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImNhcGFjaXR5IjtzOjU6ImxhYmVsIjtzOjQ6IkJlZHMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJiZWRzX2NvdW50IjtzOjU6ImxhYmVsIjtzOjExOiJBY3R1YWwgQmVkcyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTU6InByaWNlX3Blcl9uaWdodCI7czo1OiJsYWJlbCI7czoxMToiUHJpY2UvTmlnaHQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fX1zOjg6ImZpbGFtZW50IjthOjA6e319', 1766083513);

-- Dumping structure for table chansey.stations
DROP TABLE IF EXISTS `stations`;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.stations: ~4 rows (approximately)
DELETE FROM `stations`;
INSERT INTO `stations` (`id`, `station_name`, `station_code`, `floor_location`, `created_at`, `updated_at`) VALUES
	(1, 'North Wing', 'NW', '1st Floor', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 'East Wing', 'EW', '1st Floor', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 'West Wing', 'WW', '2nd Floor', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(4, 'South Wing', 'SW', '2nd Floor', '2025-12-18 08:50:49', '2025-12-18 08:50:49');

-- Dumping structure for table chansey.users
DROP TABLE IF EXISTS `users`;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.users: ~8 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `badge_id`, `email`, `email_verified_at`, `password`, `user_type`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'System Admin', 'ADM-001', 'admin@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'admin', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 'Steph Torres', 'NUR-ST-001', 'steph@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'nurse', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 'Firan Maravilla', 'SVC-FM-001', 'firan@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'general_service', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(4, 'Dr. Shimi Jallores', 'DOC-SJ-001', 'shimi@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'physician', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(5, 'Dr. Bato Jallores', 'DOC-BJ-001', 'bato@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'physician', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(6, 'Dr. Loyd Jallores', 'DOC-LJ-001', 'loyd@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'physician', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(7, 'Riovel Roallos', 'NUR-RR-001', 'nur-rr-001@chansey.local', NULL, '$2y$12$LRn33XzRwhFzsS0KazqSlu3sZKW5FpAuE66xTFqWWizixN31WWRo.', 'nurse', NULL, '2025-12-18 09:07:41', '2025-12-18 09:07:41'),
	(8, 'Riovel Roallos', 'NUR-RR-002', 'nur-rr-002@chansey.local', NULL, '$2y$12$e7Kc8zEjXSuvXr5Qdp4.3ejeDYFIVbXtoVWjlrHDjavizAN0Yb3WG', 'nurse', NULL, '2025-12-18 09:09:18', '2025-12-18 09:09:18');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
