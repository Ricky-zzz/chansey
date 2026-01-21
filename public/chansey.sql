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

-- Dumping structure for table chansey.accountants
DROP TABLE IF EXISTS `accountants`;
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
	(1, 2, 'ACC-GP-001', 'Gwen', 'Perez', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

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

-- Dumping data for table chansey.admins: ~0 rows (approximately)
DELETE FROM `admins`;
INSERT INTO `admins` (`id`, `user_id`, `full_name`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Super Administrator', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admissions: ~3 rows (approximately)
DELETE FROM `admissions`;
INSERT INTO `admissions` (`id`, `patient_id`, `admission_number`, `station_id`, `bed_id`, `attending_physician_id`, `admitting_clerk_id`, `admission_date`, `discharge_date`, `admission_type`, `case_type`, `status`, `chief_complaint`, `initial_diagnosis`, `mode_of_arrival`, `temp`, `bp_systolic`, `bp_diastolic`, `pulse_rate`, `respiratory_rate`, `o2_sat`, `known_allergies`, `created_at`, `updated_at`) VALUES
	(1, 1, 'ADM-20260111-001', 2, 7, 1, 4, '2026-01-11 09:39:57', '2026-01-13 08:36:53', 'Inpatient', 'New Case', 'Discharged', 'patient experience severe stomach aches', 'must be ulcer', 'Wheelchair', 12.0, 12, 12, 12, 12, 12, '["peanuts"]', '2026-01-11 01:39:57', '2026-01-13 00:36:53'),
	(2, 2, 'ADM-20260111-002', 2, 5, 1, 4, '2026-01-11 11:06:53', '2026-01-15 13:11:18', 'Outpatient', 'New Case', 'Discharged', 'fdsadsad', 'adasd', 'Walk-in', NULL, NULL, NULL, NULL, NULL, NULL, '[]', '2026-01-11 03:06:53', '2026-01-15 05:11:18'),
	(3, 3, 'ADM-20260113-001', 2, 6, 1, 4, '2026-01-13 08:11:08', NULL, 'Inpatient', 'New Case', 'Admitted', 'Head hurts', NULL, 'Walk-in', NULL, NULL, NULL, NULL, NULL, NULL, '["peanut"]', '2026-01-13 00:11:08', '2026-01-13 00:11:08');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admission_billing_infos: ~2 rows (approximately)
DELETE FROM `admission_billing_infos`;
INSERT INTO `admission_billing_infos` (`id`, `admission_id`, `payment_type`, `primary_insurance_provider`, `policy_number`, `approval_code`, `guarantor_name`, `guarantor_relationship`, `guarantor_contact`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Cash', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 01:39:57', '2026-01-11 01:39:57'),
	(2, 2, 'Cash', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 03:06:53', '2026-01-11 03:06:53');

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
	(1, 'Cardiology', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(2, 'Pediatrics', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(3, 'Neurology', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(4, 'Internal Medicine', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(5, 'Surgery', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(6, 'OB-GYN', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36');

-- Dumping structure for table chansey.appointments
DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `physician_id` bigint unsigned DEFAULT NULL,
  `scheduled_at` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_physician_id_foreign` (`physician_id`),
  KEY `appointments_status_index` (`status`),
  KEY `appointments_department_id_foreign` (`department_id`),
  CONSTRAINT `appointments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_physician_id_foreign` FOREIGN KEY (`physician_id`) REFERENCES `physicians` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.appointments: ~3 rows (approximately)
DELETE FROM `appointments`;
INSERT INTO `appointments` (`id`, `first_name`, `last_name`, `email`, `contact_number`, `purpose`, `physician_id`, `scheduled_at`, `end_time`, `status`, `created_at`, `updated_at`, `department_id`) VALUES
	(1, 'Jacob', 'Wesley', 'dfaye@gmail.com', '09123472922', 'wants to see if has barin cancer (Prefers: 2026-01-12)', 1, '2026-01-12 09:00:00', '2026-01-12 09:30:00', 'Approved', '2026-01-11 06:04:00', '2026-01-11 06:15:09', 1),
	(2, 'Joyce', 'Torres', 'john@example', '1313131313', 'head hurts (Prefers: 2026-01-14)', 1, '2026-01-14 08:00:00', '2026-01-14 08:30:00', 'Approved', '2026-01-13 00:06:40', '2026-01-13 00:12:03', 1),
	(3, 'Shaula', 'Ramos', 'josephleviramos2006@gmail.com', '0909090909', 'feels bad (Prefers: 2026-01-16)', 2, '2026-01-16 08:30:00', '2026-01-16 09:00:00', 'Approved', '2026-01-14 22:24:07', '2026-01-14 22:27:03', 2);

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.beds: ~16 rows (approximately)
DELETE FROM `beds`;
INSERT INTO `beds` (`id`, `room_id`, `bed_code`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'NW-101-A', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(2, 1, 'NW-101-B', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(3, 1, 'NW-101-C', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(4, 1, 'NW-101-D', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(5, 2, 'EW-201-A', 'Cleaning', '2026-01-11 01:12:36', '2026-01-15 05:11:18'),
	(6, 2, 'EW-201-B', 'Occupied', '2026-01-11 01:12:36', '2026-01-13 00:11:08'),
	(7, 2, 'EW-201-C', 'Available', '2026-01-11 01:12:36', '2026-01-13 00:36:53'),
	(8, 2, 'EW-201-D', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(9, 3, 'WW-301-A', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(10, 3, 'WW-301-B', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(11, 3, 'WW-301-C', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(12, 3, 'WW-301-D', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(13, 4, 'SW-401-A', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(14, 4, 'SW-401-B', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(15, 4, 'SW-401-C', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(16, 4, 'SW-401-D', 'Available', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

-- Dumping structure for table chansey.billable_items
DROP TABLE IF EXISTS `billable_items`;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.billable_items: ~2 rows (approximately)
DELETE FROM `billable_items`;
INSERT INTO `billable_items` (`id`, `admission_id`, `name`, `amount`, `quantity`, `total`, `status`, `created_at`, `updated_at`, `type`) VALUES
	(1, 1, 'x ray', 500.00, 1, 500.00, 'Paid', '2026-01-13 00:20:11', '2026-01-13 00:35:38', 'medical'),
	(2, 1, 'Biogesic', 5.00, 1, 5.00, 'Paid', '2026-01-13 00:30:52', '2026-01-13 00:35:38', 'medical'),
	(3, 1, 'Extra Pillow', 100.00, 1, 100.00, 'Paid', '2026-01-13 00:31:57', '2026-01-13 00:35:38', 'inventory'),
	(4, 2, 'Neozep', 7.00, 1, 7.00, 'Paid', '2026-01-15 04:44:11', '2026-01-15 05:10:44', 'medical'),
	(5, 2, 'Neozep', 7.00, 1, 7.00, 'Paid', '2026-01-15 04:44:23', '2026-01-15 05:10:44', 'medical');

-- Dumping structure for table chansey.billings
DROP TABLE IF EXISTS `billings`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.billings: ~0 rows (approximately)
DELETE FROM `billings`;
INSERT INTO `billings` (`id`, `admission_id`, `processed_by`, `breakdown`, `gross_total`, `final_total`, `amount_paid`, `change`, `status`, `receipt_number`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, '{"pf_fee": "12000", "movements": [{"days": 3, "price": 1500, "total": 4500, "bed_code": "EW-201-A", "ended_at": "Jan 13, 2026", "started_at": "Jan 11, 2026", "room_number": "201"}, {"days": 1, "price": 1500, "total": 1500, "bed_code": "EW-201-C", "ended_at": "Present", "started_at": "Jan 13, 2026", "room_number": "201"}], "deductions": {"hmo": "100", "philhealth": "500"}, "items_list": [{"name": "x ray", "type": "medical", "total": 500, "amount": "500.00", "quantity": 1}, {"name": "Biogesic", "type": "medical", "total": 5, "amount": "5.00", "quantity": 1}, {"name": "Extra Pillow", "type": "inventory", "total": 100, "amount": "100.00", "quantity": 1}], "room_total": 6000, "items_total": 605}', 18605.00, 18005.00, 19000.00, 995.00, 'Paid', 'OR-20260113-0001', '2026-01-13 00:35:38', '2026-01-13 00:35:38'),
	(2, 2, 2, '{"pf_fee": "1000", "movements": [{"days": 1, "price": 1500, "total": 1500, "bed_code": "EW-201-A", "ended_at": "Present", "started_at": "Jan 15, 2026", "room_number": "201"}], "deductions": {"hmo": "0", "philhealth": "0"}, "items_list": [{"name": "Neozep", "type": "medical", "total": 14, "amount": "7.00", "quantity": 2}], "room_total": 1500, "items_total": 14}', 2514.00, 2514.00, 3000.00, 486.00, 'Paid', 'OR-20260115-0002', '2026-01-15 05:10:44', '2026-01-15 05:10:44');

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

-- Dumping structure for table chansey.clinical_logs
DROP TABLE IF EXISTS `clinical_logs`;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.clinical_logs: ~9 rows (approximately)
DELETE FROM `clinical_logs`;

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

-- Dumping data for table chansey.general_services: ~0 rows (approximately)
DELETE FROM `general_services`;
INSERT INTO `general_services` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `assigned_area`, `shift_start`, `shift_end`, `created_at`, `updated_at`) VALUES
	(1, 6, 'SVC-FM-001', 'Firan', 'Maravilla', 'Lobby / Wards', '08:00:00', '17:00:00', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

-- Dumping structure for table chansey.hospital_fees
DROP TABLE IF EXISTS `hospital_fees`;
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
	(1, 'Ambulance Service', 2500.00, 'per_use', 1, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(2, 'Emergency Room Fee', 1000.00, 'flat', 1, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(3, 'Oxygen Tank Use', 500.00, 'per_hour', 1, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(4, 'Medical Certificate', 150.00, 'flat', 1, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(5, 'Electricity / TV', 100.00, 'per_day', 1, '2026-01-11 01:12:36', '2026-01-11 01:12:36');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.inventory_items: ~5 rows (approximately)
DELETE FROM `inventory_items`;
INSERT INTO `inventory_items` (`id`, `item_name`, `category`, `price`, `quantity`, `critical_level`, `created_at`, `updated_at`) VALUES
	(1, 'Admission Kit', 'Hygiene', 350.00, 50, 10, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(2, 'Extra Pillow', 'Linens', 100.00, 49, 10, '2026-01-11 01:12:36', '2026-01-13 00:31:57'),
	(3, 'Wool Blanket', 'Linens', 150.00, 50, 10, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(4, 'Nebulizer Kit', 'Medical', 150.00, 50, 10, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(5, 'Underpad', 'Medical', 50.00, 50, 10, '2026-01-11 01:12:36', '2026-01-11 01:12:36');

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

-- Dumping structure for table chansey.medical_orders
DROP TABLE IF EXISTS `medical_orders`;
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
  CONSTRAINT `medical_orders_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `medical_orders_fulfilled_by_user_id_foreign` FOREIGN KEY (`fulfilled_by_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `medical_orders_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`),
  CONSTRAINT `medical_orders_physician_id_foreign` FOREIGN KEY (`physician_id`) REFERENCES `physicians` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.medical_orders: ~5 rows (approximately)
DELETE FROM `medical_orders`;
INSERT INTO `medical_orders` (`id`, `admission_id`, `physician_id`, `type`, `instruction`, `medicine_id`, `quantity`, `frequency`, `status`, `fulfilled_by_user_id`, `fulfilled_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Medication', 'give after meals', 1, 1, 'Every 2 Hours', 'Discontinued', NULL, NULL, '2026-01-13 00:18:05', '2026-01-13 00:36:53'),
	(2, 1, 1, 'Monitoring', 'check vitals', NULL, 1, 'Every 2 Hours', 'Discontinued', NULL, NULL, '2026-01-13 00:18:18', '2026-01-13 00:36:53'),
	(3, 1, 1, 'Laboratory', 'x ray', NULL, 1, 'Once', 'Done', 5, '2026-01-13 00:20:11', '2026-01-13 00:18:28', '2026-01-13 00:20:11'),
	(4, 1, 1, 'Transfer', 'transfer patient to anbother bed for testing', NULL, 1, 'Once', 'Done', NULL, NULL, '2026-01-13 00:18:45', '2026-01-13 00:21:15'),
	(5, 1, 1, 'Discharge', 'Patient good for discharge. Please process billing.', NULL, 1, 'Once', 'Discontinued', NULL, NULL, '2026-01-13 00:32:53', '2026-01-13 00:36:53'),
	(6, 2, 1, 'Medication', 'give as needed', 2, 1, 'PRN', 'Discontinued', NULL, NULL, '2026-01-15 04:42:49', '2026-01-15 05:11:18'),
	(7, 2, 1, 'Discharge', 'Patient good for discharge. Please process billing.', NULL, 1, 'Once', 'Discontinued', NULL, NULL, '2026-01-15 04:42:59', '2026-01-15 05:11:18'),
	(8, 2, 1, 'Transfer', 'just move to other room', NULL, 1, 'Once', 'Done', NULL, NULL, '2026-01-15 04:45:27', '2026-01-15 04:48:03');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.medicines: ~5 rows (approximately)
DELETE FROM `medicines`;
INSERT INTO `medicines` (`id`, `generic_name`, `brand_name`, `dosage`, `form`, `stock_on_hand`, `critical_level`, `price`, `expiry_date`, `created_at`, `updated_at`) VALUES
	(1, 'Paracetamol', 'Biogesic', '500mg', 'Tablet', 99, 20, 5.00, '2026-01-01', '2026-01-11 01:12:36', '2026-01-13 00:30:52'),
	(2, 'Phenylephrine', 'Neozep', '10mg', 'Tablet', 98, 20, 7.00, '2026-01-01', '2026-01-11 01:12:36', '2026-01-15 04:44:23'),
	(3, 'Amoxicillin', 'Amoxil', '500mg', 'Capsule', 100, 20, 15.00, '2026-01-01', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(4, 'Carbocisteine', 'Solmux', '500mg', 'Capsule', 100, 20, 12.00, '2026-01-01', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(5, 'Sodium Chloride', 'PNSS 1L', '1L', 'IV Bag', 100, 20, 150.00, '2026-01-01', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

-- Dumping structure for table chansey.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.migrations: ~0 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_03_181247_create_hospital_infrastructure_tables', 1),
	(5, '2025_12_04_142811_create_staff_profiles_tables', 1),
	(6, '2025_12_06_152235_create_inventory_items_table', 1),
	(7, '2025_12_07_035007_create_clinical_core_tables', 1),
	(8, '2025_12_18_163940_create_medicines_table', 1),
	(9, '2025_12_29_114649_create_pharmacy_tables', 1),
	(10, '2025_12_30_172340_create_clinical_operations_tables', 1),
	(11, '2026_01_03_134355_create_nursing_care_plans_table', 1),
	(12, '2026_01_04_171514_add_lab_details_to_patient_files_table', 1),
	(13, '2026_01_06_064933_transfer_request', 1),
	(14, '2026_01_07_143409_create_billing_module_tables', 1),
	(15, '2026_01_09_174241_add_type_to_billable_items', 1),
	(16, '2026_01_11_083951_create_appointments_table', 1),
	(17, '2026_01_11_085815_create_departments_structure', 1);

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

-- Dumping data for table chansey.nurses: ~0 rows (approximately)
DELETE FROM `nurses`;
INSERT INTO `nurses` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `license_number`, `designation`, `station_id`, `shift_start`, `shift_end`, `created_at`, `updated_at`) VALUES
	(1, 4, 'NUR-ST-001', 'Steph', 'Torres', 'RN-1001', 'Admitting', NULL, '06:00:00', '14:00:00', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(2, 5, 'NUR-RD-001', 'Riovel', 'Dane', '21212123', 'Clinical', 2, '11:10:00', '23:10:00', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

-- Dumping structure for table chansey.nursing_care_plans
DROP TABLE IF EXISTS `nursing_care_plans`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.nursing_care_plans: ~0 rows (approximately)
DELETE FROM `nursing_care_plans`;
INSERT INTO `nursing_care_plans` (`id`, `admission_id`, `nurse_id`, `assessment`, `diagnosis`, `planning`, `interventions`, `rationale`, `evaluation`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 'patient is handsaome', 'acute pain', '["help patient"]', '["care for patient"]', 'to help patient', 'patient feels good', 'Active', '2026-01-13 00:15:33', '2026-01-13 00:15:33');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patients: ~2 rows (approximately)
DELETE FROM `patients`;
INSERT INTO `patients` (`id`, `patient_unique_id`, `created_by_user_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `sex`, `civil_status`, `nationality`, `religion`, `address_permanent`, `address_present`, `contact_number`, `email`, `emergency_contact_name`, `emergency_contact_relationship`, `emergency_contact_number`, `philhealth_number`, `senior_citizen_id`, `created_at`, `updated_at`) VALUES
	(1, 'P-2026-00001', 4, 'Faye', 'Rua', 'Lina', '2014-01-03', 'Female', 'Married', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'dfaye@gmail.com', 'Lina Mark', 'Brother', '09123472928', NULL, NULL, '2026-01-11 01:39:57', '2026-01-11 01:39:57'),
	(2, 'P-2026-00002', 4, 'John', 'Rua', 'Doe', '2000-01-14', 'Male', 'Single', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'rita@gmail.com', 'Lina Mark', 'Brother', '09123472928', NULL, NULL, '2026-01-11 03:06:53', '2026-01-11 03:11:24'),
	(3, 'P-2026-00003', 4, 'Levi', 'Lugtu', 'Ramos', '2006-01-13', 'Female', 'Single', 'Filipino', 'Catholic', 'lipa city', 'lipa city', '12121212', 'levs@gmail.com', 'nat5h ramos', 'brother', '1212121212', NULL, NULL, '2026-01-13 00:11:08', '2026-01-13 00:11:08');

-- Dumping structure for table chansey.patient_files
DROP TABLE IF EXISTS `patient_files`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patient_files: ~2 rows (approximately)
DELETE FROM `patient_files`;
INSERT INTO `patient_files` (`id`, `patient_id`, `admission_id`, `medical_order_id`, `file_path`, `file_name`, `result_type`, `description`, `document_type`, `uploaded_by_id`, `created_at`, `updated_at`) VALUES
	(1, 3, 3, NULL, 'patient_records/3/3/id_1768291868.pdf', 'id.pdf', NULL, NULL, 'Valid ID', 4, '2026-01-13 00:11:09', '2026-01-13 00:11:09'),
	(2, 1, 1, 3, 'patient_records/1/1/Lab_3_1768292411.pdf', 'Lab_3_1768292411.pdf', NULL, 'abnormal', 'Lab Result', 5, '2026-01-13 00:20:11', '2026-01-13 00:20:11');

-- Dumping structure for table chansey.patient_movements
DROP TABLE IF EXISTS `patient_movements`;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patient_movements: ~0 rows (approximately)
DELETE FROM `patient_movements`;
INSERT INTO `patient_movements` (`id`, `admission_id`, `room_id`, `bed_id`, `room_price`, `started_at`, `ended_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 5, 1500.00, '2026-01-11 09:39:57', '2026-01-13 08:21:15', '2026-01-11 01:39:57', '2026-01-13 00:21:15'),
	(2, 3, 2, 6, 1500.00, '2026-01-13 08:11:08', NULL, '2026-01-13 00:11:08', '2026-01-13 00:11:08'),
	(3, 1, 2, 7, 1500.00, '2026-01-13 08:21:15', '2026-01-13 08:36:53', '2026-01-13 00:21:15', '2026-01-13 00:36:53'),
	(4, 2, 2, 5, 1500.00, '2026-01-15 12:48:03', '2026-01-15 13:11:18', '2026-01-15 04:48:03', '2026-01-15 05:11:18');

-- Dumping structure for table chansey.pharmacists
DROP TABLE IF EXISTS `pharmacists`;
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
	(1, 3, 'PHR-GH-001', 'Gabriel Hosmillo', '0123456788', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

-- Dumping structure for table chansey.physicians
DROP TABLE IF EXISTS `physicians`;
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
	(1, 7, 'DOC-SJ-001', 'Shimi', 'Jallores', 'Consultant', '2026-01-11 01:12:36', '2026-01-11 01:12:36', 1),
	(2, 8, 'DOC-BJ-001', 'Bato', 'Jallores', 'Consultant', '2026-01-11 01:12:36', '2026-01-11 01:12:36', 2),
	(3, 9, 'DOC-LJ-001', 'Loyd', 'Jallores', 'Consultant', '2026-01-11 01:12:36', '2026-01-11 01:12:36', 3);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.rooms: ~4 rows (approximately)
DELETE FROM `rooms`;
INSERT INTO `rooms` (`id`, `station_id`, `room_number`, `room_type`, `capacity`, `price_per_night`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, '101', 'Ward', 4, 1500.00, 'Active', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(2, 2, '201', 'Ward', 4, 1500.00, 'Active', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(3, 3, '301', 'Ward', 4, 1500.00, 'Active', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(4, 4, '401', 'Ward', 4, 1500.00, 'Active', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

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

-- Dumping data for table chansey.sessions: ~8 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('992U01Vaho6LZD5JYG60g835JDq0F6wJeHxPyKox', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1pMdjZrU1QyZFpOZWYwUHM1OTVmd0lFOHhhbE1KbG9HOXhoTUNDRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJ3ZWxjb21lIjt9fQ==', 1768483954),
	('BYZifiUHxlzdXFXt4hSE44hcBhiFRVLlf8UwXk4k', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT3lmUW9JM3kwVWJqWUVkZlRhTWl4dWNJWkxNR2dVSUFhZUJ3M3BVdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9udXJzZS9jbGluaWNhbC9wYXRpZW50LzIiO3M6NToicm91dGUiO3M6MjQ6Im51cnNlLmNsaW5pY2FsLndhcmQuc2hvdyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1768481488),
	('dtLVb05At2i5lMcaOqfqp0IhStT2dDLLJVcMp5jr', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidGNaWHhUQ1lNSjBIMEZyRFp2b1Mxa1ViU2lxTFlNYmlrM3FzY1lsUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9waHlzaWNpYW4vbXlwYXRpZW50cy8yIjtzOjU6InJvdXRlIjtzOjI1OiJwaHlzaWNpYW4ubXlwYXRpZW50cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1768481494);

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
	(1, 'North Wing', 'NW', '1st Floor', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(2, 'East Wing', 'EW', '1st Floor', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(3, 'West Wing', 'WW', '2nd Floor', '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(4, 'South Wing', 'SW', '2nd Floor', '2026-01-11 01:12:36', '2026-01-11 01:12:36');

-- Dumping structure for table chansey.transfer_requests
DROP TABLE IF EXISTS `transfer_requests`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.transfer_requests: ~1 rows (approximately)
DELETE FROM `transfer_requests`;
INSERT INTO `transfer_requests` (`id`, `admission_id`, `medical_order_id`, `requested_by_user_id`, `target_station_id`, `target_bed_id`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
	(1, 1, 4, 5, 2, 7, 'Approved', 'maarte siya', '2026-01-13 00:20:41', '2026-01-13 00:21:15'),
	(2, 2, 8, 5, 2, 5, 'Approved', 'he dont like current bed', '2026-01-15 04:45:51', '2026-01-15 04:48:03');

-- Dumping structure for table chansey.treatment_plans
DROP TABLE IF EXISTS `treatment_plans`;
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
DROP TABLE IF EXISTS `users`;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_badge_id_unique` (`badge_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.users: ~8 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `badge_id`, `email`, `email_verified_at`, `password`, `user_type`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'System Admin', 'ADM-001', 'admin@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'admin', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(2, 'Gwen Perez', 'ACC-GP-001', 'gwen.perez@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'accountant', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(3, 'Gabriel Hosmillo', 'PHR-GH-001', 'gabriel.hosmillo@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'pharmacist', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(4, 'Steph Torres', 'NUR-ST-001', 'steph.torres@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'nurse', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(5, 'Riovel Dane', 'NUR-RD-001', 'riovel.dane@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'nurse', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(6, 'Firan Maravilla', 'SVC-FM-001', 'firan@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'general_service', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(7, 'Dr. Shimi Jallores', 'DOC-SJ-001', 'shimi@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'physician', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(8, 'Dr. Bato Jallores', 'DOC-BJ-001', 'bato@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'physician', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36'),
	(9, 'Dr. Loyd Jallores', 'DOC-LJ-001', 'loyd@chansey.test', NULL, '$2y$12$IG3O2Vht7a5nUQhl4AjZr.UynrQYKW3chSAOlgCpQV/fo9MKuk/mW', 'physician', NULL, '2026-01-11 01:12:36', '2026-01-11 01:12:36');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
