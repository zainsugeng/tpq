<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_ortu')->nullable()->after('nama');
            $table->string('jenis_kelamin', 1)->nullable()->after('nama_ortu'); // L / P
            $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_ortu', 'jenis_kelamin', 'tanggal_lahir']);
        });
    }
};
