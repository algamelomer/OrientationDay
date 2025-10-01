<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    /**
     * Store a newly created hall in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'timing' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        Hall::create($request->all());

        return redirect()->route('dashboard')->with('success', 'تم إضافة القاعة بنجاح.');
    }

    /**
     * Update the specified hall in storage.
     */
    public function update(Request $request, Hall $hall)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'timing' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $hall->update($request->all());

        return redirect()->route('dashboard')->with('success', 'تم تحديث بيانات القاعة بنجاح.');
    }

    /**
     * Remove the specified hall from storage.
     */
    public function destroy(Hall $hall)
    {
        // To be safe, we should check if any students are assigned to this hall first.
        // For now, we'll just delete it.
        $hall->delete();

        return redirect()->route('dashboard')->with('success', 'تم حذف القاعة بنجاح.');
    }
}
