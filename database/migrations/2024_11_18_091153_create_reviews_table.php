<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('name')->nullable();
            $table->text('designation')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        DB::table('reviews')->insert([
            [
                'slug' => 'review-1',
                'name' => 'Lora Smith',
                'designation' => 'CEO & Founder Crix',
                'description' => 'I couldn’t be happier with the custom dining table I ordered. The quality of the wood and the attention to detail are beyond expectation.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
