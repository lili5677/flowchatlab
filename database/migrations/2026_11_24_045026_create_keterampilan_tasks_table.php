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
        Schema::create('keterampilan_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->unsignedInteger('level'); // 1, 2, 3
            $table->string('nama_level'); // Mudah, Sedang, Sulit
            $table->string('judul');
            $table->text('soal');
            $table->json('langkah'); // [{teks: string, simbol: string, urutan: int}]
            $table->timestamps();

            $table->index(['level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keterampilan_tasks');
    }
};
