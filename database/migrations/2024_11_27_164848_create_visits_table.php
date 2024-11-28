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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->string('ip');
            $table->string('country');
            $table->string('country_code');
            $table->string('region');
            $table->string('city');
            $table->string('zip');
            $table->string('time_zone');
            $table->string('token');
            $table->boolean('is_completed')->default(false);
            $table->foreignId('short_link_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
