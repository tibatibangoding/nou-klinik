<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('periksa', function (Blueprint $table) {
            $table->integer('id_tindakan');
            $table->longText('detail_tindakan');
            $table->longText('diagnosis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periksa', function (Blueprint $table) {
            $table->dropColumn('id_tindakan');
            $table->dropColumn('detail_tindakan');
            $table->dropColumn('diagnosis');
        });
    }
};
