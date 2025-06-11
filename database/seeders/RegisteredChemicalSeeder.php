<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegisteredChemical;
use Illuminate\Support\Facades\Storage;

class RegisteredChemicalSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('chemicals.csv');
        if (!file_exists($path)) {
            $this->command->error("CSV file not found: $path");
            return;
        }

        $file = fopen($path, 'r');

        // $firstLine = fgets($file);
        // $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
        // rewind($file);

        // while (($data = fgetcsv($file)) !== false) {
        //     if (count($data) < 3) continue;

        //     RegisteredChemical::updateOrCreate(
        //         ['CAS_number' => $data[2]],
        //         [
        //             'empirical_formula' => $data[0],
        //             'chemical_name' => $data[1],
        //         ]
        //     );
        // }

        $firstline = true;
        while (($data = fgetcsv($file, 2000, ',')) !== false) {
            if (! $firstline) {
                RegisteredChemical::create(
                    [
                        'empirical_formula' => $data[0],
                        'chemical_name' => $data[1],
                        'CAS_number' => $data[2],
                    ]
                );
            }
            $firstline = false;
        }

        fclose($file);
        $this->command->info("Registered chemicals imported successfully.");
    }
}
