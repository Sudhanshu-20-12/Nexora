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
    Schema::create('vendors', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('store_name');
        $table->string('store_slug')->unique();
        $table->text('store_description')->nullable();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->string('logo')->nullable();

        $table->enum('status', [
            'pending',
            'approved',
            'rejected',
            'suspended'
        ])->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('vendors');
}
};
