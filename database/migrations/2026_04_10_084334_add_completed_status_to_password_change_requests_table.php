<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if constraint already exists with correct values
        $constraintExists = DB::select("SELECT EXISTS (
            SELECT 1 
            FROM pg_constraint 
            WHERE conname = 'password_change_requests_status_check' 
            AND conrelid = 'password_change_requests'::regclass
        ) as exists")[0]->exists;

        if ($constraintExists) {
            // Constraint exists, check if it has the correct values
            $checkValue = DB::select("SELECT pg_get_constraintdef(oid) as definition 
                FROM pg_constraint 
                WHERE conname = 'password_change_requests_status_check'")[0]->definition;
            
            // If constraint already has 'completed' in the check, skip this migration
            if (str_contains($checkValue, 'completed')) {
                return;
            }
        }

        // PostgreSQL doesn't support changing enum values directly
        // Need to drop and recreate the column
        DB::statement("ALTER TABLE password_change_requests ALTER COLUMN status TYPE VARCHAR(255)");
        
        // Drop constraint if exists
        DB::statement("ALTER TABLE password_change_requests DROP CONSTRAINT IF EXISTS password_change_requests_status_check");
        
        DB::statement("ALTER TABLE password_change_requests ADD CONSTRAINT password_change_requests_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'completed'))");
        DB::statement("ALTER TABLE password_change_requests ALTER COLUMN status SET DEFAULT 'pending'");
        DB::statement("ALTER TABLE password_change_requests ALTER COLUMN status SET NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE password_change_requests DROP CONSTRAINT password_change_requests_status_check");
        DB::statement("ALTER TABLE password_change_requests ALTER COLUMN status TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE password_change_requests ADD CONSTRAINT password_change_requests_status_check CHECK (status IN ('pending', 'approved', 'rejected'))");
        DB::statement("ALTER TABLE password_change_requests ALTER COLUMN status SET DEFAULT 'pending'");
        DB::statement("ALTER TABLE password_change_requests ALTER COLUMN status SET NOT NULL");
    }
};
