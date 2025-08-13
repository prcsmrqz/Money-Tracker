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
        if (Schema::hasColumn('transactions', 'source_type')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->renameColumn('source_type', 'source_savings');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'source_savings')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->renameColumn('source_savings', 'source_type');
            });
        }
    }

};
