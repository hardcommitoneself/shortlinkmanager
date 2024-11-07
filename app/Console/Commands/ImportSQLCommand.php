<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSQLCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sql {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import records from an SQL file into a given table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error('File does not exist: ' . $filePath);

            return 1;
        }

        $sql = file_get_contents($filePath);

        // Split the SQL file into individual statements
        $statements = explode(";", $sql);

        foreach ($statements as $statement) {
            // Trim the statement and skip if it's empty
            $statement = trim($statement);
            if ($statement === '') {
                continue;
            }
            
            // Modify your SQL statement here to match the column names in your database
            // For example, if your destination table has 'name' and 'url' instead of 'full_name' and 'website_url':
            $statement = str_replace('shortlinks', 'shorteners', $statement);
            $statement = str_replace('apilink', 'api_link', $statement);
            $statement = str_replace('BMF', 'bmf', $statement);
            $statement = str_replace('updated', 'updated_at', $statement);

            $this->info($statement);

            $this->info('\n');

            try {
                DB::statement($statement);
            } catch (\Exception $e) {
                $this->error('Error executing statement: ' . $e->getMessage());
                continue;  // Skip on error and proceed to the next statement
            }
        }

        $this->info('Records imported successfully from ' . $filePath);

        return 0;
    }
}
