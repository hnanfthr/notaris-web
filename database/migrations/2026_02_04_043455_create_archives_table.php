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
    Schema::create('archives', function (Blueprint $table) {
        $table->id();
        $table->uuid('uuid')->unique(); // Untuk QR Code
        $table->string('nomor_akta');
        $table->date('tanggal_akta');
        $table->string('judul_akta');
        $table->string('jenis_akta')->nullable();
        $table->string('lokasi_lemari')->nullable();
        $table->string('lokasi_rak')->nullable();
        $table->string('file_path')->nullable(); // Upload PDF
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
