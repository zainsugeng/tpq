<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelajaran', function (Blueprint $table) {
            $table->string('subjudul')->nullable()->after('nama');
            $table->string('warna')->default('emerald')->after('subjudul');
        });

        // Isi otomatis untuk pelajaran yang sudah ada, biar tampilan tidak berubah
        DB::table('pelajaran')->where('nama', 'Iqro')->update([
            'subjudul' => 'Belajar huruf hijaiyah',
            'warna'    => 'emerald',
        ]);
        DB::table('pelajaran')->where('nama', 'Bahasa Arab')->update([
            'subjudul' => 'Kosakata dasar',
            'warna'    => 'rose',
        ]);
    }

    public function down(): void
    {
        Schema::table('pelajaran', function (Blueprint $table) {
            $table->dropColumn(['subjudul', 'warna']);
        });
    }
};
