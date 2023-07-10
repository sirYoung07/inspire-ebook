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
        Schema::create('rented_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->morphs('rentable');
            $table->decimal('total_cost', 10,2);
            $table->boolean('is_available')->default(true);
            $table->timestamp('start_rent_date')->nullable();
            $table->timestamp('end_rent_date')->nullable();
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rented_books');
    }
};
