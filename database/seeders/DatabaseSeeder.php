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
        // SQL dumps split large tables into multiple INSERT statements.
        $tables = ['users', 'sounds', 'sentences', 'words'];
        $inserts = [];

        foreach ($tables as $table) {
            $pattern = '/INSERT INTO `' . preg_quote($table, '/') . '`[^;]+;/su';
            if (preg_match_all($pattern, $sql, $matches)) {
                $inserts[$table] = $matches[0]; // array of INSERT statements
            } else {
                $this->command->error("No INSERT data found for table: {$table}");
                return;
            }
        }

        // Free the large string from memory
        unset($sql);

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
                }
                // Fix enum mismatch: MySQL data has 'picture_description', migration uses 'picture description'
                if ($table === 'sounds') {
                    $statement = str_replace('picture_description', 'picture description', $statement);
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
