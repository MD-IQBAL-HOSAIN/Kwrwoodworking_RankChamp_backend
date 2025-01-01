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
        Schema::create('home_c_m_s', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('sub_description')->nullable();
            $table->string('button_text')->nullable();
            $table->timestamps();
        });

        DB::table('home_c_m_s')->insert([
            // Home Page ................................................................
            // header
            ['title' => 'Header banner', 'created_at' => now(), 'updated_at' => now()],
            ['title' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_c_m_s');
    }
};
