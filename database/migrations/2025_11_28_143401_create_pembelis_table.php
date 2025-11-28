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
        Schema::create('pembelis', function (Blueprint $table) {
            $table->id('ID_pembeli');
            $table->unsignedBigInteger('ID_user')->nullable();
            $table->string('foto');
            $table->string('kode_pos', 10)->nullable();
            $table->string('nomor_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();


            $table->foreign('ID_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelis');
    }
};
