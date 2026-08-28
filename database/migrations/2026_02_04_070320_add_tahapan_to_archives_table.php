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
        // Default tahap awal adalah "Pemberkasan / Validasi"
        $table->string('tahapan')->default('Pemberkasan')->after('kategori');
    });
}

public function down()
{
    Schema::table('archives', function (Blueprint $table) {
        $table->dropColumn('tahapan');
    });
}
};
