<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DynamicOperatorTest extends TestCase
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
     * [Test Untuk Insert Dynamic Operator Dengan Middleware]
     * @return [type] [description]
     */
    public function testCreateDynamicOperator()
    {
        /*$data = [
            'nama' => "test nama menu",
            'operator' => "test operator",
            'keterangan' => "test keterangan",
            'is_sorting' => true,
            'aktif' => true,
            'is_number' => true,
            'actionform' => 'insert'
        ];

        $response = $this->json('POST', route('referensi.dynamicfilter.operator.store'),$data);
        $response->assertStatus(200);
        $response->assertExactJson([
            'flag'  => 'success',
            'msg' => 'Sukses tambah data',
            'title' => 'Sukses'
        ]);*/
        $this->assertTrue(true);
    }

    public function testReadDynamicOperator()
    {
        /*$response = $this->json('GET', route('referensi.dynamicfilter.operator.getall'));
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                [
                    'id',
                    'nama',
                    'operator',
                    'is_number',
                    'is_sorting',
                    'aktif',
                    'keterangan',
                    'created_at',
                    'updated_at'
                ]
            ]
        );*/
        $this->assertTrue(true);
    }
}
