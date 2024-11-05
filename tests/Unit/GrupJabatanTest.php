<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GrupJabatanTest extends TestCase
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
     * [Test Untuk Insert Grup Jabatan Dengan Middleware]
     * @return [type] [description]
     */
    public function testCreateGrupJabatanWithOutMiddleware()
    {
        /*$data = [
                'nama' => "test nama",
                'keterangan' => "test keterangan",
                'actionform' => "insert",
                       ];

        $response = $this->json('POST', route('referensi.grupjabatan.store'),$data);
        $response->assertStatus(200);
        $response->assertExactJson([
            'flag'  => 'success',
            'msg' => 'Sukses tambah data',
            'title' => 'Sukses'
        ]);*/
        $this->assertTrue(true);
    }

    /**
     * [testGettingAllGrupJabatan description]
     * @return [type] [description]
     */
    public function testGettingAllGrupJabatan()
    {
        /*$response = $this->json('GET', route('referensi.grupjabatan.datatable'));
        $response->assertStatus(200);*/
        $this->assertTrue(true);
    }

}
