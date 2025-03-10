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
        Schema::create('users_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_activities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('users_category_id');
			$table->dropConstrainedForeignId('activity_id');
			$table->drop('users_categories');
        });
    }
};
