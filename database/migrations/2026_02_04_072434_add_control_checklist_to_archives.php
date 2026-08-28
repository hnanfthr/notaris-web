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
        // Kita pakai tipe JSON biar fleksibel nyimpen checklistnya
        $table->json('checklist_items')->nullable()->after('tahapan'); 
        // Kolom khusus catatan lembar kontrol (sesuai request chat)
        $table->text('catatan_kontrol')->nullable()->after('checklist_items');
    });
}

public function down()
{
    Schema::table('archives', function (Blueprint $table) {
        $table->dropColumn(['checklist_items', 'catatan_kontrol']);
    });
}
};
