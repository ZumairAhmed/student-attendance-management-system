# SAMS — Student Attendance Management System

A web-based **Student Attendance Management System** built with **Laravel 12** (PHP) and **Tailwind CSS**. SAMS lets administrators manage batches, subjects, students, lecturers, and timetables, while lecturers mark and review attendance for their assigned subjects.

## Overview

SAMS has two role-based portals, protected by dedicated middleware:

- **Admin** — full management of batches, subjects, students, lecturers, and timetables; bulk import via Excel; PDF attendance reports; alerts dashboard.
- **Lecturer** — views their timetable, marks attendance for their subjects, and reviews attendance history/sessions.

Authentication is handled via **Laravel Breeze**.

## Features

- **Role-based access control** (`admin` / `lecturer`) enforced via `AdminMiddleware` and `LecturerMiddleware`.
- **Batch management** — create and manage student batches/cohorts.
- **Subject management** — create subjects, assign lecturers, lock/unlock subjects (locking prevents further attendance edits), and bulk-import subjects from Excel (with a downloadable template).
- **Student management** — full CRUD plus bulk import from Excel (with a downloadable template).
- **Lecturer management** — full CRUD plus bulk import from Excel (with a downloadable template).
- **Timetable builder** — create timetables per batch/semester with day-by-day slots, including continuation slots and a printable timetable view.
- **Attendance marking** — lecturers record Present / Absent / Late for each student, once per subject per day; attendance for a locked subject is blocked.
- **Attendance history** — lecturers can view and edit past attendance sessions (with an edit-reason field for auditability).
- **Attendance percentage calculation** — per-student, per-subject attendance percentage.
- **Reports** — admin can generate **PDF attendance reports** per subject (via `barryvdh/laravel-dompdf`).
- **Alerts dashboard** for the admin (e.g. flagging low attendance or other conditions).
- **Excel import/export** support via `maatwebsite/excel` and `phpoffice/phpspreadsheet`.

## Tech Stack

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** Blade templates, Tailwind CSS 3, Alpine.js, Vite
- **Auth:** Laravel Breeze
- **PDF generation:** barryvdh/laravel-dompdf
- **Excel import/export:** maatwebsite/excel, phpoffice/phpspreadsheet
- **Database:** SQLite by default (see `.env.example`); easily switched to MySQL
- **Testing:** PHPUnit

## Project Structure

```
sams/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Batches, Subjects, Students, Lecturers, Reports, Timetables, Dashboard
│   │   │   ├── Lecturer/     # Dashboard, Timetable, Attendance
│   │   │   └── Auth/         # Laravel Breeze auth controllers
│   │   ├── Middleware/       # AdminMiddleware, LecturerMiddleware
│   │   └── Requests/
│   ├── Imports/              # Excel import classes (Students, Lecturers, Subjects)
│   ├── Models/                # Attendance, Batch, Session, Student, Subject, Timetable, TimetableSlot, User
│   └── View/Components/
├── database/
│   ├── migrations/
│   └── seeders/               # AdminSeeder creates a default admin account
├── resources/                   # Blade views, CSS, JS
├── routes/
│   └── web.php                    # Admin & Lecturer route groups
├── public/
├── composer.json
├── package.json
└── vite.config.js
```

## Data Model

- **Batch** — has many Subjects and Students.
- **Subject** — belongs to a Batch and a lecturer (`User`); has many Sessions; can be locked.
- **Student** — belongs to a Batch; has many Attendance records.
- **Session** — belongs to a Subject; represents one attendance-taking event (a date); has many Attendance records.
- **Attendance** — belongs to a Session and a Student; status is Present / Absent / Late.
- **Timetable** — belongs to a Batch; has many TimetableSlots.
- **TimetableSlot** — belongs to a Timetable and a Subject; represents a day/time slot (supports continuation slots).
- **User** — has a `role` (`admin` or `lecturer`); a lecturer `hasMany` Subjects.

## Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- SQLite (default) or MySQL/another Laravel-supported database

## Installation

```bash
git clone <repository-url>
cd sams

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database (SQLite is the default — create the file if needed)
touch database/database.sqlite

# Run migrations and seed the default admin account
php artisan migrate --seed

# Build frontend assets
npm run build
```

Alternatively, the project defines a single composer script that does most of this for you:

```bash
composer run setup
```

### Running the app in development

```bash
composer run dev
```

This starts the PHP dev server, the queue listener, log tailing (`pail`), and the Vite dev server together. Or run them individually:

```bash
php artisan serve
npm run dev
```

The app will be available at `http://localhost:8000` (or whichever port `artisan serve` reports).

## Default Admin Login

The `AdminSeeder` creates a default administrator account:

```
Email:    admin@sams.lk
Password: admin123
```

> Change this password immediately in any real deployment.

Lecturer accounts are created by the admin through the Lecturers management screen (or via the Excel import feature).

## Using MySQL instead of SQLite

Update `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sams
DB_USERNAME=root
DB_PASSWORD=
```

Then create the `sams` database in MySQL and run `php artisan migrate --seed`.

## Testing

```bash
composer run test
# or
php artisan test
```

## License

No license specified in the project (the base Laravel framework itself is MIT-licensed).
