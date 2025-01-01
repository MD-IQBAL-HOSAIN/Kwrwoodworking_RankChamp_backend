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
        Schema::create('c_m_s', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
        DB::table('c_m_s')->insert([
           // service banner
           ['title' => 'service banner', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('c_m_s')->insert([
            // product banner
            ['title' => 'product banner', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('c_m_s')->insert([
            // cart banner
            ['title' => 'cart banner', 'created_at' => now(), 'updated_at' => now()],

        ]);

        DB::table('c_m_s')->insert([
            // home page banner (using id 4)
            ['title' => 'home banner', 'created_at' => now(), 'updated_at' => now()],

        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_m_s');
    }
};
