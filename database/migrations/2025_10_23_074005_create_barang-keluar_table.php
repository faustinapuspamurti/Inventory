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
        Schema::create('barang_keluar', function (Blueprint $table) {
        $table->id();
        $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
        $table->date('tanggal_keluar');
        $table->integer('jumlah_keluar');
        $table->string('harga_satuan');
        $table->string('harga_total');
        $table->foreignId('lokawisata_id')->constrained('lokawisata')->onDelete('cascade');
        $table->text('keterangan')->nullable();
        $table->string('evidence')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
