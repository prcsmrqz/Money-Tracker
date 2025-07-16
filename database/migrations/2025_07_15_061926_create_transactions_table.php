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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();

            $table->unsignedBigInteger('category_id')->index()->nullable(); // for income & expenses transaction required, nullable if savings_account_id exist
            $table->unsignedBigInteger('savings_account_id')->index()->nullable(); // for: savings transaction required, nullable if category_id exist

            $table->float('amount')->nullable();
            $table->unsignedBigInteger('source_type')->index()->default(0); // only: expenses, default: income - 0, but can select in savings (withdrawals)
            $table->string('notes')->nullable();
            $table->enum('type', ['income', 'expenses', 'savings']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('savings_account_id')->references('id')->on('savings_accounts')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
