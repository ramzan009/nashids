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
        Schema::create('azkars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('azkar_type_id')->constrained('azkars');
            $table->text('content_arabic');
            $table->text('content_rus');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('azkars');
    }
};
