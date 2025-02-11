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
        Schema::create('users_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_regions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('filter_id');
			$table->dropConstrainedForeignId('region_id');
			$table->drop('users_regions');
        });
    }
};
