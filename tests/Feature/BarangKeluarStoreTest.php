<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Barang;
use App\Models\Lokawisata;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BarangKeluarStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_menyimpan_barang_keluar_dan_mengurangi_stok()
    {
        $barang = Barang::factory()->create([
            'jumlah_stok' => 10,
            'harga_satuan' => 5000
        ]);

        $lokawisata = Lokawisata::factory()->create();

        $response = $this->post(route('barang_keluar.store'), [
            'barang_id' => $barang->id,
            'lokawisata_id' => $lokawisata->id,
            'tanggal_keluar' => '2024-01-10',
            'jumlah_keluar' => 3
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('barang_keluar', [
            'barang_id' => $barang->id,
            'jumlah_keluar' => 3,
            'harga_total' => 15000
        ]);

        $this->assertDatabaseHas('barang', [
            'id' => $barang->id,
            'jumlah_stok' => 7, // stok awal 10, keluar 3
        ]);
    }
}
