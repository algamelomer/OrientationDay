<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Update the application settings.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'masrah_capacity' => 'required|integer|min:0',
            'hall_101_capacity' => 'required|integer|min:0',
            'hall_118_capacity' => 'required|integer|min:0',
            'whatsapp_group_link' => 'required|url',
        ]);

        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
