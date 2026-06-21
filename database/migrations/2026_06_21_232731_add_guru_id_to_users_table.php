<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom guru_id (boleh null, karena baris ADMIN nggak punya guru)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('guru_id')
                ->nullable()
                ->after('role')
                ->constrained('users')   // nunjuk ke users.id (relasi ke diri sendiri)
                ->nullOnDelete();         // kalau guru dihapus, guru_id murid jadi null
        });

        // 2. Data lama: semua MURID yang udah ada di-assign ke admin yang sekarang
        $adminId = DB::table('users')->where('role', 'admin')->value('id');
        if ($adminId) {
            DB::table('users')->where('role', 'murid')->update(['guru_id' => $adminId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropColumn('guru_id');
        });
    }
};
