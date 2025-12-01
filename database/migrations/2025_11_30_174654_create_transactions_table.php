<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Transaksi Utama (Header)
        // ...
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
    $table->string('invoice_code')->unique();
    $table->decimal('subtotal', 15, 2);
    $table->decimal('discount_amount', 15, 2)->default(0);
    $table->decimal('grand_total', 15, 2);
    $table->string('payment_proof')->nullable();
    // PERUBAHAN DI SINI:
    // Hapus first_name & last_name, ganti jadi name
    $table->string('name'); // Nama Penerima
    $table->string('email');
    $table->string('phone');
    $table->string('address');
    $table->string('city');
    $table->string('postal_code');
    $table->string('country')->default('Indonesia');
    $table->string('status');
    $table->string('payment_method')->nullable();
    
    $table->timestamps();
});
// ...

        // 2. Tabel Detail Item Transaksi
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('barang_id')->nullable(); 
            $table->string('product_name'); 
            $table->integer('quantity');
            $table->decimal('price', 15, 2); 
            $table->decimal('subtotal', 15, 2); 
            
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            
            // Karena di atas sudah nullable, maka set null ini baru bisa bekerja
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('set null'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
    }
};