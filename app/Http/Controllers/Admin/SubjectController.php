<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Batch;
use App\Models\User;
use App\Imports\SubjectsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['batch', 'lecturer'])->latest()->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $batches   = Batch::where('status', 'active')->get();
        $lecturers = User::where('role', 'lecturer')->where('status', 'active')->get();
        return view('admin.subjects.create', compact('batches', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'code'     => 'required|string|unique:subjects,code',
            'batch_id' => 'required|exists:batches,id',
            'user_id'  => 'nullable|exists:users,id',
            'semester' => 'required|integer|min:1|max:4',
        ]);

        Subject::create($request->all());
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    public function edit(Subject $subject)
    {
        $batches   = Batch::where('status', 'active')->get();
        $lecturers = User::where('role', 'lecturer')->where('status', 'active')->get();
        return view('admin.subjects.edit', compact('subject', 'batches', 'lecturers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'code'     => 'required|string|unique:subjects,code,' . $subject->id,
            'batch_id' => 'required|exists:batches,id',
            'user_id'  => 'nullable|exists:users,id',
            'semester' => 'required|integer|min:1|max:4',
        ]);

        $subject->update($request->all());
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }

    public function show(Subject $subject)
    {
        return redirect()->route('admin.subjects.index');
    }

    public function lock(Subject $subject)
    {
        $subject->update(['is_locked' => true]);
        return back()->with('success', 'Subject locked!');
    }

    public function unlock(Subject $subject)
    {
        $subject->update(['is_locked' => false]);
        return back()->with('success', 'Subject unlocked!');
    }

public function printPdf()
{
    $subjects = Subject::with(['batch', 'lecturer'])->orderBy('batch_id')->get();
    $pdf      = app('dompdf.wrapper');
    $pdf->loadView('admin.subjects.pdf', compact('subjects'));
    $pdf->setPaper('a4', 'landscape');
    return $pdf->download('subjects_list_' . date('Y-m-d') . '.pdf');
}

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $import = new SubjectsImport();
            Excel::import($import, $request->file('csv_file'));

            $message = "{$import->imported} subject(s) imported successfully!";
            if ($import->skipped > 0) $message .= " {$import->skipped} skipped.";

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
{
    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="subjects_template.csv"',
    ];
    $callback = function () {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['name', 'code', 'batch_id', 'semester', 'lecturer_email']);
        fputcsv($file, ['Web Technology', 'HNDIT4012', '3', '1', 'silva@sliate.lk']);
        fputcsv($file, ['OOP', 'HNDIT4022', '3', '1', 'perera@sliate.lk']);
        fclose($file);
    };
    return response()->stream($callback, 200, $headers);
}
}