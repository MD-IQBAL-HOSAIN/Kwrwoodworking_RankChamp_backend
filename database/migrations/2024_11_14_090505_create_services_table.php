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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        DB::table('services')->insert([
            ['title' => 'Custom Furniture Design', 'description' => 'We develop custom, responsive, and scalable websites using modern web technologies.', 'status' => 'active'],
            ['title' => 'Restoration & Repairs', 'description' => 'We develop custom, responsive, and scalable mobile apps for both iOS and Android platforms.', 'status' => 'active'],
            ['title' => 'Custom Home Decor', 'description' => 'We provide custom e-commerce solutions using popular platforms such as Shopify and Magento.', 'status' => 'active'],
            ['title' => 'Cabinetry & Shelving', 'description' => 'We provide digital marketing services such as SEO, social media marketing, and PPC management.', 'status' => 'active'],
            ['title' => 'Content Writing', 'description' => 'We provide high-quality content writing services for businesses and individuals.', 'status' => 'active'],
            ['title' => 'Graphic Design', 'description' => 'We provide custom graphic design services for logos, business cards, and other visual materials.', 'status' => 'active'],
            ['title' => 'Web Hosting', 'description' => 'We provide reliable and secure web hosting services for businesses and individuals.', 'status' => 'active'],
            ['title' => 'Cybersecurity', 'description' => 'We provide cybersecurity services to protect businesses and individuals from cyber threats.', 'status' => 'active'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
