<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Setting;
use App\Models\Hall;
use Illuminate\Http\Request;

class StudentRegistrationController extends Controller
{
    /**
     * Display the registration form.
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'national_id' => 'required|digits:14',
        ]);

        $student = Student::where('national_id', $request->national_id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'هذا الرقم القومي غير موجود بالنظام.');
        }

        $whatsappLink = Setting::where('key', 'whatsapp_group_link')->first()->value ?? '#';

        // If student already has a place, show their result directly.
        if ($student->place) {
            $hall = Hall::where('name', $student->place)->first();
            $timing = $hall ? $hall->timing : 'سيتم الإعلان عنه قريباً';
            return view('result', [
                'student' => $student, 
                'timing' => $timing,
                'whatsapp_link' => $whatsappLink
            ]);
        }
        
        // --- Dynamic Hall Assignment ---
        $halls = Hall::all();
        $assignedHall = null;

        foreach ($halls as $hall) {
            $currentOccupancy = Student::where('place', $hall->name)->count();
            if ($currentOccupancy < $hall->capacity) {
                $student->place = $hall->name;
                $assignedHall = $hall;
                break; // Exit loop once a hall is found
            }
        }

        if (!$assignedHall) {
            return redirect()->back()->with('error', 'عفواً، جميع الأماكن ممتلئة حالياً.');
        }

        $student->save();
        $student->refresh();
        
        return view('result', [
            'student' => $student,
            'timing' => $assignedHall->timing,
            'whatsapp_link' => $whatsappLink
        ]);
    }
}

