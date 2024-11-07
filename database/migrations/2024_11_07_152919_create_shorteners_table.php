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
        Schema::create('shorteners', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('api_link')->default('https://example.com/api?api={apikey}&url={url}');
            $table->unsignedBigInteger('views')->default(1);
            $table->float('cpm')->default(1.00);
            $table->string('referral')->nullable();
            $table->string('demo')->nullable();
            $table->string('withdraw')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shorteners');
    }
};
