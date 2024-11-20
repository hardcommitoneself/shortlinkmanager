<?php

namespace App\Console\Commands;

use App\Models\Shortener;
use Illuminate\Console\Command;

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

    public function extractColumns($insertStatement)
    {
        $insertStatement = preg_replace('/\s+/', ' ', trim($insertStatement));

        // Check if it's a valid INSERT INTO statement
        if (stripos($insertStatement, 'INSERT INTO') === 0) {
            // Extract the table name and the columns values part
            $parts = explode(' VALUES ', $insertStatement);

            $valuesSets = explode('), (', $parts[1]);

            foreach ($valuesSets as $valuesSet) {
                $valuesSet = trim($valuesSet, '(');
                $valuesSet = trim($valuesSet, ')');

                $values = explode(', ', $valuesSet);

                $name = $values[1];
                $api_link = $values[2];
                $views = $values[3];
                $cpm = $values[4];
                $referral = $values[5];
                $demo = $values[6];
                $withdraw = $values[8];
                $status = trim($values[9], "'") == 'Y' ? true : false;

                $shortener = new Shortener;

                $shortener->name = trim($name, "'");
                $shortener->api_link = trim($api_link, "'");
                $shortener->views = (int) trim($views, "'");
                $shortener->cpm = $cpm;
                $shortener->referral = trim($referral, "'");
                $shortener->demo = trim($demo, "'");
                $shortener->withdraw = trim($withdraw, "'");
                $shortener->status = $status;

                $shortener->save();
            }
        }

        return [];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (! file_exists($filePath)) {
            $this->error('File does not exist: '.$filePath);

            return 1;
        }

        $sql = file_get_contents($filePath);

        // Split the SQL file into individual statements
        $statements = explode(';', $sql);

        foreach ($statements as $statement) {
            $trimmedStatement = trim($statement);

            $result = self::extractColumns($trimmedStatement);
        }

        return 0;
    }
}
