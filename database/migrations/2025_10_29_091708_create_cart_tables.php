<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_user_id')
                ->constrained('members', 'user_id')
                ->cascadeOnDelete();
            $table->string('status', 30)->default('draft');
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();

            $table->index('member_user_id');
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_price');
            $table->string('status', 30)->default('active');
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['cart_id', 'product_id']);
        });

        DB::statement("
            CREATE UNIQUE INDEX carts_member_user_active_unique
            ON carts (member_user_id)
            WHERE status IN ('draft', 'locked')
        ");

        DB::statement("
            CREATE UNIQUE INDEX cart_items_product_locked_unique
            ON cart_items (product_id)
            WHERE status = 'locked'
        ");

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE cart_items
                ADD CONSTRAINT cart_items_quantity_positive CHECK (quantity > 0)
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE cart_items
                DROP CONSTRAINT IF EXISTS cart_items_quantity_positive
            ");
        }

        DB::statement("
            DROP INDEX IF EXISTS cart_items_product_locked_unique
        ");

        DB::statement("
            DROP INDEX IF EXISTS carts_member_user_active_unique
        ");

        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
