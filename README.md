# Chansey - Hospital Management System

A comprehensive **Hospital Management System** built with **Laravel 12** and **Filament** that streamlines clinical operations, patient management, and staff coordination for modern healthcare facilities.

## Project Overview

Chansey is designed to centralize and automate critical hospital operations, enabling healthcare providers to deliver efficient patient care while maintaining accurate medical records and optimizing resource allocation. The system integrates patient demographics, clinical admissions, billing, staff management, inventory tracking, and infrastructure management into a unified platform.

### Core Goals

- **Patient-Centric Care**: Maintain complete patient profiles with clinical history and emergency contact information
- **Clinical Operations**: Manage admissions, bed assignments, physician-patient relationships, and discharge processes
- **Staff Coordination**: Organize nurses, physicians, and support staff with scheduling and shift management
- **Billing & Insurance**: Handle payment processing, insurance verification, and billing documentation
- **Resource Management**: Track inventory items, hospital infrastructure (rooms, beds), and facility maintenance
- **Data Integrity**: Ensure secure, accurate medical records and audit trails for compliance

---

## Features

### 1. **Patient Management**
- Complete patient registration with demographic information
- Automatic age calculation from date of birth
- Emergency contact tracking
- Support for multiple identification formats (PhilHealth, Senior Citizen ID)
- Patient file and document management
- Comprehensive address tracking (permanent and present)

### 2. **Clinical Admissions**
- Multi-type admission support (Emergency, Outpatient, Inpatient, Transfer)
- Case type classification (New Case, Returning, Follow-up)
- Bed assignment and management
- Vital signs recording at admission (Temperature, Blood Pressure, Pulse Rate, Respiratory Rate, O₂ Saturation)
- Chief complaint and initial diagnosis documentation
- Mode of arrival tracking (Walk-in, Ambulance, Wheelchair, Stretcher)
- Admission status tracking (Admitted, Discharged, Transferred, Died)
- Known allergies logging in JSON format for flexibility

### 3. **Billing & Insurance Management**
- Multiple payment type support (Cash, Insurance, HMO, Company)
- Insurance provider and policy management
- Letter of Authorization (LOA) approval code tracking
- HMO coordination and verification
- Admission billing information linked to clinical records

### 4. **Staff Management & Organization**
- **Admins**: System administrators with full access
- **Nurses**: Clinical and Admitting nurses with:
  - License number tracking
  - Shift scheduling (start/end times)
  - Station assignment
  - Designation management
- **Physicians**: Medical staff with:
  - Specialization tracking
  - Employment type management
  - Patient assignment capabilities
- **General Services**: Support staff with:
  - Area assignment
  - Shift scheduling

### 5. **Hospital Infrastructure**
- **Room Management**:
  - Multiple room types (Private, Semi-Private, Ward, ICU, ER)
  - Capacity management
  - Room status tracking (Active, Maintenance, Closed)
- **Bed Management**:
  - Unique bed codes for easy identification
  - Bed status tracking (Available, Occupied, Cleaning, Maintenance)
  - Room-to-bed relationship management

### 6. **Inventory Management**
- Track inventory items and supplies
- Item quantity and status management
- Inventory-related operational support

### 7. **User Authentication & Authorization**
- Role-based access control (Admin, Nurse, Physician, General Services)
- User badge ID system for identification
- Secure password management
- Email-based authentication

### 8. **Admin Panel (Filament)**
- Intuitive admin dashboard built with Filament
- Resource management for:
  - Nurses
  - Physicians
  - General Services staff
- System maintenance tools
- Real-time status monitoring

---

## Technology Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Admin Panel**: Filament 4.0
- **Frontend**: Tailwind CSS 4, Alpine.js
- **Build Tool**: Vite 7
- **Database**: Relational database (PostgreSQL/MySQL)
- **Authentication**: Laravel Authentication with Filament integration

---

## Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- Database (MySQL or PostgreSQL)

### Quick Start

```bash
# Clone the repository
git clone <repository-url>
cd chansey

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build frontend assets
npm run build

# Start development server
npm run dev
```

---

## Project Structure

```
app/
├── Models/              # Eloquent models (Patient, User, Nurse, etc.)
├── Http/
│   ├── Controllers/     # Request handlers
│   └── Requests/        # Form validation
├── Filament/
│   └── Resources/       # Filament admin resources
└── Services/            # Business logic services

database/
├── migrations/          # Schema definitions
└── seeders/             # Initial data

resources/
├── views/               # Blade templates
├── css/                 # Tailwind styles
└── js/                  # Frontend scripts

routes/                  # API and web routes
tests/                   # Unit and feature tests
```

---

## License

MIT License - Open source and free to use.

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
