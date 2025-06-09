<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportDbToJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-db-to-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export DB tables to JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = ['inventories', 'chemicals', 'users', 'markets'];

        foreach ($tables as $table) {
            $data = DB::table($table)->get();
            $json = json_encode($data, JSON_PRETTY_PRINT);

            Storage::disk('local')->put("exports/{$table}.json", $json);
        }

        $this->info('Database exported to JSON.');
    }
}
