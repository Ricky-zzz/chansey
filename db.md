# Chansey current Database Structure

## users
- id -> bigint unsigned NOT NULL AUTO_INCREMENT (Primary Key)
- name -> varchar(255) NOT NULL
- badge_id -> varchar(255) NOT NULL UNIQUE
- email -> varchar(255) NOT NULL UNIQUE
- email_verified_at -> timestamp NULL
- password -> varchar(255) NOT NULL
- user_type -> string NOT NULL DEFAULT 'nurse'  roles are ('admin','nurse','physician','general_service',physiscians)
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


## Proffesors gptied modules thats almost a whole fucking hospitals system (Core Modules of a Health Informatics System)

## 1. Patient Registration & Master Patient Index (MPI)
- Patient demographics (done)
- Unique patient ID generation (done)
- Visit/admission history (we have an admissions list per patientts)
- Insurance / PhilHealth information (done i guess we fill this up in admission)
- Patient consent records (in file uploads i guess this means done)

## Foundation module for all clinical workflows

## 2. Electronic Health Records (EHR) we will try to do lite ehr this part is a collaboration of clinical nurses, physicians and pharamacist probably

- Medical history (i got no idea how detailed this supposed to be but the plan is like every admissions will have its own clinical logs i guess thats that i hope so)

- Diagnoses (ICD-10) ( so in admission its more of a probable diagnosis but the real diagnosis i guess is in the physician side ICD-10 is simply a format lets justt assume the doctor remmebers it maybe i input fields diagnosis he inputs the icd code lets just add a diagnosis notes, bttw still contemplating if this should be a different table or should i merge it with the clinical nurses logs )

## Table clinical_notes
 - id integer [primary key, increment]
 - case_id integer [ref: > cases.id]
 - admission_id integer [ref: > admissions.id]
 - user_id integer [ref: > users.id] // Created by User (Could be Nurse or Physician)
 // added the vital coluumns since this is the clinical_notes that act as a log i also dont know how i should connect this with doctor order 
 - temp -> decimal(4,1) NULL
- bp_systolic -> int NULL
- bp_diastolic -> int NULL
- pulse_rate -> int NULL
- respiratory_rate -> int NULL
- o2_sat -> int NULL
 - diagnosis string perhabs nulable show in the notes only it ist diagnosist in the type
 - note_type string // can be('Progress Note', 'Doctor Order', 'Triage Note', or diagnosis) 
 - note_content text

 - created_at timestamp



- Progress notes (SOAP) (we got S in the admission, O as well in initail vitals , A is the real siagnosis by the physician and P for treatment self care plan also physician via doctors orders)

- Allergies & alerts (done in admission)

- Clinical summaries ( apparently a summary of patients info perhaps in the clinical nurse when they view a patient we add a view clinical summary that kinda summarizes alL the data about that patient??)

## Central clinical data repository

## 3. Admission, Discharge, and Transfer (ADT)

- Inpatient admission (this what we did in begining)

- Bed and ward assignment ( got this)

- Discharge planning ( my plan for this was in the physician like he has a button that updates patient status to ready for discharge or maybe we make a prepare for discharge doctors order but basically it updates the admission status which alerts the patients family to settle billing for discharge)

- Patient movement tracking (i plan to make a patient_movement table)

## patient_movement table 
- id integer [primary key, increment]
- admission_id form admission table // bttw should i add a patient id but i guess admission already store it via join huh
- room_id connected to the room table
- room price 
- timestamp
- (so basically the idea is when i admit an inpatient i add a log here when i succesfully transfer aptient  i log here as well so when its time for billing i simply compute the room fees here compute the nights spent for each room based on timestamp and latest room up until discharge date btw te discharge date cpouns as 1 night still probably)

## Critical for hospitals and inpatient facilities

## 4. Clinical Documentation & Nursing Informatics (main module and not gonna lie im supposed to do coding not plan everything the prof such a scam )

- Nursing care plans (looked this up dont know how to digitalized this cause this is literally a care plan maybe i can add a care plan table and nurses can create and edit the existing one still deciding f one patient should have 1 editable or many care plans)

## care plan table (still unsure but i research it its like columsn of text)
- id integer [primary key, increment]
- admission_id form admission table
- assesment text
- analysis text
- planning text
- interventions text
- rationale text
- evaluation text
-( so the idea is the initail nurse creates this which cann serve as reference to the other nurses who wil take shift kinda dont know what goes in thre but i just saw the format in the google)

- Vital signs monitoring (should be handled via the clinical notes table btw in the clinical nurses patient show we kinda need to show all logs from lattest so it acts as monitoring)

- Medication administration records (MAR) (not gonna lie im confused as hel how to do this do i make a medication log table as well)
(then theres also the ux and ui that im stressing on cause like the physicain makes an order for medication when i a nurse goes to a patient show it should contain also all the physician order like do i do this like a checklist so the flow i think of is like this we of course make a clinical log cause we kinda neet to monitor before medication then we check it and i guess that should like make an enttry ib mAR table or do i need to make it more ux firndly by )

## Table clinical_notes
 - id integer [primary key, increment]
 - case_id integer [ref: > cases.id]
 - admission_id integer [ref: > admissions.id]
 - user_id integer [ref: > users.id] // Created by User (Could be Nurse or Physician)
 // added the vital coluumns since this is the clinical_notes that act as a log i also dont know how i should connect this with doctor order 
 - temp -> decimal(4,1) NULL
- bp_systolic -> int NULL
- bp_diastolic -> int NULL
- pulse_rate -> int NULL
- respiratory_rate -> int NULL
- o2_sat -> int NULL
 - diagnosis string perhabs nulable show in the notes only it ist diagnosist in the type
 - note_type string // can be('Progress Note', 'Doctor Order', 'Triage Note', or diagnosis) 
 - nurse_action string // adding something like this that is nullable and only required in the nursing side so when the action is medication it automatically checks the peding medication ??? what do you think
 - note_evaluation text this is where they input the monitored effects or comment it 

 - created_at timestamp

- Shift notes and endorsements (researched this this involves Sit down reports i dont think making atbale for this is necesarry i beleive the best action for this is to simply design the clinical nurse dashboard to act like one so when they log in they can get the idea already like we put in the dashboard the patients in that staion pending doctors order tasks and etc cause i cant really digitalizes sit in reports really well cause there aremany formats and mostly are handwrittent plus its mostly the walking rounds that matter cant realy add a walking round button XD)

## 📌 Very important for nursing informatics systems

## 5. Physician Order Entry (CPOE) yup i need this shit cause this is kinda the thing all the nurses act by

## physician order table
 - id integer [primary key, increment]
 - physician id (who made it)
 - admission id (which specific admission of a patent is this order to be acted upon)
 - order_type string (can be a medication, transfer, monitoring, utility like change pillows or some shet, laboratory, dont know how too specify what laboratory is needed)
 - order_instance (is it once or recurring )
 - order_frequency (1,2,3,4) hours i dont know if we should implement minutes 
 - consumable if the order was medication or util what medicine should be given this is probably a drop down connected to the pahrmacy module or if its a utility like change pillow i connectt tthe dropdonw to the inventory in gen service if its a laboratory i should just make a dropdown of different labs in hospital
 - grams (id ont knw if this should be a seperate field )
 - order_validity like up if the order was recuring up until when is the order to be executed lets just add a date time here
 - comments text something the physician comments in  maybe ill specify the labo request type here
 - status (pending when doctor makes it becomes done when the nurses checks it)
  

- Laboratory requests
- Radiology requests
- (asked prof we can cheat this and the radiology no need to make seperate log ins justt add in the clinical nurse sidebar a laboratory module)

## lab_result table
 - id integer [primary key, increment]
 - admission id from admissions
 - lab_type string maybe a select if its radiology urninary and etc 
 - lab_finding string a select of normal abnormal etc
 - lab_file (an upload of file regarding that lab can be a pic cause in real hospital labs are kinda automatic attached to patients but cant really replicate each laboratory result format so we improvise by making the nurse upload a pic or pdf something)


- Medication orders
( yeah im confuesd a bit on this cause when a doctor makes an order apparently in the pharmacy module the pharmacist prepares the medicines like they already seperate a certain amount form the real inventory
like i want these to be automatic when the doctor make a medication order we add just make the medication order already and it should appear in the medications order module of the pharmacist ill probably make another filament for pharmacist which handles crud for medicines and cruf for medications )

# medication table
 - id integer [primary key, increment]
 - admission id which patients admission is this alloted to
 - medicine
 - amount like if we have 12 capsules prepared
 - status i dont know if this is needed like what if the patient was discharged early and he only condumed 9 of 12 capsules prepared 
 (i should probably make an action button to add amount or return to inventory if te patient was discharged early)

- Treatment plans
(looked it up its like a nursing care plan but made by doctors can really digitalized a plan so i looked up its format)

## treatment plan table 
- id
- phsyciian id
- admission id
- main_problem string or varchar it can be filled with diagnosis or the injury of patient
- goals text i looked it up its in bulet form so maybe i can also do a json like i did with the allergies
- interventions same as goals bulleted this is mostly where the orders are based cause the put a bullet of moonitoring medication here
- expected evaluation -  text containing the physicians epxpected evaluations 
- evaluation text as well the real evaluation
- status string when made its ongoing when the admission is done it becomes  completed and if the doctor chnaged it revised lets avoid using enums 

## 6. Laboratory Information System (LIS) too complicated we cheet this
## 7. Radiology Information System (RIS) too complicated we cheet this



## 8. Pharmacy Management System
- medicine inventory pretty sure i already made one just did not use it yet

-ill probbaly add a pharmacist table that extends users so they can log in yah i wish i could just cram it in the admin but noo the prof said we need to make a pahramcy table yeah gooodluck with the nursing teacher who has to juggle different roles in order to train the nursing students

- Medication inventory ( the medication table)



## 9. Appointment & Scheduling System rahh why do i need to make this we already have admissions damn prof he said its simple but its focking not 

- do i make a seperate form outside te system where the patient puts their email and make an appointment request
- who should see and make and aprove the appoitment the admission? or the physician themselves apparently physicians have secretary that handle their account so i gues the physician module will have an requested_appoitments table where they schedule the request
- this should be separate from the appointment table where th real appointments are

(truthfully im fucking anmoyed with all themodules how the hell is this simpe for 5k pesos)
- anyway ill do this on the later parts cause this is really more of a helper cause when an outpatient comes in he still gies through admission
- i shoulld probably make rooms nullable in the admission huh or maybe even station so in the admission table the outpatients show waiting area if rooms are null  even if i make a clinic for each physician cant really magically update which patient is in the clinic
should i make a queue management but i think i should just make it so thet the appointments table in the physician module only show appointments for the current day should be enough

any thing more tecnical and detailed is above my paygrade and frankly not worth the effort

## 10. Billing, Claims & Financial Management (for f sake i dont wanna make another accountant user but i guess i will be forced to cause i cant just magically add a paid button to the admin )

- so the accountant see a table of admission wit status ready for discharge
- they click a button gotot billing
- Professional fees ( when creating a physician should i add a column for charge or maybe just input it here cause i think the fees vary depending on how many days and what the doctor did s ill just input it)

-  Hospital charges 
  - includes med and utils as well as room well i already have a table to track room occupancy 
  - should probably make a consumation table

  # consuumation table
  - id
  - admission id
  - tyep string can be medicine, utility(pillows n shet), laboratory
  - item string can be the medicine name or the item name like pillow if lab then just put what lab was it radiology uriniary etc
  - price yup lets input it here already so no need to fetch i dont have a table handling labs so ill rpbbaly just make an array or fprices or just default to 1k peso or somethngg no need to grt too realistic its just  simulation and only 5k pesos rahhh

  - so the idea is when a patient checks an order like a medication util order or lab order they input the necessary details here



PhilHealth & HMO claims - got no idea lets just add an input on how much tthe philhealth or hmo will dedcut from the fees

OR / cash billing - yah got no idea but the the billing paty should probbaly look like a simple pos where i input how much tthe payer pays the there a change then it adds an entry to a billing history table btw i should also make a print pdf reciept to simulate printing

billing history table
- id 
- admission id
- total
- payment amount
- change
- dontknow how im gonna make a detailed billing but yeah well work it out

Statement of accounts yup this is the billing history

📌 Important for hospital sustainability

## 11. Public Health & Epidemiology Reporting ( zero idea on this but its all reports on the admin side)

Disease surveillance` - ill probably print a report on the all the diagnosis of theyear 

DOH report generation - ill lokk a format

Immunization tracking - no need

Maternal & child health records - got no idea too much trouble to add a this is a whole new module for reistering newly born childs with names and shet 

so all in all this is my plan for all the modules pls help me fine tune this

and lets finalize the whole db structure so i only need to worry about coding and ux

as much as i want to turn this down this is a capstone project so ill just focus on making it a learning experience without stressing myself too much and overengineering shits not worth the pay

pls help me recheck the plan

- give me finalized db structure
- a flow of todos like we need to make the physician and paramcist first before the clinical nursing 
- them move on to billing 
after pay the status becomes good for discharge btw who discharges the admission nurse if so i guess ill just add a discharge module which shows all admission ready with status ready for discharge (means billing) good for discharge and in the main admission tablle i wont include admissions those 2 to simplify 

help me check iif i missed anything im a bit busy since i got driving school hopefully ic an finish part by part until the dedline which is janueary 10 i think 

see all these modules and quick deadline only for 5k yup such a scam but got to do it