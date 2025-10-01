<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class StudentsImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // The WithHeadingRow trait automatically converts "National ID" to "national_id"
        // and "Name" to "name" based on the Excel file's header row.
        if (empty($row['national_id']) || empty($row['name'])) {
            return null; // Skip any rows that are missing essential data.
        }

        return new Student([
           'name'        => $row['name'],
           'national_id' => (string) $row['national_id'], // Ensure the ID is treated as a string
        ]);
    }

    /**
     * This method is used by the WithUpserts concern. It tells the importer 
     * to use the 'national_id' column to find existing students and update them,
     * or create them if they don't exist.
     */
    public function uniqueBy()
    {
        return 'national_id';
    }
}
