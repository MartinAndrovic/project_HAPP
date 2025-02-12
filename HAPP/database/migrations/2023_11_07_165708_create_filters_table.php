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
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('age_from')->nullable();
            $table->integer('age_to')->nullable();
            $table->date('date_from')->nullable();;
            $table->date('date_to')->nullable();;
            $table->string('group')->nullable();
            $table->string('gender')->nullable();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filters', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
			$table->drop('filters');
        });
    }
};
