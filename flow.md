# Chansey Database Structure

## users
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- name -> varchar(255) NOT NULL
- badge_id -> varchar(255) NOT NULL UNIQUE
- email -> varchar(255) NOT NULL UNIQUE
- email_verified_at -> timestamp NULL
- password -> varchar(255) NOT NULL
- user_type -> string NOT NULL DEFAULT 'nurse' roles are ('admin','nurse','physician','general_service','pharmacist')
- remember_token -> varchar(100) NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## admins
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- user_id -> bigint unsigned NOT NULL (Foreign Key -> users.id ON DELETE CASCADE)
- full_name -> varchar(255) NOT NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## stations
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- station_name -> varchar(255) NOT NULL UNIQUE
- station_code -> varchar(255) NULL UNIQUE
- floor_location -> varchar(255) NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## rooms
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- station_id -> bigint unsigned NOT NULL (Foreign Key -> stations.id ON DELETE CASCADE)
- room_number -> varchar(255) NOT NULL UNIQUE
- room_type -> varchar(255) NOT NULL
- capacity -> int NOT NULL DEFAULT 1
- price_per_night -> decimal(10,2) NOT NULL DEFAULT 0.00
- status -> varchar(255) NOT NULL DEFAULT 'Active'
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## beds
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- room_id -> bigint unsigned NOT NULL (Foreign Key -> rooms.id ON DELETE CASCADE)
- bed_code -> varchar(255) NOT NULL UNIQUE
- status -> varchar(255) NOT NULL DEFAULT 'Available'
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## nurses
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- user_id -> bigint unsigned NOT NULL (Foreign Key -> users.id ON DELETE CASCADE)
- employee_id -> varchar(255) NOT NULL UNIQUE
- first_name -> varchar(255) NOT NULL
- last_name -> varchar(255) NOT NULL
- license_number -> varchar(255) NOT NULL
- designation -> varchar(255) NOT NULL DEFAULT 'Clinical'
- station_id -> bigint unsigned NULL (Foreign Key -> stations.id ON DELETE SET NULL)
- shift_start -> time NOT NULL
- shift_end -> time NOT NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## physicians
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- user_id -> bigint unsigned NOT NULL (Foreign Key -> users.id ON DELETE CASCADE)
- employee_id -> varchar(255) NOT NULL UNIQUE
- first_name -> varchar(255) NOT NULL
- last_name -> varchar(255) NOT NULL
- specialization -> varchar(255) NOT NULL
- employment_type -> varchar(255) NOT NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## general_services
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- user_id -> bigint unsigned NOT NULL (Foreign Key -> users.id ON DELETE CASCADE)
- employee_id -> varchar(255) NOT NULL UNIQUE
- first_name -> varchar(255) NOT NULL
- last_name -> varchar(255) NOT NULL
- assigned_area -> varchar(255) NOT NULL
- shift_start -> time NOT NULL
- shift_end -> time NOT NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## patients
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- patient_unique_id -> varchar(255) NOT NULL UNIQUE
- created_by_user_id -> bigint unsigned NOT NULL (Foreign Key -> users.id)
- first_name -> varchar(255) NOT NULL
- middle_name -> varchar(255) NULL
- last_name -> varchar(255) NOT NULL
- date_of_birth -> date NOT NULL
- sex -> varchar(255) NOT NULL
- civil_status -> varchar(255) NOT NULL
- nationality -> varchar(255) NOT NULL DEFAULT 'Filipino'
- religion -> varchar(255) NULL
- address_permanent -> text NOT NULL
- address_present -> text NULL
- contact_number -> varchar(255) NOT NULL
- email -> varchar(255) NULL
- emergency_contact_name -> varchar(255) NOT NULL
- emergency_contact_relationship -> varchar(255) NOT NULL
- emergency_contact_number -> varchar(255) NOT NULL
- philhealth_number -> varchar(255) NULL
- senior_citizen_id -> varchar(255) NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## admissions
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- patient_id -> bigint unsigned NOT NULL (Foreign Key -> patients.id ON DELETE CASCADE)
- admission_number -> varchar(255) NOT NULL UNIQUE
- station_id -> bigint unsigned NULL (Foreign Key -> stations.id)
- bed_id -> bigint unsigned NULL (Foreign Key -> beds.id)
- attending_physician_id -> bigint unsigned NOT NULL (Foreign Key -> physicians.id)
- admitting_clerk_id -> bigint unsigned NOT NULL (Foreign Key -> users.id)
- admission_date -> datetime NOT NULL
- discharge_date -> datetime NULL
- admission_type -> varchar(255) NOT NULL
- case_type -> varchar(255) NOT NULL
- status -> varchar(255) NOT NULL DEFAULT 'Admitted'
- chief_complaint -> text NOT NULL
- initial_diagnosis -> text NULL
- mode_of_arrival -> varchar(255) NOT NULL
- temp -> decimal(4,1) NULL
- bp_systolic -> int NULL
- bp_diastolic -> int NULL
- pulse_rate -> int NULL
- respiratory_rate -> int NULL
- o2_sat -> int NULL
- known_allergies -> json NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## admission_billing_infos
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- admission_id -> bigint unsigned NOT NULL (Foreign Key -> admissions.id ON DELETE CASCADE)
- payment_type -> varchar(255) NOT NULL
- primary_insurance_provider -> varchar(255) NULL
- policy_number -> varchar(255) NULL
- approval_code -> varchar(255) NULL
- guarantor_name -> varchar(255) NULL
- guarantor_relationship -> varchar(255) NULL
- guarantor_contact -> varchar(255) NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## patient_files
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- patient_id -> bigint unsigned NOT NULL (Foreign Key -> patients.id ON DELETE CASCADE)
- admission_id -> bigint unsigned NULL (Foreign Key -> admissions.id ON DELETE CASCADE)
- file_path -> varchar(255) NOT NULL
- file_name -> varchar(255) NOT NULL
- document_type -> varchar(255) NOT NULL
- uploaded_by_id -> bigint unsigned NOT NULL (Foreign Key -> users.id)
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## medicines
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- generic_name -> varchar(255) NOT NULL
- brand_name -> varchar(255) NULL
- dosage -> varchar(255) NOT NULL
- form -> varchar(255) NOT NULL
- stock_on_hand -> int NOT NULL DEFAULT 0
- critical_level -> int NOT NULL DEFAULT 20
- price -> decimal(10,2) NOT NULL DEFAULT 0.00
- expiry_date -> date NULL
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## inventory_items
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- item_name -> varchar(255) NOT NULL
- category -> varchar(255) NOT NULL
- price -> decimal(10,2) NOT NULL DEFAULT 0.00
- quantity -> int NOT NULL DEFAULT 0
- critical_level -> int NOT NULL DEFAULT 10
- created_at -> timestamp NULL
- updated_at -> timestamp NULL

## cache
- key -> varchar(255) NOT NULL (Primary Key)
- value -> mediumtext NOT NULL
- expiration -> int NOT NULL

## cache_locks
- key -> varchar(255) NOT NULL (Primary Key)
- owner -> varchar(255) NOT NULL
- expiration -> int NOT NULL

## sessions
- id -> varchar(255) NOT NULL (Primary Key)
- user_id -> bigint unsigned NULL
- ip_address -> varchar(45) NULL
- user_agent -> text NULL
- payload -> longtext NOT NULL
- last_activity -> int NOT NULL

## password_reset_tokens
- email -> varchar(255) NOT NULL (Primary Key)
- token -> varchar(255) NOT NULL
- created_at -> timestamp NULL

## failed_jobs
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- uuid -> varchar(255) NOT NULL UNIQUE
- connection -> text NOT NULL
- queue -> text NOT NULL
- payload -> longtext NOT NULL
- exception -> longtext NOT NULL
- failed_at -> timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP

## jobs
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- queue -> varchar(255) NOT NULL
- payload -> longtext NOT NULL
- attempts -> tinyint unsigned NOT NULL
- reserved_at -> int unsigned NULL
- available_at -> int unsigned NOT NULL
- created_at -> int unsigned NOT NULL

## job_batches
- id -> varchar(255) NOT NULL (Primary Key)
- name -> varchar(255) NOT NULL
- total_jobs -> int NOT NULL
- pending_jobs -> int NOT NULL
- failed_jobs -> int NOT NULL
- failed_job_ids -> longtext NOT NULL
- options -> mediumtext NULL
- cancelled_at -> int NULL
- created_at -> int NOT NULL
- finished_at -> int NULL

## migrations
- id -> int unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- migration -> varchar(255) NOT NULL
- batch -> int NOT NULL
