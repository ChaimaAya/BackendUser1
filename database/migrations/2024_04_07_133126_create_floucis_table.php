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
        Schema::create('floucis', function (Blueprint $table) {
            $table->id();
            $table->string('app_public');
            $table->string('app_secret');
            $table->unsignedBigInteger('id_startup');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->foreign('id_startup')->references('id')->on('startups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floucis');
    }
};
