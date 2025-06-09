<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ChemicalProperty extends Model
{
    protected $fillable = [
        'chemical_id', 'color', 'physical_state',
        'melting_point', 'boiling_point',
        'flammability', 'other_details',
    ];

    public function chemical(){
        return $this->belongsTo(Chemical::class);
    }

    // protected static function booted(){
    //     static::saved(function ($chemicalProperty) {
    //         self::exportToJson();
    //     });

    //     static::deleted(function ($chemicalProperty) {
    //         self::exportToJson();
    //     });
    // }

    // public static function exportToJson(){
    //     $data = self::with(['chemical'])
    //                 ->get()
    //                 ->toArray();

    //     Storage::disk('local')->put('exports/chemicalProperties.json', json_encode($data, JSON_PRETTY_PRINT));
    // }
    protected static function booted(){
        static::saved(function ($chemicalProperty) {
            self::exportToCsv();
        });

        static::deleted(function ($chemicalProperty) {
            self::exportToCsv();
        });
    }

    public static function exportToCsv(){
        $data = self::with(['chemical'])->get();

        // Prepare CSV headers
        $headers = [
            'id', 'chemical_id', 'color', 'physical_state', 'melting_point', 'boiling_point',
            'flammability', 'other_details', 'created_at', 'updated_at',
            // Chemical related fields to include:
            'chemical_name', 'CAS_number', 'empirical_formula', 'molecular_weight', 'ec_number',
        ];

        $csvFilePath = 'exports/chemical_properties.csv';

        // Open a memory "file" for read/write
        $handle = fopen('php://memory', 'r+');

        // Add headers
        fputcsv($handle, $headers);

        foreach ($data as $item) {
            fputcsv($handle, [
                (string) $item->id,
                (string) $item->chemical_id,
                (string) ($item->color ?? ''),
                (string) ($item->physical_state ?? ''),
                (string) ($item->melting_point ?? ''),
                (string) ($item->boiling_point ?? ''),
                (string) ($item->flammability ?? ''),
                (string) ($item->other_details ?? ''),
                (string) ($item->created_at ?? ''),
                (string) ($item->updated_at ?? ''),
                (string) ($item->chemical->chemical_name ?? ''),
                (string) ($item->chemical->CAS_number ?? ''),
                (string) ($item->chemical->empirical_formula ?? ''),
                (string) ($item->chemical->molecular_weight ?? ''),
                (string) ($item->chemical->ec_number ?? ''),
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        \Log::info("CSV Content:\n" . $csvContent);

        // Store the CSV file (adjust path and disk as needed)
        Storage::disk('public')->put($csvFilePath, $csvContent);

        // dd($csvUrl);

        return $csvContent;

    }

    // public static function exportToCsv(){
    //     $data = self::with(['chemical'])->get();

    //     // Prepare CSV headers
    //     $headers = [
    //         'id', 'chemical_id', 'color', 'physical_state', 'melting_point', 'boiling_point',
    //         'flammability', 'other_details', 'created_at', 'updated_at',
    //         // Chemical related fields to include:
    //         'chemical_name', 'CAS_number', 'empirical_formula', 'molecular_weight', 'ec_number',
    //     ];

    //     $csvFilePath = 'exports/chemical_properties.csv';
    //     //
    //     // // Make sure directory exists
    //     // if (!File::exists(public_path('exports'))) {
    //     //     File::makeDirectory(public_path('exports'), 0755, true);
    //     // }

    //     // Open a memory "file" for read/write
    //     $handle = fopen('php://memory', 'r+');

    //     // Add headers
    //     fputcsv($handle, $headers);

    //     foreach ($data as $item) {
    //         fputcsv($handle, [
    //             (string) $item->id,
    //             (string) $item->chemical_id,
    //             (string) ($item->color ?? ''),
    //             (string) ($item->physical_state ?? ''),
    //             (string) ($item->melting_point ?? ''),
    //             (string) ($item->boiling_point ?? ''),
    //             (string) ($item->flammability ?? ''),
    //             (string) ($item->other_details ?? ''),
    //             (string) ($item->created_at ?? ''),
    //             (string) ($item->updated_at ?? ''),
    //             (string) ($item->chemical->chemical_name ?? ''),
    //             (string) ($item->chemical->CAS_number ?? ''),
    //             (string) ($item->chemical->empirical_formula ?? ''),
    //             (string) ($item->chemical->molecular_weight ?? ''),
    //             (string) ($item->chemical->ec_number ?? ''),
    //         ]);
    //     }

    //     rewind($handle);
    //     $csvContent = stream_get_contents($handle);
    //     fclose($handle);

    //     \Log::info("CSV Content:\n" . $csvContent);

    //     // Store the CSV file (adjust path and disk as needed)
    //     Storage::disk('public')->put($csvFilePath, $csvContent);

    //     // $csvUrl = public_path('storage/exports/chemical_properties.csv');

    //     $localPath = storage_path('app/public/exports/chemical_properties.csv');
    //     // dd($csvUrl);

    //     // $response = Http::attach(
    //     //     'files', file_get_contents($localPath), 'chemical_properties.csv'
    //     // )->withHeaders([
    //     //     'Authorization' => 'Bearer Mk-X8puuZ_i9nqpPlqQb91IfLI9so3tgtYW62ma_3R8',
    //     // ])->post("http://localhost:3000/api/v1/document-store/upsert/abaf0e12-e1d4-410f-bef4-0073e637b951", [
    //     //     'docId' => 'ff495517-8f11-44e4-9568-f1307c37bcd9', // optional
    //     //     'metadata' => [
    //     //         'source' => 'chemical_properties'
    //     //     ]
    //     // ]);

    //     // $response = Http::withHeaders([
    //     //     'Authorization' => 'Bearer Mk-X8puuZ_i9nqpPlqQb91IfLI9so3tgtYW62ma_3R8',
    //     //     'Content-Type' => 'application/json',
    //     // ])->post('http://localhost:3000/api/v1/document-store/upsert/abaf0e12-e1d4-410f-bef4-0073e637b951', [
    //     //     "docId" => "ae6e91d9-3576-4337-8f88-bf2ab1ff1ba4",
    //     //     // "metadata" => [
    //     //     //     "source" => "chemical_properties"
    //     //     // ],
    //     //     // "replaceExisting" => true,
    //     //     // "createNewDocStore" => false,
    //     //     "docStore" => [
    //     //         "name" => "plainText",
    //     //         "description" => "Chemical Properties CSV"
    //     //     ],
    //     //     "loader" => [
    //     //         "name" => "csvFile",
    //     //         "config" => [
    //     //         "url" => $csvUrl,
    //     //         // "filePath" => "C:\\Users\\USER\\.flowise\\storage\\your_folder\\chemical_properties.csv"
    //     //         ]
    //     //     ],
    //     //     // "splitter" => [
    //     //     //     "name" => "recursiveCharacterTextSplitter",
    //     //     //     "config" => []
    //     //     // ],
    //     //     "embedding" => [
    //     //         "name" => "ollamaEmbeddings",
    //     //         "config" => [
    //     //             'model' => 'nomic-embed-text',
    //     //             'baseUrl' => 'http://localhost:11434'
    //     //         ]
    //     //     ],
    //     //     "vectorStore" => [
    //     //         "name" => "pinecone",
    //     //         "config" => [
    //     //             'index' => 'flowise-768',
    //     //             'namespace' => 'chimietrace'
    //     //         ]
    //     //     ],
    //     //     "recordManager" => [
    //     //         "name" => "sqliteRecordManager",
    //     //         "config" => [
    //     //             'namespace' => 'chimietrace',
    //     //             'sourceIdKey' => 'source',
    //     //             'cleanup' => 'full'
    //     //         ]
    //     //     ]
    //     // ]);

    //     // if ($response->failed()) {
    //     //     \Log::error('Flowise Upsert Failed: ' . $response->body());
    //     // } else {
    //     //     \Log::info('Flowise Upsert Successful.');
    //     // }
    // }

    // public static function exportToCsv(){
    //     $data = self::with(['chemical'])->get();

    //     $headers = [
    //         'id', 'chemical_id', 'color', 'physical_state', 'melting_point', 'boiling_point',
    //         'flammability', 'other_details', 'created_at', 'updated_at',
    //         'chemical_name', 'CAS_number', 'empirical_formula', 'molecular_weight', 'ec_number',
    //     ];

    //     $handle = fopen('php://memory', 'r+');
    //     fputcsv($handle, $headers);

    //     foreach ($data as $item) {
    //         fputcsv($handle, [
    //             $item->id,
    //             $item->chemical_id,
    //             $item->color,
    //             $item->physical_state,
    //             $item->melting_point,
    //             $item->boiling_point,
    //             $item->flammability,
    //             $item->other_details,
    //             $item->created_at,
    //             $item->updated_at,
    //             $item->chemical->chemical_name ?? '',
    //             $item->chemical->CAS_number ?? '',
    //             $item->chemical->empirical_formula ?? '',
    //             $item->chemical->molecular_weight ?? '',
    //             $item->chemical->ec_number ?? '',
    //         ]);
    //     }

    //     rewind($handle);
    //     $csvContent = stream_get_contents($handle);
    //     fclose($handle);

    //     // Save file locally
    //     $filename = 'chemical_properties.csv';
    //     $filePath = storage_path("app/public/exports/{$filename}");
    //     file_put_contents($filePath, $csvContent);

    //     // Upload to Flowise using multipart/form-data
    //     try {
    //         $response = Http::attach(
    //             'files',
    //             file_get_contents($filePath),
    //             $filename
    //         )->withHeaders([
    //             'Authorization' => 'Bearer Mk-X8puuZ_i9nqpPlqQb91IfLI9so3tgtYW62ma_3R8',
    //         ])->post("http://localhost:3000/api/v1/document-store/upsert/abaf0e12-e1d4-410f-bef4-0073e637b951", [
    //             'metadata[source]' => 'chemical_properties',
    //         ]);

    //         // dd(file_get_contents($filePath));

    //         if (!$response->successful()) {
    //             \Log::error("Flowise Upsert Failed: " . $response->body());
    //         }
    //     } catch (\Exception $e) {
    //         \Log::error("Flowise Upsert Exception: " . $e->getMessage());
    //     }
    // }



}
