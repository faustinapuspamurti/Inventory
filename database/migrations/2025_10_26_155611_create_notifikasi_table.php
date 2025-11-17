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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->string('nama_barang');
            $table->foreignId('lokawisata_id')->constrained('lokawisata')->onDelete('cascade');
            $table->string('nama_lokawisata');
            $table->integer('jumlah');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('pesan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
