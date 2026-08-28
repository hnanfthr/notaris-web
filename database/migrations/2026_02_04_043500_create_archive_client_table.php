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
    Schema::create('archive_client', function (Blueprint $table) {
        $table->id();
        $table->foreignId('archive_id')->constrained('archives')->onDelete('cascade');
        $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
        $table->string('peran_dalam_akta')->nullable(); // Misal: Pihak I, Pihak II
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_client');
    }
};
