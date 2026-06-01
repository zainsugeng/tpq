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
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_id')->constrained('modul')->cascadeOnDelete();
            $table->enum('tipe_konten', ['teks', 'gambar'])->default('teks');
            $table->string('teks_arab')->nullable();
            $table->string('gambar')->nullable();
            $table->string('label');
            $table->string('audio')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
