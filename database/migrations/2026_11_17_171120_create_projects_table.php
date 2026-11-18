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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category');
            $table->string('year', 4);
            $table->boolean('featured')->default(false);
            $table->text('summary');
            $table->string('cover_path')->nullable();
            $table->json('tech')->nullable();
            $table->string('role')->nullable();
            $table->text('problem')->nullable();
            $table->text('goal')->nullable();
            $table->json('features')->nullable();
            $table->text('process')->nullable();
            $table->text('feedback')->nullable();
            $table->json('metrics')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
