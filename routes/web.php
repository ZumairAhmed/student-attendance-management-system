<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Lecturer;

Route::get('/', function () {
    return redirect()->route('login');
});

// Profile Routes (both admin and lecturer)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/auth.php';

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/alerts', [Admin\DashboardController::class, 'alerts'])->name('alerts');
    Route::get('/students/pdf', [Admin\StudentController::class, 'printPdf'])->name('students.pdf');
    Route::get('/lecturers/pdf', [Admin\LecturerController::class, 'printPdf'])->name('lecturers.pdf');
    Route::get('/subjects/pdf', [Admin\SubjectController::class, 'printPdf'])->name('subjects.pdf');
    // Batches
    Route::resource('batches', Admin\BatchController::class);

    // Subjects — template & import BEFORE resource
    Route::get('/subjects/template', [Admin\SubjectController::class, 'downloadTemplate'])->name('subjects.template');
    Route::post('/subjects/import', [Admin\SubjectController::class, 'import'])->name('subjects.import');
    Route::post('/subjects/{subject}/lock', [Admin\SubjectController::class, 'lock'])->name('subjects.lock');
    Route::post('/subjects/{subject}/unlock', [Admin\SubjectController::class, 'unlock'])->name('subjects.unlock');
    Route::resource('subjects', Admin\SubjectController::class);

    // Students — template & import BEFORE resource
    Route::get('/students/template', [Admin\StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::post('/students/import', [Admin\StudentController::class, 'import'])->name('students.import');
    Route::resource('students', Admin\StudentController::class);

    // Lecturers — template & import BEFORE resource
    Route::get('/lecturers/template', [Admin\LecturerController::class, 'downloadTemplate'])->name('lecturers.template');
    Route::post('/lecturers/import', [Admin\LecturerController::class, 'import'])->name('lecturers.import');
    Route::resource('lecturers', Admin\LecturerController::class);

    // Reports
    Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [Admin\ReportController::class, 'generatePdf'])->name('reports.pdf');

    // Timetables
    Route::get('/timetables/{timetable}/print', [Admin\TimetableController::class, 'printView'])->name('timetables.print');
    Route::post('/timetables/{timetable}/slots', [Admin\TimetableController::class, 'addSlot'])->name('timetables.slots.add');
    Route::delete('/timetables/{timetable}/slots/{slot}', [Admin\TimetableController::class, 'deleteSlot'])->name('timetables.slots.delete');
    Route::resource('timetables', Admin\TimetableController::class);
});

// Lecturer Routes
Route::prefix('lecturer')->name('lecturer.')->middleware(['auth', 'lecturer'])->group(function () {
    Route::get('/dashboard', [Lecturer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/timetable', [Lecturer\TimetableController::class, 'index'])->name('timetable');
    Route::get('/attendance/{subject}', [Lecturer\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/{subject}', [Lecturer\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{subject}/history', [Lecturer\AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('/attendance/{subject}/session/{session}', [Lecturer\AttendanceController::class, 'show'])->name('attendance.show');
    Route::put('/attendance/{subject}/session/{session}', [Lecturer\AttendanceController::class, 'update'])->name('attendance.update');
    Route::get('/notifications', [Lecturer\DashboardController::class, 'notifications'])->name('notifications');
});