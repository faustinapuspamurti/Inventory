<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Barang_masuk;
use App\Models\Barang_keluar;
use App\Models\Lokawisata;
use Faker\Factory;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Seed related inventory tables with ample sample data.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        // Seed lokawisata
        $lokawisatas = Lokawisata::factory()->count(10)->create();

        // Seed barang with reasonable starting stock and prices
        $barangs = collect();
        for ($i = 0; $i < 50; $i++) {
            $qty = $faker->numberBetween(50, 200);
            $price = $faker->numberBetween(5000, 200000);

            $barangs->push(Barang::create([
                'nama_barang' => ucfirst($faker->unique()->words(2, true)),
                'deskripsi' => $faker->sentence(8),
                'satuan' => $faker->randomElement(['pcs', 'unit', 'pack', 'box']),
                'harga_satuan' => $price,
                'jumlah_stok' => $qty,
                'harga_total' => $qty * $price,
            ]));
        }

        // Seed barang_masuk and keep stock in sync
        for ($i = 0; $i < 200; $i++) {
            $item = $barangs->random();
            $qty = $faker->numberBetween(5, 50);
            $date = Carbon::now()->subDays($faker->numberBetween(0, 180))->format('Y-m-d');

            Barang_masuk::create([
                'barang_id' => $item->id,
                'jumlah_masuk' => $qty,
                'tanggal_masuk' => $date,
                'deskripsi' => $faker->sentence(),
                'evidence' => null,
            ]);

            $item->jumlah_stok += $qty;
            $item->harga_total = $item->jumlah_stok * $item->harga_satuan;
            $item->save();
        }

        // Seed barang_keluar ensuring stock does not go negative
        for ($i = 0; $i < 150; $i++) {
            $candidates = $barangs->filter(fn ($b) => $b->jumlah_stok > 5);
            if ($candidates->isEmpty()) {
                break;
            }

            $item = $candidates->random();
            $maxOut = max(1, min(30, $item->jumlah_stok - 1));
            $qty = $faker->numberBetween(1, $maxOut);
            $date = Carbon::now()->subDays($faker->numberBetween(0, 120))->format('Y-m-d');
            $lok = $lokawisatas->random();

            Barang_keluar::create([
                'barang_id' => $item->id,
                'tanggal_keluar' => $date,
                'jumlah_keluar' => $qty,
                'harga_satuan' => $item->harga_satuan,
                'harga_total' => $qty * $item->harga_satuan,
                'lokawisata_id' => $lok->id,
                'keterangan' => $faker->sentence(),
                'evidence' => null,
            ]);

            $item->jumlah_stok -= $qty;
            $item->harga_total = $item->jumlah_stok * $item->harga_satuan;
            $item->save();
        }
    }
}
