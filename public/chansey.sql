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
	(1, 11, 'ACC-GP-001', 'Gwen', 'Perez', '2026-01-08 05:27:47', '2026-01-08 05:27:47');

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
	(1, 1, 'Super Administrator', '2025-12-18 08:50:49', '2025-12-18 08:50:49');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admissions: ~3 rows (approximately)
DELETE FROM `admissions`;
INSERT INTO `admissions` (`id`, `patient_id`, `admission_number`, `station_id`, `bed_id`, `attending_physician_id`, `admitting_clerk_id`, `admission_date`, `discharge_date`, `admission_type`, `case_type`, `status`, `chief_complaint`, `initial_diagnosis`, `mode_of_arrival`, `temp`, `bp_systolic`, `bp_diastolic`, `pulse_rate`, `respiratory_rate`, `o2_sat`, `known_allergies`, `created_at`, `updated_at`) VALUES
	(1, 1, 'ADM-20251218-001', 2, 17, 1, 2, '2025-12-18 19:20:43', NULL, 'Inpatient', 'New Case', 'Admitted', 'head hurst', 'must be brain cancer', 'Walk-in', 12.0, 12, 12, 12, 12, 12, '["peanuts"]', '2025-12-18 11:20:43', '2026-01-06 03:06:17'),
	(2, 2, 'ADM-20251218-002', 2, 7, 1, 2, '2025-12-18 19:24:03', NULL, 'Inpatient', 'New Case', 'Admitted', 'dizziness', 'must be tubercolosis', 'Walk-in', 32.0, 32, 32, 32, 32, 32, '["peanuts"]', '2025-12-18 11:24:03', '2025-12-18 11:24:03'),
	(7, 7, 'ADM-20260106-002', 2, 6, 1, 2, '2026-01-06 08:42:28', '2026-01-10 14:29:35', 'Inpatient', 'New Case', 'Discharged', 'adasdasd', 'adsadasdads', 'Walk-in', NULL, NULL, NULL, NULL, NULL, NULL, '[]', '2026-01-06 00:42:28', '2026-01-10 06:29:35');

-- Dumping structure for table chansey.admission_billing_infos
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.admission_billing_infos: ~3 rows (approximately)
DELETE FROM `admission_billing_infos`;
INSERT INTO `admission_billing_infos` (`id`, `admission_id`, `payment_type`, `primary_insurance_provider`, `policy_number`, `approval_code`, `guarantor_name`, `guarantor_relationship`, `guarantor_contact`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Cash', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-18 11:20:43', '2025-12-18 11:20:43'),
	(2, 2, 'Cash', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-18 11:24:03', '2025-12-18 11:24:03'),
	(7, 7, 'Cash', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-06 00:42:28', '2026-01-06 00:42:28');

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.beds: ~19 rows (approximately)
DELETE FROM `beds`;
INSERT INTO `beds` (`id`, `room_id`, `bed_code`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'NW-101-A', 'Available', '2025-12-18 08:50:49', '2026-01-06 00:30:33'),
	(2, 1, 'NW-101-B', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 1, 'NW-101-C', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(4, 1, 'NW-101-D', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(5, 2, 'EW-201-A', 'Available', '2025-12-18 08:50:49', '2026-01-06 03:06:17'),
	(6, 2, 'EW-201-B', 'Cleaning', '2025-12-18 08:50:49', '2026-01-10 06:27:23'),
	(7, 2, 'EW-201-C', 'Occupied', '2025-12-18 08:50:49', '2025-12-18 11:24:03'),
	(8, 2, 'EW-201-D', 'Available', '2025-12-18 08:50:49', '2026-01-06 01:33:01'),
	(9, 3, 'WW-301-A', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(10, 3, 'WW-301-B', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(11, 3, 'WW-301-C', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(12, 3, 'WW-301-D', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(13, 4, 'SW-401-A', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(14, 4, 'SW-401-B', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(15, 4, 'SW-401-C', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(16, 4, 'SW-401-D', 'Available', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(17, 5, 'EW-High Class-A', 'Occupied', '2025-12-18 09:16:23', '2026-01-06 03:06:17'),
	(18, 6, 'EW-ICU1-A', 'Available', '2025-12-18 10:44:33', '2026-01-06 01:37:16'),
	(19, 7, 'EW-E1-A', 'Available', '2025-12-18 10:45:06', '2026-01-06 00:39:33');

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.billable_items: ~16 rows (approximately)
DELETE FROM `billable_items`;
INSERT INTO `billable_items` (`id`, `admission_id`, `name`, `amount`, `quantity`, `total`, `status`, `created_at`, `updated_at`, `type`) VALUES
	(2, 1, 'Biogesic', 80.00, 2, 160.00, 'Unpaid', '2026-01-04 01:19:32', '2026-01-04 01:19:32', 'medical'),
	(3, 1, 'Chest X-ray', 500.00, 1, 500.00, 'Unpaid', '2026-01-04 09:49:31', '2026-01-04 09:49:31', 'medical'),
	(4, 1, 'Biogesic', 80.00, 2, 160.00, 'Unpaid', '2026-01-06 02:07:31', '2026-01-06 02:07:31', 'medical'),
	(5, 1, 'Rocephine', 100.00, 1, 100.00, 'Unpaid', '2026-01-06 02:57:24', '2026-01-06 02:57:24', 'medical'),
	(6, 1, 'Biogesic', 80.00, 2, 160.00, 'Unpaid', '2026-01-07 04:00:16', '2026-01-07 04:00:16', 'medical'),
	(7, 1, 'Hospital Gown', 100.00, 1, 100.00, 'Unpaid', '2026-01-07 04:52:30', '2026-01-07 04:52:30', 'inventory'),
	(9, 1, 'Biogesic', 80.00, 2, 160.00, 'Unpaid', '2026-01-09 09:37:33', '2026-01-09 09:37:33', 'medical'),
	(11, 7, 'Biogesic', 80.00, 2, 160.00, 'Paid', '2026-01-04 01:19:32', '2026-01-10 04:14:07', 'medical'),
	(12, 7, 'Chest X-ray', 500.00, 1, 500.00, 'Paid', '2026-01-04 09:49:31', '2026-01-10 04:14:07', 'medical'),
	(13, 7, 'Biogesic', 80.00, 2, 160.00, 'Paid', '2026-01-06 02:07:31', '2026-01-10 04:14:07', 'medical'),
	(14, 7, 'Rocephine', 100.00, 1, 100.00, 'Paid', '2026-01-06 02:57:24', '2026-01-10 04:14:07', 'medical'),
	(15, 7, 'Biogesic', 80.00, 2, 160.00, 'Paid', '2026-01-07 04:00:16', '2026-01-10 04:14:07', 'medical'),
	(16, 7, 'Hospital Gown', 100.00, 1, 100.00, 'Paid', '2026-01-07 04:52:30', '2026-01-10 04:14:07', 'inventory'),
	(17, 7, 'Biogesic', 80.00, 2, 160.00, 'Paid', '2026-01-09 09:37:33', '2026-01-10 04:14:07', 'medical'),
	(18, 7, 'Electrical Fee', 300.00, 4, 1200.00, 'Paid', '2026-01-09 10:27:43', '2026-01-10 04:14:07', 'fee'),
	(19, 7, 'Ambulance', 1000.00, 1, 1000.00, 'Paid', '2026-01-09 10:30:16', '2026-01-10 04:14:07', 'fee');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.billings: ~0 rows (approximately)
DELETE FROM `billings`;
INSERT INTO `billings` (`id`, `admission_id`, `processed_by`, `breakdown`, `gross_total`, `final_total`, `amount_paid`, `change`, `status`, `receipt_number`, `created_at`, `updated_at`) VALUES
	(5, 7, 11, '{"pf_fee": "10000", "movements": [{"days": 5, "price": 1000, "total": 5000, "bed_code": "EW-201-B", "ended_at": "Present", "started_at": "Jan 06, 2026", "room_number": "201"}], "deductions": {"hmo": "800", "philhealth": "1000"}, "items_list": [{"name": "Biogesic", "type": "medical", "total": "160.00", "amount": "80.00", "quantity": 2}, {"name": "Chest X-ray", "type": "medical", "total": "500.00", "amount": "500.00", "quantity": 1}, {"name": "Biogesic", "type": "medical", "total": "160.00", "amount": "80.00", "quantity": 2}, {"name": "Rocephine", "type": "medical", "total": "100.00", "amount": "100.00", "quantity": 1}, {"name": "Biogesic", "type": "medical", "total": "160.00", "amount": "80.00", "quantity": 2}, {"name": "Hospital Gown", "type": "inventory", "total": "100.00", "amount": "100.00", "quantity": 1}, {"name": "Biogesic", "type": "medical", "total": "160.00", "amount": "80.00", "quantity": 2}, {"name": "Electrical Fee", "type": "fee", "total": "1200.00", "amount": "300.00", "quantity": 4}, {"name": "Ambulance", "type": "fee", "total": "1000.00", "amount": "1000.00", "quantity": 1}], "room_total": 5000, "items_total": 3540}', 18540.00, 16740.00, 17000.00, 260.00, 'Paid', 'OR-20260110-0007', '2026-01-10 04:14:07', '2026-01-10 04:14:07');

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.clinical_logs: ~9 rows (approximately)
DELETE FROM `clinical_logs`;
INSERT INTO `clinical_logs` (`id`, `admission_id`, `user_id`, `medical_order_id`, `type`, `data`, `created_at`, `updated_at`) VALUES
	(2, 1, 9, 3, 'Vitals', '{"hr": "21", "o2": "12", "rr": "12", "temp": "12", "bp_systolic": "21", "observation": "he still bretahes", "bp_diastolic": "12"}', '2026-01-04 01:15:28', '2026-01-04 01:15:28'),
	(3, 1, 9, 2, 'Medication', '{"hr": "24", "o2": "24", "rr": "24", "temp": "24", "dosage": 2, "remarks": null, "medicine": "Biogesic", "bp_systolic": "24", "observation": "patient lives", "bp_diastolic": "24"}', '2026-01-04 01:19:32', '2026-01-04 01:19:32'),
	(4, 1, 9, 5, 'Laboratory', '{"note": "Lab Result Uploaded: Chest X-ray", "finding": "Normal"}', '2026-01-04 09:49:31', '2026-01-04 09:49:31'),
	(5, 1, 9, 2, 'Medication', '{"dosage": 2, "remarks": null, "medicine": "Biogesic"}', '2026-01-06 02:07:31', '2026-01-06 02:07:31'),
	(6, 1, 9, 3, 'Vitals', '{"hr": "12", "o2": "12", "rr": "12", "temp": "21", "bp_systolic": "12", "observation": "still alive and kicking", "bp_diastolic": "12"}', '2026-01-06 02:07:54', '2026-01-06 02:07:54'),
	(7, 1, 9, 8, 'Medication', '{"hr": "12", "o2": "12", "rr": "12", "temp": "12", "dosage": 1, "remarks": null, "medicine": "Rocephin", "bp_systolic": "12", "observation": "12121212", "bp_diastolic": "12"}', '2026-01-06 02:57:24', '2026-01-06 02:57:24'),
	(8, 1, 9, 2, 'Medication', '{"hr": "12", "o2": "12", "rr": "12", "temp": "12", "dosage": 2, "remarks": null, "medicine": "Biogesic", "bp_systolic": "12", "observation": "patient slept too well", "bp_diastolic": "12"}', '2026-01-07 04:00:16', '2026-01-07 04:00:16'),
	(9, 1, 9, 3, 'Vitals', '{"hr": "13", "o2": "13", "rr": "13", "temp": "13", "bp_systolic": "13", "observation": "sdsdad", "bp_diastolic": "13"}', '2026-01-07 04:00:30', '2026-01-07 04:00:30'),
	(10, 1, 9, NULL, 'Utility', '{"qty": "1", "note": "Used Item: Hospital Gown", "price": "100.00", "remarks": "patient soiled their own"}', '2026-01-07 04:52:30', '2026-01-07 04:52:30'),
	(12, 7, 4, 10, 'Discharge', '{"note": "Physician cleared patient for discharge."}', '2026-01-07 06:30:04', '2026-01-07 06:30:04'),
	(14, 1, 9, 2, 'Medication', '{"hr": "12", "o2": "12", "rr": "12", "temp": "12", "dosage": 2, "remarks": null, "medicine": "Biogesic", "bp_systolic": "12", "observation": "weqewqeq", "bp_diastolic": "12"}', '2026-01-09 09:37:33', '2026-01-09 09:37:33');

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
	(1, 3, 'SVC-FM-001', 'Firan', 'Maravilla', 'Lobby / Wards', '08:00:00', '17:00:00', '2025-12-18 08:50:49', '2025-12-18 08:50:49');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.hospital_fees: ~4 rows (approximately)
DELETE FROM `hospital_fees`;
INSERT INTO `hospital_fees` (`id`, `name`, `price`, `unit`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Ambulance', 1000.00, 'per_use', 1, '2026-01-09 08:42:20', '2026-01-09 08:42:20'),
	(2, 'Electrical Fee', 300.00, 'per_day', 1, '2026-01-09 08:42:54', '2026-01-09 08:42:54'),
	(3, 'Minor fee', 30.00, 'flat', 0, '2026-01-09 08:59:51', '2026-01-09 09:12:02');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.inventory_items: ~8 rows (approximately)
DELETE FROM `inventory_items`;
INSERT INTO `inventory_items` (`id`, `item_name`, `category`, `price`, `quantity`, `critical_level`, `created_at`, `updated_at`) VALUES
	(2, 'Admission Kit', 'Medical', 100.00, 100, 10, '2026-01-07 04:37:57', '2026-01-07 04:37:57'),
	(3, 'Hospital Gown', 'Medical', 100.00, 99, 10, '2026-01-07 04:38:16', '2026-01-07 04:52:30'),
	(4, ' Pillow', 'Linens', 100.00, 100, 10, '2026-01-07 04:38:31', '2026-01-07 04:38:31'),
	(5, 'Wool Blanket', 'Linens', 100.00, 100, 10, '2026-01-07 04:38:43', '2026-01-07 04:38:43'),
	(6, 'Patient Gown (Cotton)', 'Medical', 100.00, 100, 10, '2026-01-07 04:39:07', '2026-01-07 04:39:07'),
	(7, 'Suction Catheter', 'Medical', 100.00, 100, 10, '2026-01-07 04:39:40', '2026-01-07 04:39:40'),
	(8, 'Underpad (Disposable)', 'Medical', 100.00, 100, 10, '2026-01-07 04:39:53', '2026-01-07 04:39:53');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.medical_orders: ~5 rows (approximately)
DELETE FROM `medical_orders`;
INSERT INTO `medical_orders` (`id`, `admission_id`, `physician_id`, `type`, `instruction`, `medicine_id`, `quantity`, `frequency`, `status`, `fulfilled_by_user_id`, `fulfilled_at`, `created_at`, `updated_at`) VALUES
	(2, 1, 1, 'Medication', 'after meals', 1, 2, 'Every 4 Hours', 'Active', NULL, NULL, '2026-01-04 00:04:09', '2026-01-04 01:14:47'),
	(3, 1, 1, 'Monitoring', 'check all crucial vitals', NULL, 1, 'Every 6 Hours', 'Active', NULL, NULL, '2026-01-04 00:04:51', '2026-01-04 01:15:28'),
	(5, 1, 1, 'Laboratory', 'Chest X-ray', NULL, 1, 'Once', 'Done', 9, '2026-01-04 09:49:31', '2026-01-04 09:26:58', '2026-01-04 09:49:31'),
	(6, 1, 1, 'Transfer', 'Transfer patient to another room', NULL, 1, 'Once', 'Done', NULL, NULL, '2026-01-06 02:24:01', '2026-01-06 03:06:17'),
	(8, 1, 1, 'Medication', 'No specific instructions.', 3, 1, 'Once', 'Done', NULL, NULL, '2026-01-06 02:53:43', '2026-01-06 02:57:24'),
	(10, 7, 1, 'Discharge', 'Patient good for discharge. Please process billing.', NULL, 1, 'Once', 'Discontinued', NULL, NULL, '2026-01-07 06:30:04', '2026-01-10 06:29:35');

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

-- Dumping data for table chansey.medicines: ~4 rows (approximately)
DELETE FROM `medicines`;
INSERT INTO `medicines` (`id`, `generic_name`, `brand_name`, `dosage`, `form`, `stock_on_hand`, `critical_level`, `price`, `expiry_date`, `created_at`, `updated_at`) VALUES
	(1, 'Paracetamol', 'Biogesic', '500mg', 'Tablet', 92, 20, 80.00, '2026-07-30', '2025-12-30 00:51:45', '2026-01-09 09:37:33'),
	(2, 'Amoxicillin', 'Amoxil', '500mg', 'Capsule', 100, 20, 100.00, '2027-01-14', '2025-12-30 00:52:27', '2025-12-30 00:52:27'),
	(3, 'Ceftriaxone', 'Rocephin', '500mg', 'Syrup', 99, 20, 100.00, '2026-07-03', '2025-12-30 00:53:00', '2026-01-06 02:57:24'),
	(4, 'Salbutamol', 'Ventolin', '500mg', 'Syrup', 100, 20, 100.00, '2026-04-15', '2025-12-30 00:53:30', '2025-12-30 00:53:30'),
	(5, 'Omeprazole', 'Losec', '500mg', 'Tablet', 100, 20, 100.00, '2026-04-16', '2025-12-30 00:54:06', '2025-12-30 00:54:06');

-- Dumping structure for table chansey.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
	(8, '2025_12_18_163940_create_medicines_table', 1),
	(11, '2025_12_29_114649_create_pharmacy_tables', 2),
	(12, '2025_12_30_172340_create_clinical_operations_tables', 2),
	(13, '2026_01_03_134355_create_nursing_care_plans_table', 3),
	(14, '2026_01_04_171514_add_lab_details_to_patient_files_table', 4),
	(15, '2026_01_06_064933_transfer_request', 5),
	(16, '2026_01_07_143409_create_billing_module_tables', 6),
	(17, '2026_01_09_174241_add_type_to_billable_items', 7);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.nurses: ~2 rows (approximately)
DELETE FROM `nurses`;
INSERT INTO `nurses` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `license_number`, `designation`, `station_id`, `shift_start`, `shift_end`, `created_at`, `updated_at`) VALUES
	(1, 2, 'NUR-ST-001', 'Steph', 'Torres', 'RN-1001', 'Admitting', NULL, '06:00:00', '14:00:00', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 9, 'NUR-RD-001', 'Riovel', 'Dane', '21212123', 'Clinical', 2, '11:10:00', '23:10:00', '2025-12-18 19:10:56', '2025-12-18 19:10:56');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.nursing_care_plans: ~0 rows (approximately)
DELETE FROM `nursing_care_plans`;
INSERT INTO `nursing_care_plans` (`id`, `admission_id`, `nurse_id`, `assessment`, `diagnosis`, `planning`, `interventions`, `rationale`, `evaluation`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 3, 'patient is  breathing', 'acute cancer maybe fatal', '["ressurect the patient", "spread love"]', '["give patient meds", "give love\\\\"]', 'to address patient', 'i like it he says', 'Active', '2026-01-04 00:33:42', '2026-01-04 00:33:42');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patients: ~4 rows (approximately)
DELETE FROM `patients`;
INSERT INTO `patients` (`id`, `patient_unique_id`, `created_by_user_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `sex`, `civil_status`, `nationality`, `religion`, `address_permanent`, `address_present`, `contact_number`, `email`, `emergency_contact_name`, `emergency_contact_relationship`, `emergency_contact_number`, `philhealth_number`, `senior_citizen_id`, `created_at`, `updated_at`) VALUES
	(1, 'P-2025-00001', 2, 'Faye', 'Carubio', 'Lina', '2004-12-19', 'Female', 'Single', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'dino@gmail.com', 'Lina Mark', 'Brother', '09123472928', NULL, NULL, '2025-12-18 11:20:43', '2025-12-18 11:20:43'),
	(2, 'P-2025-00002', 2, 'John', 'Rua', 'Doe', '1999-12-02', 'Male', 'Married', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'dino@gmail.com', 'Lina Mark', 'Brother', '09123472928', NULL, NULL, '2025-12-18 11:24:03', '2025-12-18 11:24:03'),
	(7, 'P-2026-00005', 2, 'Andrew', 'Rua', 'Mercado', '2013-03-06', 'Male', 'Single', 'Filipino', 'Catholic', 'Balibago Rosario Batangas', 'Balibago Rosario Batangas', '09123472922', 'rita@gmail.com', 'Lina Mark', 'Brother', '09123472928', NULL, NULL, '2026-01-06 00:42:28', '2026-01-06 00:42:28');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patient_files: ~0 rows (approximately)
DELETE FROM `patient_files`;
INSERT INTO `patient_files` (`id`, `patient_id`, `admission_id`, `medical_order_id`, `file_path`, `file_name`, `result_type`, `description`, `document_type`, `uploaded_by_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 5, 'patient_records/1/1/Lab_5_1767548970.pdf', 'Lab_5_1767548970.pdf', NULL, 'Normal', 'Lab Result', 9, '2026-01-04 09:49:31', '2026-01-04 09:49:31'),
	(2, 1, 1, NULL, 'patient_records/1/1/id_1767697633.pdf', 'id.pdf', NULL, NULL, 'Valid ID', 2, '2026-01-06 03:07:13', '2026-01-06 03:07:13');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.patient_movements: ~4 rows (approximately)
DELETE FROM `patient_movements`;
INSERT INTO `patient_movements` (`id`, `admission_id`, `room_id`, `bed_id`, `room_price`, `started_at`, `ended_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 5, 1000.00, '2026-01-05 08:46:25', '2026-01-06 11:06:17', '2026-01-06 00:46:25', '2026-01-06 03:06:17'),
	(3, 7, 2, 6, 1000.00, '2026-01-06 08:42:28', '2026-01-10 14:27:23', '2026-01-06 00:49:25', '2026-01-10 06:27:23'),
	(4, 2, 2, 7, 1000.00, '2025-12-18 19:24:03', NULL, '2026-01-06 00:49:25', '2026-01-06 00:49:25'),
	(8, 1, 5, 17, 7000.00, '2026-01-06 11:06:17', NULL, '2026-01-06 03:06:17', '2026-01-06 03:06:17');

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
	(1, 12, 'PHR-GH-001', 'Gabriel Hosmillo', '0123456788', '2026-01-08 05:28:15', '2026-01-08 05:28:15');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.physicians: ~3 rows (approximately)
DELETE FROM `physicians`;
INSERT INTO `physicians` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `specialization`, `employment_type`, `created_at`, `updated_at`) VALUES
	(1, 4, 'DOC-SJ-001', 'Shimi', 'Jallores', 'Cardiology', 'Consultant', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 5, 'DOC-BJ-001', 'Bato', 'Jallores', 'Pediatrics', 'Consultant', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 6, 'DOC-LJ-001', 'Loyd', 'Jallores', 'Neurology', 'Consultant', '2025-12-18 08:50:49', '2025-12-18 08:50:49');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.rooms: ~6 rows (approximately)
DELETE FROM `rooms`;
INSERT INTO `rooms` (`id`, `station_id`, `room_number`, `room_type`, `capacity`, `price_per_night`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, '101', 'Ward', 4, 1000.00, 'Active', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 2, '201', 'Ward', 4, 1000.00, 'Active', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 3, '301', 'Ward', 4, 1000.00, 'Active', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(4, 4, '401', 'Ward', 4, 1000.00, 'Active', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(5, 2, 'High Class', 'Private', 1, 7000.00, 'Active', '2025-12-18 09:16:23', '2025-12-18 09:16:23'),
	(6, 2, 'ICU1', 'ICU', 1, 30000.00, 'Active', '2025-12-18 10:44:33', '2025-12-18 10:44:33'),
	(7, 2, 'E1', 'ER', 1, 12000.00, 'Active', '2025-12-18 10:45:06', '2025-12-18 10:45:06');

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
	('bZa0SCiZWNobNsal293MAekfZkFBmXmsNSr9FRWr', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiVVF6U2E4VXBJaUREV1hLaHo3dWtla1lRdnRBcVRuUGVjb21oV2pJYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYWludGVuYW5jZS9iZWRzIjtzOjU6InJvdXRlIjtzOjQxOiJmaWxhbWVudC5tYWludGVuYW5jZS5yZXNvdXJjZXMuYmVkcy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRhTkMzTWt5ck5wSG1VZVNqeUhmMWxlWG04ck5HYm1iLnVqQ25tcTlCczVNM3ZBVlRHeGpTNiI7czo2OiJ0YWJsZXMiO2E6MTp7czo0MDoiMDQ5ZWRiNjkwYTJkNTJjZGYzY2EwNmQyN2VkMDQ0MDdfY29sdW1ucyI7YTozOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTY6InJvb20ucm9vbV9udW1iZXIiO3M6NToibGFiZWwiO3M6NDoiUm9vbSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6ODoiYmVkX2NvZGUiO3M6NToibGFiZWwiO3M6ODoiQmVkIGNvZGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fX1zOjg6ImZpbGFtZW50IjthOjA6e319', 1768055663);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.stations: ~4 rows (approximately)
DELETE FROM `stations`;
INSERT INTO `stations` (`id`, `station_name`, `station_code`, `floor_location`, `created_at`, `updated_at`) VALUES
	(1, 'North Wing', 'NW', '1st Floor', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 'East Wing', 'EW', '1st Floor', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 'West Wing', 'WW', '2nd Floor', '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(4, 'South Wing', 'SW', '2nd Floor', '2025-12-18 08:50:49', '2025-12-18 08:50:49');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.transfer_requests: ~0 rows (approximately)
DELETE FROM `transfer_requests`;
INSERT INTO `transfer_requests` (`id`, `admission_id`, `medical_order_id`, `requested_by_user_id`, `target_station_id`, `target_bed_id`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
	(2, 1, 6, 9, 2, 17, 'Approved', 'patient wants privacy', '2026-01-06 02:31:50', '2026-01-06 03:06:17');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.treatment_plans: ~0 rows (approximately)
DELETE FROM `treatment_plans`;
INSERT INTO `treatment_plans` (`id`, `admission_id`, `physician_id`, `main_problem`, `goals`, `interventions`, `expected_outcome`, `evaluation`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Acute pneumonia', '["stabilize bp", "heal patient", "find gf"]', '["heal patient", "spread love", "go to gym"]', 'the patient should not die', 'patient is a wuss', 'Active', '2025-12-31 06:22:12', '2025-12-31 06:22:12');

-- Dumping structure for table chansey.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `badge_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_badge_id_unique` (`badge_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table chansey.users: ~9 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `badge_id`, `email`, `email_verified_at`, `password`, `user_type`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'System Admin', 'ADM-001', 'admin@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'admin', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(2, 'Steph Torres', 'NUR-ST-001', 'steph@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'nurse', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(3, 'Firan Maravilla', 'SVC-FM-001', 'firan@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'general_service', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(4, 'Dr. Shimi Jallores', 'DOC-SJ-001', 'shimi@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'physician', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(5, 'Dr. Bato Jallores', 'DOC-BJ-001', 'bato@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'physician', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(6, 'Dr. Loyd Jallores', 'DOC-LJ-001', 'loyd@chansey.test', NULL, '$2y$12$aNC3MkyrNpHmUeSjyHf1leXm8rNGbmb.ujCnmq9Bs5M3vAVTGxjS6', 'physician', NULL, '2025-12-18 08:50:49', '2025-12-18 08:50:49'),
	(9, 'Riovel Dane', 'NUR-RD-001', 'nur-rd-001@chansey.local', NULL, '$2y$12$OggRjgg30XweWiXkAPOtfe5MIQtfK5VbLdZixZ2cRik5jTFjxPCPO', 'nurse', NULL, '2025-12-18 19:10:56', '2025-12-18 19:10:56'),
	(11, 'Gwen Perez', 'ACC-GP-001', 'acc-gp-001@chansey.local', NULL, '$2y$12$UT.xnRcKT6dwoe0Mz7AmoOSJERAHCy7K5WXgd8IbMr.602dopl2u.', 'accountant', NULL, '2026-01-08 05:27:47', '2026-01-08 05:27:47'),
	(12, 'Gabriel Hosmillo', 'PHR-GH-001', 'phr-gh-001@chansey.local', NULL, '$2y$12$ox1KFU5pt/xUXtLVhd.kHebIFNtqgek7bLLHZMPqsNNnYVHiG3ASS', 'pharmacist', NULL, '2026-01-08 05:28:15', '2026-01-08 05:28:15');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
