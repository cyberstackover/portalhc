<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//web service ambil data kota luar negeri
Route::get('/monitoring-pejabat/{id_angka?}', 'Modules\Api\WebServiceController@DataMonitoringPejabat');

Route::get('/all-talent', 'Modules\Api\WebServiceController@biodatatalent');

Route::get('/cv-pejabat/{id_talenta?}', 'Modules\Api\WebServiceController@cvpejabat');

Route::get('/cv-pejabat-byname/{nama_talenta?}', 'Modules\Api\WebServiceController@cvpejabatbyname');

Route::get('/pejabat-bykepemilikan/{jenis_perusahaan?}', 'Modules\Api\WebServiceController@pejabatbykepemilikan');

Route::get('/jenis-jabatan', 'Modules\Api\WebServiceController@jenisjabatan');

Route::get('/klaster-bumn', 'Modules\Api\WebServiceController@klasterbumn');
