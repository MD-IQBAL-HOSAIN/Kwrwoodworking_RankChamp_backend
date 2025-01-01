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
        Schema::create('contact_cms', function (Blueprint $table) {
            $table->id();
            $table->string('contact', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('support_email')->nullable();
            $table->string('location')->nullable();
            $table->string('opening_time')->nullable();
            $table->timestamps();
        });

        DB::table('contact_cms')->insert([
            // contact
            ['contact' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_cms');
    }
};
