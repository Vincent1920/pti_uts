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
        Schema::create('diskons', function (Blueprint $table) {
            $table->id();
            $table->string('nama_diskon');      // Contoh: "Diskon Lebaran"
            $table->integer('persentase');      // Contoh: 15 (artinya 15%)
            $table->boolean('status')->default(0);// Aktif (1) atau Tidak (0)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Diskon');
    }
};
