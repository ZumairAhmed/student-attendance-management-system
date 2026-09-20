<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Imports\LecturersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = User::where('role', 'lecturer')->latest()->get();
        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function create()
    {
        return view('admin.lecturers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:15',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'lecturer',
            'status'   => 'active',
        ]);

        return redirect()->route('admin.lecturers.index')
            ->with('success', 'Lecturer added successfully!');
    }

    public function printPdf()
{
    $lecturers = User::where('role', 'lecturer')
        ->with('subjects.batch')
        ->latest()->get();
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('admin.lecturers.pdf', compact('lecturers'));
    $pdf->setPaper('a4', 'portrait');
    return $pdf->download('lecturers_list_' . date('Y-m-d') . '.pdf');
}

    public function edit(User $lecturer)
    {
        return view('admin.lecturers.edit', compact('lecturer'));
    }

    public function update(Request $request, User $lecturer)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $lecturer->id,
            'phone'  => 'nullable|string|max:15',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only('name', 'email', 'phone', 'status');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $lecturer->update($data);
        return redirect()->route('admin.lecturers.index')
            ->with('success', 'Lecturer updated successfully!');
    }

    public function destroy(User $lecturer)
    {
        $lecturer->delete();
        return redirect()->route('admin.lecturers.index')
            ->with('success', 'Lecturer deleted successfully!');
    }

    public function show(User $lecturer)
    {
        return redirect()->route('admin.lecturers.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $import = new LecturersImport();
            Excel::import($import, $request->file('csv_file'));

            $message = "{$import->imported} lecturer(s) imported successfully!";
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
        'Content-Disposition' => 'attachment; filename="lecturers_template.csv"',
    ];
    $callback = function () {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['name', 'email', 'phone', 'password']);
        fputcsv($file, ['Mr. Silva', 'silva@sliate.lk', '0771234567', 'lecturer123']);
        fputcsv($file, ['Ms. Perera', 'perera@sliate.lk', '0772345678', 'lecturer123']);
        fclose($file);
    };
    return response()->stream($callback, 200, $headers);
}
}