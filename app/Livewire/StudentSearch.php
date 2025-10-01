<?php

namespace App\Livewire;

use App\Models\Hall;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class StudentSearch extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $students = Student::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('national_id', 'like', '%'.$this->search.'%')
            ->paginate(10);

        $halls = Hall::all();

        return view('livewire.student-search', [
            'students' => $students,
            'halls' => $halls,
        ]);
    }

    public function updatePlace($studentId, $place)
    {
        $student = Student::find($studentId);
        if ($student) {
            $student->place = $place === 'none' ? null : $place;
            $student->save();
            session()->flash('success', 'Student place updated successfully.');
        }
    }
}
