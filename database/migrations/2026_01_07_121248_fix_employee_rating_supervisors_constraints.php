<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite - recreate the table with correct constraints
        DB::statement('PRAGMA foreign_keys = OFF');
        
        // Get the table structure
        $tableDef = DB::selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = 'employee_rating_supervisors'");
        
        // Create new table with correct constraints
        DB::statement("
            CREATE TABLE employee_rating_supervisors_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                employee_number TEXT NOT NULL,
                supervisor_id TEXT NOT NULL,
                kpa_id INTEGER NULLABLE,
                relationship_type TEXT DEFAULT 'direct',
                rating_weight INTEGER DEFAULT 100,
                is_primary BOOLEAN DEFAULT 0,
                can_view_appraisal BOOLEAN DEFAULT 1,
                can_approve_appraisal BOOLEAN DEFAULT 1,
                notes TEXT,
                rating DECIMAL(5,2),
                comments TEXT,
                status TEXT DEFAULT 'pending',
                rated_at TIMESTAMP,
                created_at TIMESTAMP,
                updated_at TIMESTAMP,
                FOREIGN KEY (employee_number) REFERENCES users(employee_number) ON DELETE CASCADE,
                FOREIGN KEY (supervisor_id) REFERENCES users(employee_number) ON DELETE CASCADE,
                FOREIGN KEY (kpa_id) REFERENCES appraisal_kpas(id) ON DELETE CASCADE,
                UNIQUE(employee_number, supervisor_id, kpa_id)
            )
        ");
        
        // Copy all data except duplicates that would violate the new constraint
        DB::statement("
            INSERT INTO employee_rating_supervisors_new 
            SELECT 
                id,
                employee_number,
                supervisor_id,
                kpa_id,
                relationship_type,
                rating_weight,
                is_primary,
                can_view_appraisal,
                can_approve_appraisal,
                notes,
                rating,
                comments,
                status,
                rated_at,
                created_at,
                updated_at
            FROM employee_rating_supervisors
            GROUP BY employee_number, supervisor_id, kpa_id
        ");
        
        // Drop old table
        DB::statement('DROP TABLE employee_rating_supervisors');
        
        // Rename new table
        DB::statement('ALTER TABLE employee_rating_supervisors_new RENAME TO employee_rating_supervisors');
        
        // Recreate other indexes (but not the wrong unique ones)
        DB::statement('CREATE INDEX employee_rating_supervisors_kpa_id_supervisor_id_status_index ON employee_rating_supervisors(kpa_id, supervisor_id, status)');
        DB::statement('CREATE INDEX employee_rating_supervisors_supervisor_id_index ON employee_rating_supervisors(supervisor_id)');
        DB::statement('CREATE INDEX employee_rating_supervisors_is_primary_index ON employee_rating_supervisors(is_primary)');
        DB::statement('CREATE INDEX employee_rating_supervisors_employee_number_index ON employee_rating_supervisors(employee_number)');
        
        DB::statement('PRAGMA foreign_keys = ON');
        
        echo "Table recreated with correct constraints!\n";
    }

    public function down(): void
    {
        // Note: This rollback is destructive - it will recreate the old constraints
        // You might want to backup first
        DB::statement('PRAGMA foreign_keys = OFF');
        
        // Recreate with old constraints for rollback
        DB::statement("
            CREATE TABLE employee_rating_supervisors_old (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                employee_number TEXT NOT NULL,
                supervisor_id TEXT NOT NULL,
                kpa_id INTEGER NULLABLE,
                relationship_type TEXT DEFAULT 'direct',
                rating_weight INTEGER DEFAULT 100,
                is_primary BOOLEAN DEFAULT 0,
                can_view_appraisal BOOLEAN DEFAULT 1,
                can_approve_appraisal BOOLEAN DEFAULT 1,
                notes TEXT,
                rating DECIMAL(5,2),
                comments TEXT,
                status TEXT DEFAULT 'pending',
                rated_at TIMESTAMP,
                created_at TIMESTAMP,
                updated_at TIMESTAMP,
                FOREIGN KEY (employee_number) REFERENCES users(employee_number) ON DELETE CASCADE,
                FOREIGN KEY (supervisor_id) REFERENCES users(employee_number) ON DELETE CASCADE,
                FOREIGN KEY (kpa_id) REFERENCES appraisal_kpas(id) ON DELETE CASCADE,
                UNIQUE(kpa_id, supervisor_id),
                UNIQUE(employee_number, supervisor_id)
            )
        ");
        
        DB::statement("
            INSERT INTO employee_rating_supervisors_old 
            SELECT * FROM employee_rating_supervisors
        ");
        
        DB::statement('DROP TABLE employee_rating_supervisors');
        DB::statement('ALTER TABLE employee_rating_supervisors_old RENAME TO employee_rating_supervisors');
        
        // Recreate indexes
        DB::statement('CREATE INDEX employee_rating_supervisors_kpa_id_supervisor_id_status_index ON employee_rating_supervisors(kpa_id, supervisor_id, status)');
        DB::statement('CREATE INDEX employee_rating_supervisors_supervisor_id_index ON employee_rating_supervisors(supervisor_id)');
        DB::statement('CREATE INDEX employee_rating_supervisors_is_primary_index ON employee_rating_supervisors(is_primary)');
        DB::statement('CREATE INDEX employee_rating_supervisors_employee_number_index ON employee_rating_supervisors(employee_number)');
        
        DB::statement('PRAGMA foreign_keys = ON');
    }
};