<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Session;
use App\Models\Attendance;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('batch')->latest()->get();
        $batches  = Batch::where('status', 'active')->get();
        return view('admin.students.index', compact('students', 'batches'));
    }

    public function create()
    {
        $batches = Batch::where('status', 'active')->get();
        return view('admin.students.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'index_no'  => 'required|string|unique:students,index_no',
            'batch_id'  => 'required|exists:batches,id',
            'email'     => 'nullable|email',
            'phone'     => 'nullable|string|max:15',
            'status'    => 'required|in:active,inactive',
        ]);

        Student::create($request->all());
        return redirect()->route('admin.students.index')
            ->with('success', 'Student added successfully!');
    }

    public function printPdf()
{
    $students = Student::with('batch')->orderBy('batch_id')->get();
    $batches  = Batch::where('status', 'active')->get();
    $pdf      = app('dompdf.wrapper');
    $pdf->loadView('admin.students.pdf', compact('students', 'batches'));
    $pdf->setPaper('a4', 'landscape');
    return $pdf->download('students_list_' . date('Y-m-d') . '.pdf');
}

    public function show(Student $student)
    {
        $subjects = Subject::where('batch_id', $student->batch_id)->get();
        $attendanceData = [];

        foreach ($subjects as $subject) {
            $totalSessions = Session::where('subject_id', $subject->id)->count();
            $present = Attendance::whereIn('session_id',
                Session::where('subject_id', $subject->id)->pluck('id'))
                ->where('student_id', $student->id)
                ->whereIn('status', ['Present', 'Late'])
                ->count();

            $percentage = $totalSessions > 0
                ? round(($present / $totalSessions) * 100, 1) : 100;

            $attendanceData[] = [
                'subject'    => $subject,
                'total'      => $totalSessions,
                'present'    => $present,
                'percentage' => $percentage,
                'flagged'    => $percentage < 80,
            ];
        }

        return view('admin.students.show', compact('student', 'attendanceData'));
    }

    public function edit(Student $student)
    {
        $batches = Batch::where('status', 'active')->get();
        return view('admin.students.edit', compact('student', 'batches'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'index_no'  => 'required|string|unique:students,index_no,' . $student->id,
            'batch_id'  => 'required|exists:batches,id',
            'email'     => 'nullable|email',
            'phone'     => 'nullable|string|max:15',
            'status'    => 'required|in:active,inactive',
        ]);

        $student->update($request->all());
        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully!');
    }

   public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:xlsx,xls,csv|max:5120'
    ]);

    try {
        $import = new StudentsImport();
        Excel::import($import, $request->file('csv_file'));

        $message = "{$import->imported} student(s) imported successfully!";
        if ($import->skipped > 0) $message .= " {$import->skipped} row(s) skipped.";
        if (!empty($import->importErrors)) {
            $message .= " Note: " . implode(', ', array_slice($import->importErrors, 0, 2));
        }

        return back()->with('success', $message);
    } catch (\Exception $e) {
        return back()->with('error', 'Import failed: ' . $e->getMessage());
    }
}

    public function downloadTemplate()
{
    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="students_template.csv"',
    ];
    $callback = function () {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['full_name', 'index_no', 'batch_id', 'email', 'phone']);
        fputcsv($file, ['Kasun Perera', 'KAN/IT/2324/F/0001', '3', 'kasun@gmail.com', '0771234567']);
        fputcsv($file, ['Nimali Silva', 'KAN/IT/2324/F/0002', '3', 'nimali@gmail.com', '0772345678']);
        fputcsv($file, ['Amal Fernando', 'KAN/IT/2425/F/0001', '4', 'amal@gmail.com', '0773456789']);
        fclose($file);
    };
    return response()->stream($callback, 200, $headers);
}
}