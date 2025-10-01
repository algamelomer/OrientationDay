<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Student;

class StudentRegistrationTest extends TestCase
{
    // This trait will reset the database after each test, so tests don't interfere with each other.
    use RefreshDatabase;

    /** @test */
    public function the_main_page_loads_correctly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ادخل رقمك القومي');
    }

    /** @test */
    public function registration_fails_for_a_non_existent_national_id()
    {
        $response = $this->post('/register', ['national_id' => '12345678901234']);

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'هذا الرقم القومي غير موجود بالنظام.');
    }

    /** @test */
    public function it_shows_data_for_a_student_who_is_already_assigned_a_place()
    {
        // Create a student who already has a place
        $student = Student::factory()->create(['place' => 'مسرح']);

        $response = $this->post('/register', ['national_id' => $student->national_id]);

        $response->assertStatus(200);
        $response->assertViewIs('result');
        $response->assertSee($student->name);
        $response->assertSee('مسرح');
    }

    /** @test */
    public function it_assigns_a_student_to_masrah_when_it_is_not_full()
    {
        // Create a student with no place assigned yet
        $student = Student::factory()->create(['place' => null]);

        $response = $this->post('/register', ['national_id' => $student->national_id]);

        $response->assertStatus(200);
        $response->assertViewIs('result');
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'place' => 'مسرح'
        ]);
    }

    /** @test */
    public function it_assigns_a_student_to_hall_101_when_masrah_is_full()
    {
        // 1. Fill up the first location
        Student::factory()->count(250)->create(['place' => 'مسرح']);

        // 2. Create the new student to be registered
        $newStudent = Student::factory()->create(['place' => null]);

        // 3. Attempt to register the new student
        $this->post('/register', ['national_id' => $newStudent->national_id]);
        
        // 4. Assert that the student was assigned to the second location
        $this->assertDatabaseHas('students', [
            'id' => $newStudent->id,
            'place' => 'hall_101'
        ]);
    }

    /** @test */
    public function it_assigns_a_student_to_hall_118_when_masrah_and_hall_101_are_full()
    {
        // 1. Fill up the first two locations
        Student::factory()->count(250)->create(['place' => 'مسرح']);
        Student::factory()->count(300)->create(['place' => 'hall_101']);

        // 2. Create the new student to be registered
        $newStudent = Student::factory()->create(['place' => null]);

        // 3. Attempt to register the new student
        $this->post('/register', ['national_id' => $newStudent->national_id]);

        // 4. Assert that the student was assigned to the third location
        $this->assertDatabaseHas('students', [
            'id' => $newStudent->id,
            'place' => 'hall_118'
        ]);
    }

    /** @test */
    public function it_returns_an_error_when_all_places_are_full()
    {
        // 1. Fill up all available locations
        Student::factory()->count(250)->create(['place' => 'مسرح']);
        Student::factory()->count(300)->create(['place' => 'hall_101']);
        Student::factory()->count(300)->create(['place' => 'hall_118']);

        // 2. Create a new student who will have no place to go
        $lateStudent = Student::factory()->create(['place' => null]);
        
        // 3. Attempt to register them
        $response = $this->post('/register', ['national_id' => $lateStudent->national_id]);

        // 4. Assert they are redirected with an error message
        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'عفواً، جميع الأماكن ممتلئة حالياً.');
    }
}
