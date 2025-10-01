<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Setting;
use App\Models\Hall;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $stats = [
            'registered' => Student::count(),
            'assigned' => Student::whereNotNull('place')->count(),
            'unassigned' => Student::whereNull('place')->count(),
        ];

        $settings = Setting::pluck('value', 'key')->all();
        $students = Student::paginate(10);
        $halls = Hall::all();

        // Hall specific stats
        $hallStats = [];
        foreach ($halls as $hall) {
            $hallStats[$hall->name] = Student::where('place', $hall->name)->count();
        }

        return view('dashboard', compact('stats', 'settings', 'students', 'halls', 'hallStats'));
    }

    /**
     * Import students from an Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return redirect()->route('dashboard')->with('success', 'تم استيراد بيانات الطلاب بنجاح!');
    }

    /**
     * Update a student's assigned place.
     */
    public function updateStudent(Request $request, Student $student)
    {
        $request->validate([
            'place' => 'nullable|string',
        ]);

        $student->place = $request->place === 'none' ? null : $request->place;
        $student->save();

        return redirect()->route('dashboard')->with('success', 'تم تحديث مكان الطالب بنجاح.');
    }
    
    /**
     * Delete all student records.
     */
    public function dropStudents()
    {
        Student::truncate();
        return redirect()->route('dashboard')->with('success', 'تم حذف جميع بيانات الطلاب بنجاح.');
    }
}

