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
        Schema::create('financing_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('member_user_id')->constrained('members', 'user_id')->cascadeOnDelete();
            $table->foreignId('admin_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('tenor');
            $table->unsignedBigInteger('cost_price_total');
            $table->bigInteger('margin');
            $table->unsignedBigInteger('selling_price_total');
            $table->unsignedBigInteger('monthly_installment');
            $table->string('status', 50)->default('Pending');
            $table->timestamp('application_date')->useCurrent();
            $table->timestamp('approval_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('agreement_signed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('installment_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financing_application_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('bill_number');
            $table->unsignedBigInteger('amount');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('status', 50)->default('Unpaid');
            $table->timestamps();

            $table->unique(['financing_application_id', 'bill_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_bills');
        Schema::dropIfExists('financing_applications');
    }
};
