<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom guru_id ke pelajaran (pemilik = admin/guru)
        Schema::table('pelajaran', function (Blueprint $table) {
            $table->foreignId('guru_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        // 2. Data lama: semua PELAJARAN yang udah ada di-assign ke admin yang sekarang
        $adminId = DB::table('users')->where('role', 'admin')->value('id');
        if ($adminId) {
            DB::table('pelajaran')->update(['guru_id' => $adminId]);
        }
    }

    public function down(): void
    {
        Schema::table('pelajaran', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropColumn('guru_id');
        });
    }
};
