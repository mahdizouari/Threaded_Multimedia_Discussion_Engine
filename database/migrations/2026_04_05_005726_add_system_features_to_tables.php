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
        // Add is_blocked to users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
        });

        // Add reports_count to posts
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('reports_count')->default(0);
        });

        // Add reports_count to comments
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('reports_count')->default(0);
        });

        // Pivot table for Interests (Users following Categories)
        Schema::create('category_user_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_user_interests');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('reports_count');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('reports_count');
        });
    }
};
