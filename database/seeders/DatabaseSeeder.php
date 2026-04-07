<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database from chinese.sql dump.
     *
     * Seeds: users, sounds, sentences, words (new schema).
     * Supports both MySQL and PostgreSQL.
     */
    public function run(): void
    {
        $sqlPath = database_path('data/chinese.sql');

        if (!file_exists($sqlPath)) {
            $this->command->error('database/data/chinese.sql not found.');
            return;
        }

        $sql = file_get_contents($sqlPath);

        // Extract all INSERT INTO blocks for each target table.
        // Uses line-based parsing to correctly handle semicolons inside data values.
        // phpMyAdmin dumps have each INSERT ending with ");\n" on the last row.
        $tables = ['users', 'sounds', 'sentences', 'words'];
        $targetTables = array_flip($tables);
        $inserts = [];
        $lines = explode("\n", $sql);
        $currentTable = null;
        $currentStmt = '';

        foreach ($lines as $line) {
            if (preg_match('/^INSERT INTO `(\w+)`/', $line, $m)) {
                $currentTable = $m[1];
                $currentStmt = $line;
            } elseif ($currentTable !== null) {
                $currentStmt .= "\n" . $line;
            }

            // Statement ends when line ends with ");" (phpMyAdmin dump format)
            if ($currentTable !== null && preg_match('/\);\s*$/', $line)) {
                if (isset($targetTables[$currentTable])) {
                    $inserts[$currentTable][] = $currentStmt;
                }
                $currentTable = null;
                $currentStmt = '';
            }
        }

        // Free memory
        unset($sql, $lines);

        foreach ($tables as $table) {
            if (empty($inserts[$table])) {
                $this->command->error("No INSERT data found for table: {$table}");
                return;
            }
        }

        $driver = DB::connection()->getDriverName();
        $isPgsql = $driver === 'pgsql';

        // Truncate in reverse FK order
        foreach (array_reverse($tables) as $table) {
            if ($isPgsql) {
                DB::statement("TRUNCATE TABLE \"{$table}\" RESTART IDENTITY CASCADE");
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                DB::table($table)->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        }

        // Insert in FK order (users → sounds → sentences → words)
        foreach ($tables as $table) {
            $count = count($inserts[$table]);
            $this->command->info("Seeding {$table} ({$count} batch(es))...");
            foreach ($inserts[$table] as $statement) {
                if ($isPgsql) {
                    $statement = str_replace('`', '"', $statement);
                    // MySQL escapes single quotes as \', PostgreSQL uses ''
                    $statement = str_replace("\\'", "''", $statement);
                }
                // Fix enum mismatch: MySQL data has 'picture_description', migration uses 'picture description'
                // Also convert .wav references to .mp3
                if ($table === 'sounds') {
                    $statement = str_replace('picture_description', 'picture description', $statement);
                    $statement = str_replace('.wav', '.mp3', $statement);
                }
                DB::unprepared($statement);
            }
        }

        // Reset auto-increment sequences for PostgreSQL
        if ($isPgsql) {
            foreach ($tables as $table) {
                DB::statement("SELECT setval(pg_get_serial_sequence('{$table}', 'id'), COALESCE((SELECT MAX(id) FROM \"{$table}\"), 0))");
            }
        }

        // Verify counts
        $counts = [
            'users'     => DB::table('users')->count(),
            'sounds'    => DB::table('sounds')->count(),
            'sentences' => DB::table('sentences')->count(),
            'words'     => DB::table('words')->count(),
        ];

        foreach ($counts as $table => $count) {
            $this->command->info("{$table}: {$count} rows seeded.");
        }
    }
}
