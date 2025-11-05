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
        Schema::create('ledger_account_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 20)->unique();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('ledger_account_groups')
                ->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ledger_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->enum('type', ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense']);
            $table->enum('normal_balance', ['Debit', 'Credit']);
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('ledger_accounts')
                ->nullOnDelete();
            $table->foreignId('group_id')
                ->nullable()
                ->constrained('ledger_account_groups')
                ->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ledger_journals', function (Blueprint $table) {
            $table->id();
            $table->string('journal_number', 30)->unique();
            $table->date('journal_date');
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable()->index();
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });

        Schema::create('ledger_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained('ledger_journals')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('ledger_accounts')->cascadeOnDelete();
            $table->foreignId('member_user_id')
                ->nullable()
                ->constrained('members', 'user_id')
                ->nullOnDelete();
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('credit')->default(0);
            $table->string('memo', 150)->nullable();
            $table->timestamps();

            $table->index(['journal_id', 'account_id']);
        });

        Schema::create('ledger_closings', function (Blueprint $table) {
            $table->id();
            $table->date('period_start');
            $table->date('period_end');
            $table->foreignId('closed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('closed_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ledger_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('ledger_accounts')->cascadeOnDelete();
            $table->string('period', 7);
            $table->bigInteger('opening_balance')->default(0);
            $table->bigInteger('debit_total')->default(0);
            $table->bigInteger('credit_total')->default(0);
            $table->bigInteger('closing_balance')->default(0);
            $table->timestamps();

            $table->unique(['account_id', 'period']);
        });

        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->foreignId('ledger_journal_id')
                ->nullable()
                ->after('transaction_date')
                ->constrained('ledger_journals')
                ->nullOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('ledger_journal_id')
                ->nullable()
                ->after('payment_method')
                ->constrained('ledger_journals')
                ->nullOnDelete();
        });

        Schema::table('installment_bills', function (Blueprint $table) {
            $table->foreignId('ledger_journal_id')
                ->nullable()
                ->after('status')
                ->constrained('ledger_journals')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installment_bills', function (Blueprint $table) {
            $table->dropForeign(['ledger_journal_id']);
            $table->dropColumn('ledger_journal_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['ledger_journal_id']);
            $table->dropColumn('ledger_journal_id');
        });

        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->dropForeign(['ledger_journal_id']);
            $table->dropColumn('ledger_journal_id');
        });

        Schema::dropIfExists('ledger_balances');
        Schema::dropIfExists('ledger_closings');
        Schema::dropIfExists('ledger_lines');
        Schema::dropIfExists('ledger_journals');
        Schema::dropIfExists('ledger_accounts');
        Schema::dropIfExists('ledger_account_groups');
    }
};
