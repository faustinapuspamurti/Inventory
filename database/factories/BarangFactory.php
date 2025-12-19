<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'nama_barang'   => $this->faker->word(),
            'jumlah_stok'   => 10,
            'harga_satuan'  => 5000,
            'harga_total'   => 10 * 5000,
            'satuan'        => 'pcs',
        ];
    }
}
