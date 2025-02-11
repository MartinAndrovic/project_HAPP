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
        Schema::create('filter_matches', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('filter_1_id')->references('id')->on('filters')->constrained()->onDelete('cascade');
            $table->foreignId('filter_2_id')->references('id')->on('filters')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('filter_matches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('filter_1_id');
			$table->dropConstrainedForeignId('filter_2_id');
			$table->drop('filter_matches');
        });
    }
};
