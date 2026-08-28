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
        $table->date('deadline')->nullable()->after('tanggal_akta');
        $table->string('assigned_to')->nullable()->after('deadline'); // Nama Staff
        $table->string('status')->default('PROCESS')->after('assigned_to'); // PROCESS atau ARCHIVED
    });
}

public function down()
{
    Schema::table('archives', function (Blueprint $table) {
        $table->dropColumn(['deadline', 'assigned_to', 'status']);
    });
}
};