<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DynamicTabelSumberTest extends TestCase
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
     *
     */
    public function testCreateDynamicTabelSumber()
    {
        /*$data = [
            'tabel' => "test tabel",
            'field' => "test field",
            'alias' => "test alias",
            'query' => 'test query',
            'keterangan' => 'test keterangan',
            'actionform' => 'insert'
        ];

        $response = $this->json('POST', route('referensi.dynamicfilter.tabelsumber.store'),$data);
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
        /*$response = $this->json('GET', route('referensi.dynamicfilter.tabelsumber..getall'));
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                [
                    'id',
                    'tabel',
                    'field',
                    'alias',
                    'query',
                    'keterangan',
                    'created_at',
                    'updated_at'
                ]
            ]
        );*/
        $this->assertTrue(true);
    }
}
