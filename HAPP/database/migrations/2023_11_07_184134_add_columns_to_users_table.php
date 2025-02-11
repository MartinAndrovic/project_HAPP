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
        Schema::table('users', function (Blueprint $table) {
			$table->string('oauth_id')->nullable();
			$table->boolean('is_admin')->default(false);
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('oauth_id');
			$table->dropColumn('is_admin');
            $table->dropColumn('age');
            $table->dropColumn('gender');
        });
    }
};
