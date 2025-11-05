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
        Schema::create('savings_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_user_id')->unique()->constrained('members', 'user_id')->cascadeOnDelete();
            $table->decimal('principal_saving', 15, 2)->default(0);
            $table->decimal('mandatory_saving', 15, 2)->default(0);
            $table->decimal('voluntary_saving', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('type', 50);
            $table->text('description')->nullable();
            $table->string('bukti_transfer')->nullable(false);
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_transactions');
        Schema::dropIfExists('savings_accounts');
    }
};
