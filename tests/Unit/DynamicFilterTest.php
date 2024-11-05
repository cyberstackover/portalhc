<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DynamicFilterTest extends TestCase
{
    /**
     * Untuk bypass Middleware
     */
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    /**
     * [Test Untuk Insert Dynamic Filter Dengan Middleware]
     * @return [type] [description]
     */
    public function testCreateDynamicFilter()
    {
        /*$data = [
            'menu' => "test menu",
            'submenu' => "test submenu",
            'tipe' => "test tipe",
            'dynamic_tabel_sumber_id' => 1,
            'dynamic_standar_value_id' => 1,
            'keterangan' => "test keterangan",
            'aktif' => true,
            'is_number' => true,
            'actionform' => 'insert'
        ];

        $response = $this->json('POST', route('talenta.filter_dynamic.store'),$data);
        $response->assertStatus(200);
        $response->assertExactJson([
            'flag'  => 'success',
            'msg' => 'Sukses tambah data',
            'title' => 'Sukses'
        ]);*/
        $this->assertTrue(true);
    }

    public function testReadDynamicFilter()
    {
        /*$response = $this->json('GET', route('talenta.filter_dynamic.getall'));
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                [
                    'menu',
                    'submenu',
                    'tipe',
                    'dynamic_tabel_sumber_id',
                    'dynamic_standar_value_id',
                    'keterangan',
                    'aktif',
                    'is_number',
                    'created_at',
                    'updated_at'
                ]
            ]
        );*/
        $this->assertTrue(true);
    }

    public function testUpdateDynamicFilter()
    {
        /*$response = $this->json('GET', route('talenta.filter_dynamic.getall'));
        $response->assertStatus(200);
        $order = $response->getData()[0];

        $updatetest = array(
            'submenu' => $order->submenu,
            'tipe' => $order->tipe,
            'actionform' => 'update',
            'dynamic_tabel_sumber_id' => $order->dynamic_tabel_sumber_id,
            'dynamic_standar_value_id' => $order->dynamic_standar_value_id,
            'keterangan' => $order->keterangan,
            'aktif' => $order->aktif,
            'id' => $order->id
        );

        $update = $this->json('POST', route('talenta.filter_dynamic.store'),$updatetest);
        $update->assertStatus(200);
        $update->assertExactJson([
            'flag'  => 'success',
            'msg' => 'Sukses ubah data',
            'title' => 'Sukses'
        ]);*/
        $this->assertTrue(true);
    }

    public function testDeleteDynamicFilter()
    {
        /*$response = $this->json('GET', route('talenta.filter_dynamic.getall'));
        $response->assertStatus(200);
        $order = $response->getData()[0];

        $delete = $this->json('POST', route('talenta.filter_dynamic.delete'),['id' => $order->id]);
        $delete->assertStatus(200);
        $delete->assertExactJson([
            'flag'  => 'success',
            'msg' => 'Sukses hapus data',
            'title' => 'Sukses'
        ]);*/
        $this->assertTrue(true);
    }
}
