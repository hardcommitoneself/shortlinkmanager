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
        Schema::table('website_shortener_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('count_visits')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_shortener_settings', function (Blueprint $table) {
            $table->dropColumn('count_visits');
        });
    }
};
