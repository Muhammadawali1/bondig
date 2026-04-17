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
