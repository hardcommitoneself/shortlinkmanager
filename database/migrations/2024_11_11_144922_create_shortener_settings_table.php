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
        Schema::create('shortener_settings', function (Blueprint $table) {
            $table->id();

            $table->string('api_key');
            $table->unsignedBigInteger('views');
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('priority');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shortener_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortener_settings');
    }
};
