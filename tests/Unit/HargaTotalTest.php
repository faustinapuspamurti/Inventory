<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HargaTotalTest extends TestCase
{
    public function test_harga_total()
    {
        $harga_satuan = 5000;
        $jumlah_keluar = 3;

        $harga_total = $harga_satuan * $jumlah_keluar;

        $this->assertEquals(15000, $harga_total);
    }
}
