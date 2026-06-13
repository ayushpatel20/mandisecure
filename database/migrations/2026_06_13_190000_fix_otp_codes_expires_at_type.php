<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // TIMESTAMP columns in MariaDB automatically get ON UPDATE CURRENT_TIMESTAMP
        // on the first TIMESTAMP column, resetting expires_at to NOW() on every row update.
        // DATETIME never has this auto-update behavior.
        DB::statement('ALTER TABLE otp_codes MODIFY expires_at DATETIME NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE otp_codes MODIFY expires_at TIMESTAMP NOT NULL');
    }
};
