<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('archives', function (Blueprint $table) {
        // Tambah kolom Nomor Order & Kategori
        $table->string('nomor_order')->nullable()->after('uuid'); 
        $table->string('kategori')->default('Umum')->after('jenis_akta'); // Umum atau KPR
    });
}

public function down()
{
    Schema::table('archives', function (Blueprint $table) {
        $table->dropColumn(['nomor_order', 'kategori']);
    });
}
};
