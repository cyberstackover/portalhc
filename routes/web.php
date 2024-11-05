<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('logout', function() {
    \Auth::logout();
    \Session::flush();
    \Session::invalidate();
})->name('logout');

Route::get('/login',function() {
    \Auth::login(\App\User::find(581));
    return Redirect::to('/');
})->name('login'); 
// Route::group(['middleware' => 'auth'],function(){

Route::group(
    ['middleware' => ['auth']],
    function() {  
	Route::get('/', 'HomeController@index')->name('home');
    Route::get('/gettabledetail/{id?}/{kategori?}/{id_filter?}', 'HomeController@gettabledetail')->name('home.gettabledetail')->where([
      'id', '[0-9]+',
      'kategori', '[0-9]+'
    ]);
    Route::get('/gettablekontribusi', 'HomeController@gettablekontribusi')->name('home.gettablekontribusi');
    Route::get('/gettableperusahaan', 'HomeController@gettableperusahaan')->name('home.gettableperusahaan');
    Route::get('/gettabletalenta', 'HomeController@gettabletalenta')->name('home.gettabletalenta');

    Route::get('/chartmasajabatans', 'HomeController@chartmasajabatans')->name('home.chartmasajabatans');
    Route::get('/chartkontribusi', 'HomeController@chartkontribusi')->name('home.chartkontribusi');
    Route::get('/chartinstansi', 'HomeController@chartinstansi')->name('home.chartinstansi');
    Route::get('/chartdemografi', 'HomeController@chartdemografi')->name('home.chartdemografi');
    Route::get('/chartdemografijk', 'HomeController@chartdemografijk')->name('home.chartdemografijk');
    Route::get('/chartdemografiusia', 'HomeController@chartdemografiusia')->name('home.chartdemografiusia');
    Route::get('/chartdemografipendidikan', 'HomeController@chartdemografipendidikan')->name('home.chartdemografipendidikan');
    Route::get('/chartjumlah', 'HomeController@chartjumlah')->name('home.chartjumlah');
    Route::get('/datatabletigabln/{id?}', 'HomeController@datatabletigabln')->name('home.datatabletigabln')->where('id', '[0-9]+');
    Route::get('/datatableenambln/{id?}', 'HomeController@datatableenambln')->name('home.datatableenambln')->where('id', '[0-9]+');
    Route::get('/datatableexpired/{id?}', 'HomeController@datatableexpired')->name('home.datatableexpired')->where('id', '[0-9]+');
    Route::get('/datatablerangkap/{id?}', 'HomeController@datatablerangkap')->name('home.datatablerangkap')->where('id', '[0-9]+');
    Route::get('/datatablekosong/{id?}/{id_filter?}', 'HomeController@datatablekosong')->name('home.datatablekosong')->where('id', '[0-9]+');
    Route::get('/datatablekontribusi', 'HomeController@datatablekontribusi')->name('home.datatablekontribusi');
    Route::get('/datatableperusahaan', 'HomeController@datatableperusahaan')->name('home.datatableperusahaan');
    Route::get('/datatabletalenta', 'HomeController@datatabletalenta')->name('home.datatabletalenta');

    Route::prefix('log')->group(function(){
        Route::get('index', 'Log\ActivityUserController@index')->name('log.index');
        Route::get('datatable', 'Log\ActivityUserController@datatable')->name('log.datatable');

    });

    Route::prefix('talenta')->group(function(){

        // Dashboard
        Route::prefix('dashboard')->group(function(){
            Route::get('index', 'Talenta\DashboardController@index')->name('talenta.dashboard.index');
            Route::get('datatable', 'Talenta\DashboardController@datatable')->name('talenta.dashboard.datatable');
            Route::get('datatabletalenta', 'Talenta\DashboardController@datatabletalenta')->name('talenta.dashboard.datatabletalenta');
            Route::get('datatablerekap', 'Talenta\DashboardController@datatablerekap')->name('talenta.dashboard.datatablerekap');
            Route::get('datatabletalentarekap', 'Talenta\DashboardController@datatabletalentarekap')->name('talenta.dashboard.datatabletalentarekap');
            Route::get('chartjumlahtalenta', 'Talenta\DashboardController@chartjumlahtalenta')->name('talenta.dashboard.chartjumlahtalenta');
            Route::get('gettabledetail/', 'Talenta\DashboardController@gettabledetail')->name('talenta.dashboard.gettabledetail');
            Route::get('gettabledetailrekap/', 'Talenta\DashboardController@gettabledetailrekap')->name('talenta.dashboard.gettabledetailrekap');
            Route::get('downloadrekap', 'Talenta\DashboardController@downloadrekap')->name('talenta.dashboard.downloadrekap');
            Route::post('detail_rekap', 'Talenta\DashboardController@detail_rekap')->name('talenta.dashboard.detail_rekap');
        });

        // Dynamic Search
        Route::prefix('dynamic_search')->group(function(){
            Route::get('index', 'Talenta\DynamicSearchController@index')->name('talenta.dynamic_search.index');
            Route::post('datatable', 'Talenta\DynamicSearchController@datatable')->name('talenta.dynamic_search.datatable');
            Route::post('export', 'Talenta\DynamicSearchController@export')->name('talenta.dynamic_search.export');
            Route::post('compare', 'Talenta\DynamicSearchController@compare')->name('talenta.dynamic_search.compare');
            Route::get('compare_pdf', 'Talenta\DynamicSearchController@compare_pdf')->name('talenta.dynamic_search.compare_pdf');
        });

        // filter dynamic
        Route::prefix('filter_dynamic')->group(function(){
            Route::get('index', 'Talenta\DynamicFilterController@index')->name('talenta.filter_dynamic.index');
            Route::post('create', 'Talenta\DynamicFilterController@create')->name('talenta.filter_dynamic.create');
            Route::post('edit', 'Talenta\DynamicFilterController@edit')->name('talenta.filter_dynamic.edit');
            Route::post('store', 'Talenta\DynamicFilterController@store')->name('talenta.filter_dynamic.store');
            Route::post('delete', 'Talenta\DynamicFilterController@delete')->name('talenta.filter_dynamic.delete');
            Route::post('aktif', 'Talenta\DynamicFilterController@aktif')->name('talenta.filter_dynamic.aktif');
            Route::post('is_number', 'Talenta\DynamicFilterController@is_number')->name('talenta.filter_dynamic.is_number');
            Route::get('datatable', 'Talenta\DynamicFilterController@datatable')->name('talenta.filter_dynamic.datatable');
            Route::get('getall', 'Talenta\DynamicFilterController@getAllData')->name('talenta.filter_dynamic.getall');
        });
        
        // register
        Route::prefix('register')->group(function(){
            Route::get('index', 'Talenta\RegisterController@index')->name('talenta.register.index');
            Route::post('create', 'Talenta\RegisterController@create')->name('talenta.register.create');
            Route::post('import', 'Talenta\RegisterController@import')->name('talenta.register.import');
            Route::post('minicv', 'Talenta\RegisterController@minicv')->name('talenta.register.minicv');
            Route::post('log_status', 'Talenta\RegisterController@log_status')->name('talenta.register.log_status');
            Route::post('store_import', 'Talenta\RegisterController@store_import')->name('talenta.register.store_import');
            Route::post('store', 'Talenta\RegisterController@store')->name('talenta.register.store');
            Route::post('delete', 'Talenta\RegisterController@delete')->name('talenta.register.delete');
            Route::post('selected', 'Talenta\RegisterController@selected')->name('talenta.register.selected');
            Route::post('update_talenta', 'Talenta\RegisterController@update_talenta')->name('talenta.register.update_talenta');
            Route::post('cancel_selected', 'Talenta\RegisterController@cancel_selected')->name('talenta.register.cancel_selected');
            Route::get('datatable', 'Talenta\RegisterController@datatable')->name('talenta.register.datatable');
            Route::get('table_log/{id_talenta}', 'Talenta\RegisterController@table_log')->name('talenta.register.table_log');
        });

        // selected
        Route::prefix('selected')->group(function(){
            Route::get('index', 'Talenta\SelectedController@index')->name('talenta.selected.index');
            Route::get('datatable', 'Talenta\SelectedController@datatable')->name('talenta.selected.datatable');
        });

        // Verifikasi Direksi
        Route::prefix('verifikasi_direksi')->group(function(){
            Route::get('index', 'Talenta\VerifikasiDireksiController@index')->name('talenta.verifikasi_direksi.index');
            Route::post('approve', 'Talenta\VerifikasiDireksiController@approve')->name('talenta.verifikasi_direksi.approve');
            Route::post('reject', 'Talenta\VerifikasiDireksiController@reject')->name('talenta.verifikasi_direksi.reject');
            Route::get('datatable', 'Talenta\VerifikasiDireksiController@datatable')->name('talenta.verifikasi_direksi.datatable');
            Route::post('update_talenta', 'Talenta\VerifikasiDireksiController@update_talenta')->name('talenta.verifikasi_direksi.update_talenta');
        });

        // Verifikasi KBUMN
        Route::prefix('verifikasi_kbumn')->group(function(){
            Route::get('index', 'Talenta\VerifikasiKbumnController@index')->name('talenta.verifikasi_kbumn.index');
            Route::post('approve', 'Talenta\VerifikasiKbumnController@approve')->name('talenta.verifikasi_kbumn.approve');
            Route::post('reject', 'Talenta\VerifikasiKbumnController@reject')->name('talenta.verifikasi_kbumn.reject');
            Route::get('datatable', 'Talenta\VerifikasiKbumnController@datatable')->name('talenta.verifikasi_kbumn.datatable');
            Route::post('update_talenta', 'Talenta\VerifikasiKbumnController@update_talenta')->name('talenta.verifikasi_kbumn.update_talenta');
        });

        // Assessment
        Route::prefix('assessment')->group(function(){
            Route::get('index', 'Talenta\AssessmentController@index')->name('talenta.assessment.index');
            Route::post('approve', 'Talenta\AssessmentController@approve')->name('talenta.assessment.approve');
            Route::post('reject', 'Talenta\AssessmentController@reject')->name('talenta.assessment.reject');
            Route::get('datatable', 'Talenta\AssessmentController@datatable')->name('talenta.assessment.datatable');
        });

        // Verifikasi Assessment
        Route::prefix('verifikasi_assessment')->group(function(){
            Route::get('index', 'Talenta\VerifikasiAssessmentController@index')->name('talenta.verifikasi_assessment.index');
            Route::post('approve', 'Talenta\VerifikasiAssessmentController@approve')->name('talenta.verifikasi_assessment.approve');
            Route::post('reject', 'Talenta\VerifikasiAssessmentController@reject')->name('talenta.verifikasi_assessment.reject');
            Route::get('datatable', 'Talenta\VerifikasiAssessmentController@datatable')->name('talenta.verifikasi_assessment.datatable');
            Route::post('update_talenta', 'Talenta\VerifikasiAssessmentController@update_talenta')->name('talenta.verifikasi_assessment.update_talenta');
            Route::post('show', 'Talenta\VerifikasiAssessmentController@show')->name('talenta.verifikasi_assessment.show');
            Route::post('cancelassignment', 'Talenta\VerifikasiAssessmentController@cancel')->name('talenta.verifikasi_assessment.cancelassignment');
            Route::post('update_lembaga_assignment', 'Talenta\VerifikasiAssessmentController@updateLembagaAssignment')->name('talenta.verifikasi_assessment.update_lembaga_assignment');
            Route::get('export', 'Talenta\VerifikasiAssessmentController@export')->name('talenta.verifikasi_assessment.export');
        });

        // Page Assessment oleh assessor
        Route::prefix('assessment_assessor')->group(function(){
            Route::get('index', 'Talenta\AssessmentAssessorController@index')->name('talenta.assessment_assessor.index');
            Route::post('approve', 'Talenta\AssessmentAssessorController@approve')->name('talenta.assessment_assessor.approve');
            Route::post('reject', 'Talenta\AssessmentAssessorController@reject')->name('talenta.assessment_assessor.reject');
            Route::get('datatable', 'Talenta\AssessmentAssessorController@datatable')->name('talenta.assessment_assessor.datatable');
            Route::post('update_talenta', 'Talenta\AssessmentAssessorController@update_talenta')->name('talenta.assessment_assessor.update_talenta');
            Route::post('cv', 'Talenta\AssessmentAssessorController@cv')->name('talenta.assessment_assessor.cv');
        });

        // Nilai Assessmemnt
        Route::prefix('assessment_nilai/{id_talenta}')->group(function(){
            Route::get('index', 'Talenta\AssessmentNilaiController@index')->name('talenta.assessment_nilai.index');
            Route::post('create', 'Talenta\AssessmentNilaiController@create')->name('talenta.assessment_nilai.create');
            Route::post('upload_spa', 'Talenta\AssessmentNilaiController@upload_spa')->name('talenta.assessment_nilai.upload_spa');
            Route::post('upload_short', 'Talenta\AssessmentNilaiController@upload_short')->name('talenta.assessment_nilai.upload_short');
            Route::post('upload_full', 'Talenta\AssessmentNilaiController@upload_full')->name('talenta.assessment_nilai.upload_full');
            Route::post('edit', 'Talenta\AssessmentNilaiController@edit')->name('talenta.assessment_nilai.edit');
            Route::post('store', 'Talenta\AssessmentNilaiController@store')->name('talenta.assessment_nilai.store');
            Route::post('store_upload', 'Talenta\AssessmentNilaiController@store_upload')->name('talenta.assessment_nilai.store_upload');
            Route::post('delete', 'Talenta\AssessmentNilaiController@delete')->name('talenta.assessment_nilai.delete');
            Route::get('datatable', 'Talenta\AssessmentNilaiController@datatable')->name('talenta.assessment_nilai.datatable');
        });

        // Periode
        Route::prefix('periode')->group(function(){
            Route::get('index', 'Talenta\PeriodeRegisterController@index')->name('talenta.periode.index');
            Route::post('create', 'Talenta\PeriodeRegisterController@create')->name('talenta.periode.create');
            Route::post('edit', 'Talenta\PeriodeRegisterController@edit')->name('talenta.periode.edit');
            Route::post('store', 'Talenta\PeriodeRegisterController@store')->name('talenta.periode.store');
            Route::post('delete', 'Talenta\PeriodeRegisterController@delete')->name('talenta.periode.delete');
            Route::get('datatable', 'Talenta\PeriodeRegisterController@datatable')->name('talenta.periode.datatable');
            Route::post('changeaktif', 'Talenta\PeriodeRegisterController@changeaktif')->name('talenta.periode.changeaktif');
        });
    });
    
    Route::prefix('cv')->group(function(){
            // CV Board
            Route::prefix('board')->group(function(){
                Route::get('index', 'CV\BoardController@index')->name('cv.board.index');
                Route::view('/page', 'cv.board.tes');
                Route::post('create', 'CV\BoardController@create')->name('cv.board.create');
                Route::post('import', 'CV\BoardController@import')->name('cv.board.import');
                Route::post('minicv', 'CV\BoardController@minicv')->name('cv.board.minicv');
                Route::post('jabatantalent', 'CV\BoardController@jabatantalent')->name('cv.board.jabatantalent');
                Route::post('editstatus', 'CV\BoardController@editstatus')->name('cv.board.editstatus');
                Route::post('store_import', 'CV\BoardController@store_import')->name('cv.board.store_import');
                Route::post('store', 'CV\BoardController@store')->name('cv.board.store');
                Route::post('store_status', 'CV\BoardController@store_status')->name('cv.board.store_status');
                Route::post('datatalent', 'CV\BoardController@datatalent')->name('cv.board.datatalent');
                Route::post('datajabatantalent', 'CV\BoardController@datajabatantalent')->name('cv.board.datajabatantalent');
                Route::post('datanontalent', 'CV\BoardController@datanontalent')->name('cv.board.datanontalent');
                Route::post('delete', 'CV\BoardController@delete')->name('cv.board.delete');
                Route::get('export/{id}', 'CV\BoardController@export')->name('cv.board.export');
                Route::get('export2/{id}', 'CV\BoardController@export2')->name('cv.board.export2');
                Route::get('template', 'CV\BoardController@template')->name('cv.board.template');
                Route::get('generate', 'CV\BoardController@generate')->name('cv.board.generate');
                Route::get('datatable', 'CV\BoardController@datatable')->name('cv.board.datatable');
                Route::get('fill_persentase', 'CV\BoardController@fill_persentase')->name('cv.board.fill_persentase');
                Route::get('fill_cv', 'CV\BoardController@fill_cv')->name('cv.board.fill_cv');
                Route::get('/checknik', 'CV\BoardController@checknik')->name('cv.board.checknik');
            });

            // CV Biodata
            Route::prefix('biodata/{id_talenta}')->group(function(){
                Route::get('index', 'CV\BiodataController@index')->name('cv.biodata.index');
                Route::post('update', 'CV\BiodataController@update')->name('cv.biodata.update');
            });

            // CV summary
            Route::prefix('summary/{id_talenta}')->group(function(){
                Route::get('index', 'CV\SummaryController@index')->name('cv.summary.index');
                Route::post('update', 'CV\SummaryController@update')->name('cv.summary.update');
            });

            // CV summary
            Route::prefix('nilai/{id_talenta}')->group(function(){
                Route::get('index', 'CV\NilaiPribadiController@index')->name('cv.nilai.index');
                Route::post('update', 'CV\NilaiPribadiController@update')->name('cv.nilai.update');
            });

            // CV interest
            Route::prefix('interest/{id_talenta}')->group(function(){
                Route::get('index', 'CV\InterestController@index')->name('cv.interest.index');
                Route::post('update', 'CV\InterestController@update')->name('cv.interest.update');
            });

            // CV keahlian
            Route::prefix('keahlian/{id_talenta}')->group(function(){
                Route::get('index', 'CV\KeahlianController@index')->name('cv.keahlian.index');
                Route::post('update', 'CV\KeahlianController@update')->name('cv.keahlian.update');
            });

            // CV aspirasi
            Route::prefix('aspirasi/{id_talenta}')->group(function(){
                Route::get('index', 'CV\AspirasiController@index')->name('cv.aspirasi.index');
                Route::post('update', 'CV\AspirasiController@update')->name('cv.aspirasi.update');
            });

            // CV kelas
            Route::prefix('kelas/{id_talenta}')->group(function(){
                Route::get('index', 'CV\AspirasiKelasController@index')->name('cv.kelas.index');
                Route::post('update', 'CV\AspirasiKelasController@update')->name('cv.kelas.update');
            });

            // // CV cluster
            Route::prefix('cluster/{id_talenta}')->group(function(){
                Route::get('index', 'CV\AspirasiClusterController@index')->name('cv.cluster.index');
                Route::post('update', 'CV\AspirasiClusterController@update')->name('cv.cluster.update');
            });

            // CV pendidikan
            Route::prefix('pendidikan/{id_talenta}')->group(function(){
                Route::get('index', 'CV\PendidikanController@index')->name('cv.pendidikan.index');
                Route::post('create', 'CV\PendidikanController@create')->name('cv.pendidikan.create');
                Route::post('edit', 'CV\PendidikanController@edit')->name('cv.pendidikan.edit');
                Route::post('store', 'CV\PendidikanController@store')->name('cv.pendidikan.store');
                Route::post('delete', 'CV\PendidikanController@delete')->name('cv.pendidikan.delete');
                Route::get('datatable', 'CV\PendidikanController@datatable')->name('cv.pendidikan.datatable');
            });

            // CV pelatihan
            Route::prefix('pelatihan/{id_talenta}')->group(function(){
                Route::get('index', 'CV\PelatihanController@index')->name('cv.pelatihan.index');
                Route::post('create', 'CV\PelatihanController@create')->name('cv.pelatihan.create');
                Route::post('edit', 'CV\PelatihanController@edit')->name('cv.pelatihan.edit');
                Route::post('store', 'CV\PelatihanController@store')->name('cv.pelatihan.store');
                Route::post('delete', 'CV\PelatihanController@delete')->name('cv.pelatihan.delete');
                Route::get('datatable', 'CV\PelatihanController@datatable')->name('cv.pelatihan.datatable');
            });

            // CV Keluarga
            Route::prefix('keluarga/{id_talenta}')->group(function(){
                Route::get('index', 'CV\KeluargaController@index')->name('cv.keluarga.index');
                Route::post('create', 'CV\KeluargaController@create')->name('cv.keluarga.create');
                Route::post('edit', 'CV\KeluargaController@edit')->name('cv.keluarga.edit');
                Route::post('delete', 'CV\KeluargaController@delete')->name('cv.keluarga.delete');
                Route::get('datatable', 'CV\KeluargaController@datatable')->name('cv.keluarga.datatable');
                Route::post('store', 'CV\KeluargaController@store')->name('cv.keluarga.store');

                Route::post('create_anak', 'CV\KeluargaController@create_anak')->name('cv.keluarga.create_anak');
                Route::post('delete_anak', 'CV\KeluargaController@delete_anak')->name('cv.keluarga.delete_anak');
                Route::post('edit_anak', 'CV\KeluargaController@edit_anak')->name('cv.keluarga.edit_anak');
                Route::get('datatable_anak', 'CV\KeluargaController@datatable_anak')->name('cv.keluarga.datatable_anak');
                Route::post('store_anak', 'CV\KeluargaController@store_anak')->name('cv.keluarga.store_anak');
                Route::post('tidak_memiliki', 'CV\KeluargaController@tidak_memiliki')->name('cv.keluarga.tidak_memiliki');
            });

            // CV penghargaan
            Route::prefix('penghargaan/{id_talenta}')->group(function(){
                Route::get('index', 'CV\PenghargaanController@index')->name('cv.penghargaan.index');
                Route::post('create', 'CV\PenghargaanController@create')->name('cv.penghargaan.create');
                Route::post('edit', 'CV\PenghargaanController@edit')->name('cv.penghargaan.edit');
                Route::post('store', 'CV\PenghargaanController@store')->name('cv.penghargaan.store');
                Route::post('delete', 'CV\PenghargaanController@delete')->name('cv.penghargaan.delete');
                Route::get('datatable', 'CV\PenghargaanController@datatable')->name('cv.penghargaan.datatable');
                Route::post('tidak_memiliki', 'CV\PenghargaanController@tidak_memiliki')->name('cv.penghargaan.tidak_memiliki');
            });
            
            // CV karya ilmiah
            Route::prefix('karya_ilmiah/{id_talenta}')->group(function(){
                Route::get('index', 'CV\KaryaIlmiahController@index')->name('cv.karya_ilmiah.index');
                Route::post('create', 'CV\KaryaIlmiahController@create')->name('cv.karya_ilmiah.create');
                Route::post('edit', 'CV\KaryaIlmiahController@edit')->name('cv.karya_ilmiah.edit');
                Route::post('store', 'CV\KaryaIlmiahController@store')->name('cv.karya_ilmiah.store');
                Route::post('delete', 'CV\KaryaIlmiahController@delete')->name('cv.karya_ilmiah.delete');
                Route::get('datatable', 'CV\KaryaIlmiahController@datatable')->name('cv.karya_ilmiah.datatable');
                Route::post('tidak_memiliki', 'CV\KaryaIlmiahController@tidak_memiliki')->name('cv.karya_ilmiah.tidak_memiliki');
            });

            // CV referensi rekomendasi cv
            Route::prefix('referensi_cv/{id_talenta}')->group(function(){
                Route::get('index', 'CV\ReferensiCVController@index')->name('cv.referensi_cv.index');
                Route::post('create', 'CV\ReferensiCVController@create')->name('cv.referensi_cv.create');
                Route::post('edit', 'CV\ReferensiCVController@edit')->name('cv.referensi_cv.edit');
                Route::post('store', 'CV\ReferensiCVController@store')->name('cv.referensi_cv.store');
                Route::post('delete', 'CV\ReferensiCVController@delete')->name('cv.referensi_cv.delete');
                Route::get('datatable', 'CV\ReferensiCVController@datatable')->name('cv.referensi_cv.datatable');
                Route::post('tidak_memiliki', 'CV\ReferensiCVController@tidak_memiliki')->name('cv.referensi_cv.tidak_memiliki');
            });

            // CV riwayat organisasi
            Route::prefix('riwayat_organisasi/{id_talenta}')->group(function(){
                Route::get('index', 'CV\RiwayatOrganisasiController@index')->name('cv.riwayat_organisasi.index');
                Route::post('create', 'CV\RiwayatOrganisasiController@create')->name('cv.riwayat_organisasi.create');
                Route::post('edit', 'CV\RiwayatOrganisasiController@edit')->name('cv.riwayat_organisasi.edit');
                Route::post('store', 'CV\RiwayatOrganisasiController@store')->name('cv.riwayat_organisasi.store');
                Route::post('delete', 'CV\RiwayatOrganisasiController@delete')->name('cv.riwayat_organisasi.delete');
                Route::get('datatable', 'CV\RiwayatOrganisasiController@datatable')->name('cv.riwayat_organisasi.datatable');
                Route::post('tidak_memiliki', 'CV\RiwayatOrganisasiController@tidak_memiliki')->name('cv.riwayat_organisasi.tidak_memiliki');
                
                
                Route::post('create_nonformal', 'CV\RiwayatOrganisasiController@create_nonformal')->name('cv.riwayat_organisasi.create_nonformal');
                Route::post('edit_nonformal', 'CV\RiwayatOrganisasiController@edit_nonformal')->name('cv.riwayat_organisasi.edit_nonformal');
                Route::get('datatable_nonformal', 'CV\RiwayatOrganisasiController@datatable_nonformal')->name('cv.riwayat_organisasi.datatable_nonformal');
                Route::post('tidak_memiliki_nonformal', 'CV\RiwayatOrganisasiController@tidak_memiliki_nonformal')->name('cv.riwayat_organisasi.tidak_memiliki_nonformal');
            });

            // CV pengalaman lain
            Route::prefix('pengalaman_lain/{id_talenta}')->group(function(){
                Route::get('index', 'CV\PengalamanLainController@index')->name('cv.pengalaman_lain.index');
                Route::post('create', 'CV\PengalamanLainController@create')->name('cv.pengalaman_lain.create');
                Route::post('edit', 'CV\PengalamanLainController@edit')->name('cv.pengalaman_lain.edit');
                Route::post('store', 'CV\PengalamanLainController@store')->name('cv.pengalaman_lain.store');
                Route::post('delete', 'CV\PengalamanLainController@delete')->name('cv.pengalaman_lain.delete');
                Route::get('datatable', 'CV\PengalamanLainController@datatable')->name('cv.pengalaman_lain.datatable');
                Route::post('tidak_memiliki', 'CV\PengalamanLainController@tidak_memiliki')->name('cv.pengalaman_lain.tidak_memiliki');
            });

            // CV Riwayat Jabatan
            Route::prefix('riwayat_jabatan/{id_talenta}')->group(function(){
                Route::get('index', 'CV\RiwayatJabatanController@index')->name('cv.riwayat_jabatan.index');
                Route::post('create', 'CV\RiwayatJabatanController@create')->name('cv.riwayat_jabatan.create');
                Route::post('edit', 'CV\RiwayatJabatanController@edit')->name('cv.riwayat_jabatan.edit');
                Route::post('store', 'CV\RiwayatJabatanController@store')->name('cv.riwayat_jabatan.store');
                Route::post('delete', 'CV\RiwayatJabatanController@delete')->name('cv.riwayat_jabatan.delete');
                Route::get('datatable', 'CV\RiwayatJabatanController@datatable')->name('cv.riwayat_jabatan.datatable');
            });

            // CV Riwayat Jabatan Lain
            Route::prefix('riwayat_jabatan_lain/{id_talenta}')->group(function(){
                Route::get('index', 'CV\RiwayatJabatanLainController@index')->name('cv.riwayat_jabatan_lain.index');
                Route::post('create', 'CV\RiwayatJabatanLainController@create')->name('cv.riwayat_jabatan_lain.create');
                Route::post('edit', 'CV\RiwayatJabatanLainController@edit')->name('cv.riwayat_jabatan_lain.edit');
                Route::post('store', 'CV\RiwayatJabatanLainController@store')->name('cv.riwayat_jabatan_lain.store');
                Route::post('delete', 'CV\RiwayatJabatanLainController@delete')->name('cv.riwayat_jabatan_lain.delete');
                Route::get('datatable', 'CV\RiwayatJabatanLainController@datatable')->name('cv.riwayat_jabatan_lain.datatable');
            });

            // CV SPT Tahunan
            Route::prefix('pajak/{id_talenta}')->group(function(){
                Route::get('index', 'CV\PajakController@index')->name('cv.pajak.index');
                Route::post('create', 'CV\PajakController@create')->name('cv.pajak.create');
                Route::post('edit', 'CV\PajakController@edit')->name('cv.pajak.edit');
                Route::post('store', 'CV\PajakController@store')->name('cv.pajak.store');
                Route::post('delete', 'CV\PajakController@delete')->name('cv.pajak.delete');
                Route::get('datatable', 'CV\PajakController@datatable')->name('cv.pajak.datatable');
            });

            // CV LHKPN
            Route::prefix('lhkpn/{id_talenta}')->group(function(){
                Route::get('index', 'CV\LhkpnController@index')->name('cv.lhkpn.index');
                Route::post('create', 'CV\LhkpnController@create')->name('cv.lhkpn.create');
                Route::post('edit', 'CV\LhkpnController@edit')->name('cv.lhkpn.edit');
                Route::post('store', 'CV\LhkpnController@store')->name('cv.lhkpn.store');
                Route::post('delete', 'CV\LhkpnController@delete')->name('cv.lhkpn.delete');
                Route::get('datatable', 'CV\LhkpnController@datatable')->name('cv.lhkpn.datatable');
            });

            // CV TANI
            Route::prefix('tani/{id_talenta}')->group(function(){
                Route::get('index', 'CV\TaniController@index')->name('cv.tani.index');
                Route::post('create', 'CV\TaniController@create')->name('cv.tani.create');
                Route::post('edit', 'CV\TaniController@edit')->name('cv.tani.edit');
                Route::post('store', 'CV\TaniController@store')->name('cv.tani.store');
                Route::post('delete', 'CV\TaniController@delete')->name('cv.tani.delete');
                Route::get('datatable', 'CV\TaniController@datatable')->name('cv.tani.datatable');
            });

            // CV Kesehatan
            Route::prefix('kesehatan/{id_talenta}')->group(function(){
                Route::get('index', 'CV\KesehatanController@index')->name('cv.kesehatan.index');
                Route::post('create', 'CV\KesehatanController@create')->name('cv.kesehatan.create');
                Route::post('edit', 'CV\KesehatanController@edit')->name('cv.kesehatan.edit');
                Route::post('store', 'CV\KesehatanController@store')->name('cv.kesehatan.store');
                Route::post('delete', 'CV\KesehatanController@delete')->name('cv.kesehatan.delete');
                Route::get('datatable', 'CV\KesehatanController@datatable')->name('cv.kesehatan.datatable');
            });

            // CV Sosial Media
            Route::prefix('social/{id_talenta}')->group(function(){
                Route::get('index', 'CV\SocialController@index')->name('cv.social.index');
                Route::post('create', 'CV\SocialController@create')->name('cv.social.create');
                Route::post('edit', 'CV\SocialController@edit')->name('cv.social.edit');
                Route::post('store', 'CV\SocialController@store')->name('cv.social.store');
                Route::post('delete', 'CV\SocialController@delete')->name('cv.social.delete');
                Route::get('datatable', 'CV\SocialController@datatable')->name('cv.social.datatable');
            });

            // CV Hasil Assessment
            Route::prefix('assessment_upload/{id_talenta}')->group(function(){
                Route::get('index', 'CV\AssessmentUploadController@index')->name('cv.assessment_upload.index');
                Route::post('create', 'CV\AssessmentUploadController@create')->name('cv.assessment_upload.create');
                Route::post('edit', 'CV\AssessmentUploadController@edit')->name('cv.assessment_upload.edit');
                Route::post('store', 'CV\AssessmentUploadController@store')->name('cv.assessment_upload.store');
                Route::post('delete', 'CV\AssessmentUploadController@delete')->name('cv.assessment_upload.delete');
                Route::get('datatable', 'CV\AssessmentUploadController@datatable')->name('cv.assessment_upload.datatable');
            });
        });

    // Fetch Route
    Route::prefix('fetch')->group(function(){
        Route::prefix('referensi')->group(function(){
            Route::post('/getkota', 'Fetch\Referensi\ReferensiFetchController@getKota');
            Route::post('/getallprovinsi', 'Fetch\Referensi\ReferensiFetchController@getAllProvinsi');
            Route::post('/getdatauniversitas', 'Fetch\Referensi\ReferensiFetchController@getDataUniversitas');
            Route::post('/gettalenta', 'Fetch\Referensi\ReferensiFetchController@getTalenta');
            Route::post('/getdynamicsearch', 'Fetch\Referensi\ReferensiFetchController@getDynamicSearch');
            Route::post('/getdynamicsearchmenu', 'Fetch\Referensi\ReferensiFetchController@getDynamicSearchMenu');
            Route::post('/getdynamicsearchsubmenu', 'Fetch\Referensi\ReferensiFetchController@getDynamicSearchSubMenu');
            Route::post('/getdynamicsearchbyid', 'Fetch\Referensi\ReferensiFetchController@getDynamicSearchById');
            Route::post('/getdynamicsearchoperator', 'Fetch\Referensi\ReferensiFetchController@getDynamicSearchOperator');
            Route::post('/getdynamicsearchbumn', 'Fetch\Referensi\ReferensiFetchController@getDynamicSearchBumn');
        });
    });

    Route::prefix('administrasi')->group(function(){

        Route::prefix('bumn')->group(function(){
            Route::get('index', 'Administrasi\BumnController@index')->name('administrasi.bumn.index');
            Route::get('/tambah', 'Administrasi\BumnController@tambah')->name('administrasi.bumn.tambah');
            Route::get('{bumn}/edittambah', 'Administrasi\BumnController@edittambah');
            Route::put('{bumn}', 'Administrasi\BumnController@updatetambah');
            //Route::get('/tambah2', 'Administrasi\BumnController@tambah2')->name('administrasi.bumn.tambah2');
            Route::get('/tambah3', 'Administrasi\BumnController@tambah3')->name('administrasi.bumn.tambah3');
            Route::post('storetambah', 'Administrasi\BumnController@storetambah')->name('administrasi.bumn.storetambah');
            Route::post('delete', 'Administrasi\BumnController@delete')->name('administrasi.bumn.delete');
            Route::post('datatable', 'Administrasi\BumnController@datatable')->name('administrasi.bumn.datatable');
            Route::post('/getjenissk', 'Administrasi\BumnController@getjenissk');
            Route::post('detail', 'Administrasi\BumnController@detail')->name('administrasi.bumn.detail');
            Route::post('detailjo', 'Administrasi\BumnController@detailjo')->name('administrasi.bumn.detailjo');
            //untuk pengangkatan
            Route::post('createangkat', 'Administrasi\BumnController@createangkat')->name('administrasi.bumn.createangkat');
            Route::post('createorang', 'Administrasi\BumnController@createorang')->name('administrasi.bumn.createorang');
            Route::post('createangkatlagi', 'Administrasi\BumnController@createangkatlagi')->name('administrasi.bumn.createangkatlagi');
            Route::post('editangkat', 'Administrasi\BumnController@editangkat')->name('administrasi.bumn.editangkat');
            Route::post('editangkatlagi', 'Administrasi\BumnController@editangkatlagi')->name('administrasi.bumn.editangkatlagi');
            Route::post('storeangkat', 'Administrasi\BumnController@storeangkat')->name('administrasi.bumn.storeangkat');
            Route::post('storeangkatlagi', 'Administrasi\BumnController@storeangkatlagi')->name('administrasi.bumn.storeangkatlagi');
            Route::post('neworang', 'Administrasi\BumnController@neworang')->name('administrasi.bumn.neworang');
            Route::post('deleteangkat', 'Administrasi\BumnController@deleteangkat')->name('administrasi.bumn.deleteangkat');
            Route::get('datatableangkat', 'Administrasi\BumnController@datatableangkat')->name('administrasi.bumn.datatableangkat');
            Route::get('datatablesumangkat', 'Administrasi\BumnController@datatablesumangkat')->name('administrasi.bumn.datatablesumangkat');
            //untuk pemberhentian
            Route::post('createhenti', 'Administrasi\BumnController@createhenti')->name('administrasi.bumn.createhenti');
            Route::post('edithenti', 'Administrasi\BumnController@edithenti')->name('administrasi.bumn.edithenti');
            Route::post('storehenti', 'Administrasi\BumnController@storehenti')->name('administrasi.bumn.storehenti');
            Route::post('deletehenti', 'Administrasi\BumnController@deletehenti')->name('administrasi.bumn.deletehenti');
            Route::get('datatablehenti', 'Administrasi\BumnController@datatablehenti')->name('administrasi.bumn.datatablehenti');
            Route::get('datatablesumhenti', 'Administrasi\BumnController@datatablesumhenti')->name('administrasi.bumn.datatablesumhenti');
            Route::post('/getnamajabat', 'Administrasi\BumnController@getnamajabat');
            Route::post('/getjabatan', 'Administrasi\BumnController@getjabatan');
            //untuk nomenklatur
            Route::post('createklatur', 'Administrasi\BumnController@createklatur')->name('administrasi.bumn.createklatur');
            Route::post('editklatur', 'Administrasi\BumnController@editklatur')->name('administrasi.bumn.editklatur');
            Route::post('storeklatur', 'Administrasi\BumnController@storeklatur')->name('administrasi.bumn.storeklatur');
            Route::post('deleteklatur', 'Administrasi\BumnController@deleteklatur')->name('administrasi.bumn.deleteklatur');
            Route::get('datatableklatur', 'Administrasi\BumnController@datatableklatur')->name('administrasi.bumn.datatableklatur');
            Route::get('datatablesumklatur', 'Administrasi\BumnController@datatablesumklatur')->name('administrasi.bumn.datatablesumklatur');
            //untuk PLT
            Route::post('createplt', 'Administrasi\BumnController@createplt')->name('administrasi.bumn.createplt');
            Route::post('editplt', 'Administrasi\BumnController@editplt')->name('administrasi.bumn.editplt');
            Route::post('storeplt', 'Administrasi\BumnController@storeplt')->name('administrasi.bumn.storeplt');
            Route::post('deleteplt', 'Administrasi\BumnController@deleteplt')->name('administrasi.bumn.deleteplt');
            Route::get('datatableplt', 'Administrasi\BumnController@datatableplt')->name('administrasi.bumn.datatableplt');
            Route::get('datatablesumplt', 'Administrasi\BumnController@datatablesumplt')->name('administrasi.bumn.datatablesumplt');
            //untuk ALT
            Route::post('createalt', 'Administrasi\BumnController@createalt')->name('administrasi.bumn.createalt');
            Route::post('editalt', 'Administrasi\BumnController@editalt')->name('administrasi.bumn.editalt');
            Route::post('storealt', 'Administrasi\BumnController@storealt')->name('administrasi.bumn.storealt');
            Route::post('deletealt', 'Administrasi\BumnController@deletealt')->name('administrasi.bumn.deletealt');
            Route::get('datatablealt', 'Administrasi\BumnController@datatablealt')->name('administrasi.bumn.datatablealt');
            Route::get('datatablesumalt', 'Administrasi\BumnController@datatablesumalt')->name('administrasi.bumn.datatablesumalt');
            //untuk Independen
            Route::post('createindependen', 'Administrasi\BumnController@createindependen')->name('administrasi.bumn.createindependen');
            Route::post('editindependen', 'Administrasi\BumnController@editindependen')->name('administrasi.bumn.editindependen');
            Route::post('storeindependen', 'Administrasi\BumnController@storeindependen')->name('administrasi.bumn.storeindependen');
            Route::post('deleteindependen', 'Administrasi\BumnController@deleteindependen')->name('administrasi.bumn.deleteindependen');
            Route::get('datatableindependen', 'Administrasi\BumnController@datatableindependen')->name('administrasi.bumn.datatableindependen');
            Route::get('datatablesumindependen', 'Administrasi\BumnController@datatablesumindependen')->name('administrasi.bumn.datatablesumindependen');
            Route::post('savetambah2', 'Administrasi\BumnController@savetambah2')->name('administrasi.bumn.savetambah2');
            Route::post('/gettgljabat', 'Administrasi\BumnController@gettgljabat');
            Route::get('/checknik', 'Administrasi\BumnController@checknik')->name('administrasi.bumn.checknik');
            Route::get('/checknpwp', 'Administrasi\BumnController@checknpwp')->name('administrasi.bumn.checknpwp');
        });

        Route::prefix('anak')->group(function(){
            Route::get('index', 'Administrasi\AnakController@index')->name('administrasi.anak.index');
            Route::get('/tambah', 'Administrasi\AnakController@tambah')->name('administrasi.anak.tambah');
            Route::get('{anak}/edittambah', 'Administrasi\AnakController@edittambah');
            Route::put('{anak}', 'Administrasi\AnakController@updatetambah');
            Route::get('/tambah2', 'Administrasi\AnakController@tambah2')->name('administrasi.anak.tambah2');
            Route::get('/tambah3', 'Administrasi\AnakController@tambah3')->name('administrasi.anak.tambah3');
            Route::post('storetambah', 'Administrasi\AnakController@storetambah')->name('administrasi.anak.storetambah');
            Route::post('delete', 'Administrasi\AnakController@delete')->name('administrasi.anak.delete');
            Route::post('datatable', 'Administrasi\AnakController@datatable')->name('administrasi.anak.datatable');
            Route::post('/getanak', 'Administrasi\AnakController@getanak');
            Route::post('detail', 'Administrasi\AnakController@detail')->name('administrasi.anak.detail');
            //untuk pengangkatan
            Route::post('createangkat', 'Administrasi\AnakController@createangkat')->name('administrasi.anak.createangkat');
            Route::post('createorang', 'Administrasi\AnakController@createorang')->name('administrasi.anak.createorang');
            Route::post('createangkatlagi', 'Administrasi\AnakController@createangkatlagi')->name('administrasi.anak.createangkatlagi');
            Route::post('editangkat', 'Administrasi\AnakController@editangkat')->name('administrasi.anak.editangkat');
            Route::post('editangkatlagi', 'Administrasi\AnakController@editangkatlagi')->name('administrasi.anak.editangkatlagi');
            Route::post('storeangkat', 'Administrasi\AnakController@storeangkat')->name('administrasi.anak.storeangkat');
            Route::post('storeangkatlagi', 'Administrasi\AnakController@storeangkatlagi')->name('administrasi.anak.storeangkatlagi');
            Route::post('neworang', 'Administrasi\AnakController@neworang')->name('administrasi.anak.neworang');
            Route::post('deleteangkat', 'Administrasi\AnakController@deleteangkat')->name('administrasi.anak.deleteangkat');
            Route::get('datatableangkat', 'Administrasi\AnakController@datatableangkat')->name('administrasi.anak.datatableangkat');
            Route::get('datatablesumangkat', 'Administrasi\AnakController@datatablesumangkat')->name('administrasi.anak.datatablesumangkat');
            //untuk pemberhentian
            Route::post('createhenti', 'Administrasi\AnakController@createhenti')->name('administrasi.anak.createhenti');
            Route::post('edithenti', 'Administrasi\AnakController@edithenti')->name('administrasi.anak.edithenti');
            Route::post('storehenti', 'Administrasi\AnakController@storehenti')->name('administrasi.anak.storehenti');
            Route::post('deletehenti', 'Administrasi\AnakController@deletehenti')->name('administrasi.anak.deletehenti');
            Route::get('datatablehenti', 'Administrasi\AnakController@datatablehenti')->name('administrasi.anak.datatablehenti');
            Route::get('datatablesumhenti', 'Administrasi\AnakController@datatablesumhenti')->name('administrasi.anak.datatablesumhenti');
            Route::post('/getnamajabat', 'Administrasi\AnakController@getnamajabat');
            Route::post('/getjabatan', 'Administrasi\AnakController@getjabatan');
            //untuk nomenklatur
            Route::post('createklatur', 'Administrasi\AnakController@createklatur')->name('administrasi.anak.createklatur');
            Route::post('editklatur', 'Administrasi\AnakController@editklatur')->name('administrasi.anak.editklatur');
            Route::post('storeklatur', 'Administrasi\AnakController@storeklatur')->name('administrasi.anak.storeklatur');
            Route::post('deleteklatur', 'Administrasi\AnakController@deleteklatur')->name('administrasi.anak.deleteklatur');
            Route::get('datatableklatur', 'Administrasi\AnakController@datatableklatur')->name('administrasi.anak.datatableklatur');
            Route::get('datatablesumklatur', 'Administrasi\AnakController@datatablesumklatur')->name('administrasi.anak.datatablesumklatur');
            //untuk PLT
            Route::post('createplt', 'Administrasi\AnakController@createplt')->name('administrasi.anak.createplt');
            Route::post('editplt', 'Administrasi\AnakController@editplt')->name('administrasi.anak.editplt');
            Route::post('storeplt', 'Administrasi\AnakController@storeplt')->name('administrasi.anak.storeplt');
            Route::post('deleteplt', 'Administrasi\AnakController@deleteplt')->name('administrasi.anak.deleteplt');
            Route::get('datatableplt', 'Administrasi\AnakController@datatableplt')->name('administrasi.anak.datatableplt');
            Route::get('datatablesumplt', 'Administrasi\AnakController@datatablesumplt')->name('administrasi.anak.datatablesumplt');
            //untuk ALT
            Route::post('createalt', 'Administrasi\AnakController@createalt')->name('administrasi.anak.createalt');
            Route::post('editalt', 'Administrasi\AnakController@editalt')->name('administrasi.anak.editalt');
            Route::post('storealt', 'Administrasi\AnakController@storealt')->name('administrasi.anak.storealt');
            Route::post('deletealt', 'Administrasi\AnakController@deletealt')->name('administrasi.anak.deletealt');
            Route::get('datatablealt', 'Administrasi\AnakController@datatablealt')->name('administrasi.anak.datatablealt');
            Route::get('datatablesumalt', 'Administrasi\AnakController@datatablesumalt')->name('administrasi.anak.datatablesumalt');
            //untuk Independen
            Route::post('createindependen', 'Administrasi\AnakController@createindependen')->name('administrasi.anak.createindependen');
            Route::post('editindependen', 'Administrasi\AnakController@editindependen')->name('administrasi.anak.editindependen');
            Route::post('storeindependen', 'Administrasi\AnakController@storeindependen')->name('administrasi.anak.storeindependen');
            Route::post('deleteindependen', 'Administrasi\AnakController@deleteindependen')->name('administrasi.anak.deleteindependen');
            Route::get('datatableindependen', 'Administrasi\AnakController@datatableindependen')->name('administrasi.anak.datatableindependen');
            Route::get('datatablesumindependen', 'Administrasi\AnakController@datatablesumindependen')->name('administrasi.anak.datatablesumindependen');
            Route::post('savetambah2', 'Administrasi\AnakController@savetambah2')->name('administrasi.anak.savetambah2');
            Route::post('/gettgljabat', 'Administrasi\AnakController@gettgljabat');
        });

        Route::prefix('kelengkapansk')->group(function(){
            Route::get('index', 'Administrasi\KelengkapanskController@index')->name('administrasi.kelengkapansk.index');
            Route::post('create', 'Administrasi\KelengkapanskController@create')->name('administrasi.kelengkapansk.create');
            Route::post('edit', 'Administrasi\KelengkapanskController@edit')->name('administrasi.kelengkapansk.edit');
            Route::get('{kelengkapansk}/editlengkapsk', 'Administrasi\KelengkapanskController@editlengkapsk');
            Route::post('store', 'Administrasi\KelengkapanskController@store')->name('administrasi.kelengkapansk.store');
            Route::post('update', 'Administrasi\KelengkapanskController@update')->name('administrasi.kelengkapansk.update');
            Route::post('delete', 'Administrasi\KelengkapanskController@delete')->name('administrasi.kelengkapansk.delete');
            Route::post('datatable', 'Administrasi\KelengkapanskController@datatable')->name('administrasi.kelengkapansk.datatable');
            Route::post('store-asal-instansi', 'Administrasi\KelengkapanskController@storeAsalInstansi')->name('administrasi.kelengkapansk.store-asal-instansi');
            Route::post('store-data-assesmen', 'Administrasi\KelengkapanskController@storeDataAssesmen')->name('administrasi.kelengkapansk.store-data-assesmen');
            Route::post('store-data-penghasilan', 'Administrasi\KelengkapanskController@storeDataPenghasilan')->name('administrasi.kelengkapansk.store-data-penghasilan');
            Route::post('store-data-filekelengkapan', 'Administrasi\KelengkapanskController@storeDataFilekelengkapan')->name('administrasi.kelengkapansk.store-data-filekelengkapan');
            Route::post('store-prosentase', 'Administrasi\KelengkapanskController@storeProsentase')->name('administrasi.kelengkapansk.store-prosentase');

            // to do
            Route::delete('delete-penghasilan', 'Administrasi\KelengkapanskController@deletePenghasilan')->name('administrasi.kelengkapansk.delete-penghasilan');
            Route::delete('delete-filependukung', 'Administrasi\KelengkapanskController@deleteFilependukung')->name('administrasi.kelengkapansk.delete-filependukung');

            //untuk instansi
            Route::post('createinstansi', 'Administrasi\KelengkapanskController@createinstansi')->name('administrasi.kelengkapansk.createinstansi');
            Route::post('editinstansi', 'Administrasi\KelengkapanskController@editinstansi')->name('administrasi.kelengkapansk.editinstansi');
            Route::post('storeinstansi', 'Administrasi\KelengkapanskController@storeinstansi')->name('administrasi.kelengkapansk.storeinstansi');
            Route::post('deleteinstansi', 'Administrasi\KelengkapanskController@deleteinstansi')->name('administrasi.kelengkapansk.deleteinstansi');
            Route::get('datatableinstansi', 'Administrasi\KelengkapanskController@datatableinstansi')->name('administrasi.kelengkapansk.datatableinstansi');
            Route::get('datatablesuminstansi', 'Administrasi\KelengkapanskController@datatablesuminstansi')->name('administrasi.kelengkapansk.datatablesuminstansi');
            Route::post('/getasalinstansi', 'Administrasi\KelengkapanskController@getasalinstansi');

             //untuk assesmen
            Route::post('createassesmen', 'Administrasi\KelengkapanskController@createassesmen')->name('administrasi.kelengkapansk.createassesmen');
            Route::post('editassesmen', 'Administrasi\KelengkapanskController@editassesmen')->name('administrasi.kelengkapansk.editassesmen');
            Route::post('storeassesmen', 'Administrasi\KelengkapanskController@storeassesmen')->name('administrasi.kelengkapansk.storeassesmen');
            Route::post('deleteassesmen', 'Administrasi\KelengkapanskController@deleteassesmen')->name('administrasi.kelengkapansk.deleteassesmen');
            Route::get('datatableassesmen', 'Administrasi\KelengkapanskController@datatableassesmen')->name('administrasi.kelengkapansk.datatableassesmen');
            Route::get('datatablesumassesmen', 'Administrasi\KelengkapanskController@datatablesumassesmen')->name('administrasi.kelengkapansk.datatablesumassesmen');

            //untuk penghasilan
            Route::post('createpenghasilan', 'Administrasi\KelengkapanskController@createpenghasilan')->name('administrasi.kelengkapansk.createpenghasilan');
            Route::post('editpenghasilan', 'Administrasi\KelengkapanskController@editpenghasilan')->name('administrasi.kelengkapansk.editpenghasilan');
            Route::post('storepenghasilan', 'Administrasi\KelengkapanskController@storepenghasilan')->name('administrasi.kelengkapansk.storepenghasilan');
            Route::post('deletepenghasilan', 'Administrasi\KelengkapanskController@deletepenghasilan')->name('administrasi.kelengkapansk.deletepenghasilan');
            Route::get('datatablepenghasilan', 'Administrasi\KelengkapanskController@datatablepenghasilan')->name('administrasi.kelengkapansk.datatablepenghasilan');
            Route::get('datatablesumpenghasilan', 'Administrasi\KelengkapanskController@datatablesumpenghasilan')->name('administrasi.kelengkapansk.datatablesumpenghasilan');

            //untuk kelengkapan
            Route::post('createkelengkapan', 'Administrasi\KelengkapanskController@createkelengkapan')->name('administrasi.kelengkapansk.createkelengkapan');
            Route::post('editkelengkapan', 'Administrasi\KelengkapanskController@editkelengkapan')->name('administrasi.kelengkapansk.editkelengkapan');
            Route::post('storekelengkapan', 'Administrasi\KelengkapanskController@storekelengkapan')->name('administrasi.kelengkapansk.storekelengkapan');
            Route::post('deletekelengkapan', 'Administrasi\KelengkapanskController@deletekelengkapan')->name('administrasi.kelengkapansk.deletekelengkapan');
            Route::get('datatablekelengkapan', 'Administrasi\KelengkapanskController@datatablekelengkapan')->name('administrasi.kelengkapansk.datatablekelengkapan');
            Route::get('datatablesumkelengkapan', 'Administrasi\KelengkapanskController@datatablesumkelengkapan')->name('administrasi.kelengkapansk.datatablesumkelengkapan');

            Route::post('savetambah2', 'Administrasi\KelengkapanskController@savetambah2')->name('administrasi.kelengkapansk.savetambah2');
        });

        Route::prefix('pejabatbaru')->group(function(){
            Route::get('index', 'Administrasi\PejabatBaruController@index')->name('administrasi.pejabatbaru.index');
            Route::get('datatable', 'Administrasi\PejabatBaruController@datatable')->name('administrasi.pejabatbaru.datatable');
        });

        Route::prefix('monitoring')->group(function(){

            Route::prefix('pejabat')->group(function(){
                Route::get('index', 'Administrasi\MonitoringPejabatController@index')->name('administrasi.monitoring.pejabat.index');
                Route::post('datatable', 'Administrasi\MonitoringPejabatController@datatable')->name('administrasi.monitoring.pejabat.datatable');
                Route::get('export', 'Administrasi\MonitoringPejabatController@export')->name('administrasi.monitoring.pejabat.export');
            });

            Route::prefix('pengisiansk')->group(function(){
                Route::get('index', 'Administrasi\PengisianskController@index')->name('administrasi.monitoring.pengisiansk.index');
                Route::post('datatable', 'Administrasi\PengisianskController@datatable')->name('administrasi.monitoring.pengisiansk.datatable');
                Route::post('datatablegrup', 'Administrasi\PengisianskController@datatablegrup')->name('administrasi.monitoring.pengisiansk.datatablegrup');
                Route::get('export', 'Administrasi\PengisianskController@export')->name('administrasi.monitoring.pengisiansk.export');
                Route::get('export2/{id_perusahaan?}', 'Administrasi\PengisianskController@export2')->name('administrasi.monitoring.pengisiansk.export2');
            });
        });
    });

    Route::prefix('organ')->group(function(){

        Route::prefix('komposisi')->group(function(){
            Route::get('index', 'Organ\KomposisiController@index')->name('organ.komposisi.index');
            Route::get('datatable', 'Organ\KomposisiController@datatable')->name('organ.komposisi.datatable');
            Route::get('/tambah2', 'Organ\KomposisiController@tambah2')->name('organ.komposisi.tambah2');
            Route::post('datatable2', 'Organ\KomposisiController@datatable2')->name('organ.komposisi.datatable2');
            Route::post('creategrupjabatan', 'Organ\KomposisiController@creategrupjabatan')->name('organ.komposisi.creategrupjabatan');
            Route::post('storegrupjabatan', 'Organ\KomposisiController@storegrupjabatan')->name('organ.komposisi.storegrupjabatan');
            Route::post('create', 'Organ\KomposisiController@create')->name('organ.komposisi.create');
            Route::post('store', 'Organ\KomposisiController@store')->name('organ.komposisi.store');
            Route::post('createanak', 'Organ\KomposisiController@createanak')->name('organ.komposisi.createanak');
            Route::post('storeanak', 'Organ\KomposisiController@storeanak')->name('organ.komposisi.storeanak');
            Route::post('delete', 'Organ\KomposisiController@delete')->name('organ.komposisi.delete');
            Route::post('deleteanak', 'Organ\KomposisiController@deleteanak')->name('organ.komposisi.deleteanak');
            Route::post('deleteall', 'Organ\KomposisiController@deleteall')->name('organ.komposisi.deleteall');
            Route::get('/showkomposisi', 'Organ\KomposisiController@showkomposisi');
            Route::get('/showkomwas', 'Organ\KomposisiController@showkomwas');
            Route::post('editgrupjabatan', 'Organ\KomposisiController@editgrupjabatan')->name('organ.komposisi.editgrupjabatan');
            Route::post('changeaktif', 'Organ\KomposisiController@changeaktif')->name('organ.komposisi.changeaktif');
        });

        Route::prefix('kelasbumn')->group(function(){
            Route::get('index', 'Organ\KelasBumnController@index')->name('organ.kelasbumn.index');
            Route::post('create', 'Organ\KelasBumnController@create')->name('organ.kelasbumn.create');
            Route::post('edit', 'Organ\KelasBumnController@edit')->name('organ.kelasbumn.edit');
            Route::post('store', 'Organ\KelasBumnController@store')->name('organ.kelasbumn.store');
            Route::post('delete', 'Organ\KelasBumnController@delete')->name('organ.kelasbumn.delete');
            Route::get('datatable', 'Organ\KelasBumnController@datatable')->name('organ.kelasbumn.datatable');
            Route::get('detailbumn', 'Organ\KelasBumnController@detailbumn')->name('organ.kelasbumn.detailbumn');
        });

    });

    Route::prefix('remunerasi')->group(function(){
        // Remunerasi Board - BUMN
        Route::prefix('board')->group(function(){
            Route::get('index', 'Remunerasi\BoardController@index')->name('remunerasi.board.index');
            Route::post('create', 'Remunerasi\BoardController@create')->name('remunerasi.board.create');
            Route::post('edit', 'Remunerasi\BoardController@edit')->name('remunerasi.board.edit');
            Route::post('store', 'Remunerasi\BoardController@store')->name('remunerasi.board.store');
            Route::post('update', 'Remunerasi\BoardController@update')->name('remunerasi.board.update');
            Route::post('delete', 'Remunerasi\BoardController@delete')->name('remunerasi.board.delete');
            Route::get('datatable', 'Remunerasi\BoardController@datatable')->name('remunerasi.board.datatable');
        });

        // Remunerasi Jabatan - Dirkomwas
        Route::prefix('dirkomwas')->group(function(){
            Route::get('index', 'Remunerasi\DirkomwasController@index')->name('remunerasi.dirkomwas.index');
            Route::post('create', 'Remunerasi\DirkomwasController@create')->name('remunerasi.dirkomwas.create');
            Route::post('edit', 'Remunerasi\DirkomwasController@edit')->name('remunerasi.dirkomwas.edit');
            Route::post('store', 'Remunerasi\DirkomwasController@store')->name('remunerasi.dirkomwas.store');
            Route::post('update', 'Remunerasi\DirkomwasController@update')->name('remunerasi.dirkomwas.update');
            Route::post('delete', 'Remunerasi\DirkomwasController@delete')->name('remunerasi.dirkomwas.delete');
            Route::get('datatable', 'Remunerasi\DirkomwasController@datatable')->name('remunerasi.dirkomwas.datatable');
            Route::get('detail', 'Remunerasi\DirkomwasController@detail')->name('remunerasi.dirkomwas.detail');
        });
    });

    Route::prefix('referensi')->group(function(){

        Route::prefix('periode')->group(function(){
            Route::get('index', 'Referensi\PeriodeController@index')->name('referensi.periode.index');
            Route::post('create', 'Referensi\PeriodeController@create')->name('referensi.periode.create');
            Route::post('edit', 'Referensi\PeriodeController@edit')->name('referensi.periode.edit');
            Route::post('store', 'Referensi\PeriodeController@store')->name('referensi.periode.store');
            Route::post('delete', 'Referensi\PeriodeController@delete')->name('referensi.periode.delete');
            Route::get('datatable', 'Referensi\PeriodeController@datatable')->name('referensi.periode.datatable');
        });

        Route::prefix('jenjangpendidikan')->group(function(){
            Route::get('index', 'Referensi\JenjangPendidikanController@index')->name('referensi.jenjangpendidikan.index');
            Route::post('create', 'Referensi\JenjangPendidikanController@create')->name('referensi.jenjangpendidikan.create');
            Route::post('edit', 'Referensi\JenjangPendidikanController@edit')->name('referensi.jenjangpendidikan.edit');
            Route::post('store', 'Referensi\JenjangPendidikanController@store')->name('referensi.jenjangpendidikan.store');
            Route::post('delete', 'Referensi\JenjangPendidikanController@delete')->name('referensi.jenjangpendidikan.delete');
            Route::get('datatable', 'Referensi\JenjangPendidikanController@datatable')->name('referensi.jenjangpendidikan.datatable');
        });

        Route::prefix('bidangjabatan')->group(function(){
            Route::get('index', 'Referensi\BidangJabatanController@index')->name('referensi.bidangjabatan.index');
            Route::post('create', 'Referensi\BidangJabatanController@create')->name('referensi.bidangjabatan.create');
            Route::post('edit', 'Referensi\BidangJabatanController@edit')->name('referensi.bidangjabatan.edit');
            Route::post('store', 'Referensi\BidangJabatanController@store')->name('referensi.bidangjabatan.store');
            Route::post('delete', 'Referensi\BidangJabatanController@delete')->name('referensi.bidangjabatan.delete');
            Route::get('datatable', 'Referensi\BidangJabatanController@datatable')->name('referensi.bidangjabatan.datatable');
        });

          // referensi grup jabatan
        Route::prefix('grupjabatan')->group(function(){
            Route::get('index', 'Referensi\GrupJabatanController@index')->name('referensi.grupjabatan.index');
            Route::post('create', 'Referensi\GrupJabatanController@create')->name('referensi.grupjabatan.create');
            Route::post('edit', 'Referensi\GrupJabatanController@edit')->name('referensi.grupjabatan.edit');
            Route::post('store', 'Referensi\GrupJabatanController@store')->name('referensi.grupjabatan.store');
            Route::post('delete', 'Referensi\GrupJabatanController@delete')->name('referensi.grupjabatan.delete');
            Route::get('datatable', 'Referensi\GrupJabatanController@datatable')->name('referensi.grupjabatan.datatable');
        });

        Route::prefix('partaipolitik')->group(function(){
            Route::get('index', 'Referensi\PartaiPolitikController@index')->name('referensi.partaipolitik.index');
            Route::post('create', 'Referensi\PartaiPolitikController@create')->name('referensi.partaipolitik.create');
            Route::post('edit', 'Referensi\PartaiPolitikController@edit')->name('referensi.partaipolitik.edit');
            Route::post('store', 'Referensi\PartaiPolitikController@store')->name('referensi.partaipolitik.store');
            Route::post('delete', 'Referensi\PartaiPolitikController@delete')->name('referensi.partaipolitik.delete');
            Route::get('datatable', 'Referensi\PartaiPolitikController@datatable')->name('referensi.partaipolitik.datatable');
        });

        Route::prefix('suku')->group(function(){
            Route::get('index', 'Referensi\SukuController@index')->name('referensi.suku.index');
            Route::post('create', 'Referensi\SukuController@create')->name('referensi.suku.create');
            Route::post('edit', 'Referensi\SukuController@edit')->name('referensi.suku.edit');
            Route::post('store', 'Referensi\SukuController@store')->name('referensi.suku.store');
            Route::post('delete', 'Referensi\SukuController@delete')->name('referensi.suku.delete');
            Route::get('datatable', 'Referensi\SukuController@datatable')->name('referensi.suku.datatable');
        });

        Route::prefix('kementerianlain')->group(function(){
            Route::get('index', 'Referensi\KementerianLainController@index')->name('referensi.kementerianlain.index');
            Route::post('create', 'Referensi\KementerianLainController@create')->name('referensi.kementerianlain.create');
            Route::post('edit', 'Referensi\KementerianLainController@edit')->name('referensi.kementerianlain.edit');
            Route::post('store', 'Referensi\KementerianLainController@store')->name('referensi.kementerianlain.store');
            Route::post('delete', 'Referensi\KementerianLainController@delete')->name('referensi.kementerianlain.delete');
            Route::get('datatable', 'Referensi\KementerianLainController@datatable')->name('referensi.kementerianlain.datatable');
            Route::get('apisynclembaga', function () {
                $exitCode = Artisan::call('apisync:lembaga');
                return redirect('referensi/kementerianlain/index');
            })->name('referensi.kementerianlain.apisynclembaga');
        });

        Route::prefix('provinsi')->group(function(){
            Route::get('index', 'Referensi\ProvinsiController@index')->name('referensi.provinsi.index');
            Route::post('create', 'Referensi\ProvinsiController@create')->name('referensi.provinsi.create');
            Route::post('edit', 'Referensi\ProvinsiController@edit')->name('referensi.provinsi.edit');
            Route::post('store', 'Referensi\ProvinsiController@store')->name('referensi.provinsi.store');
            Route::post('delete', 'Referensi\ProvinsiController@delete')->name('referensi.provinsi.delete');
            Route::get('datatable', 'Referensi\ProvinsiController@datatable')->name('referensi.provinsi.datatable');
            Route::get('apisyncprovinsikota', function () {
                $exitCode = Artisan::call('apisync:provinsikota');
                return redirect('referensi/provinsi/index');
            })->name('referensi.provinsi.apisyncprovinsikota');
        });

        Route::prefix('kota')->group(function(){
            Route::get('index', 'Referensi\KotaController@index')->name('referensi.kota.index');
            Route::post('create', 'Referensi\KotaController@create')->name('referensi.kota.create');
            Route::post('edit', 'Referensi\KotaController@edit')->name('referensi.kota.edit');
            Route::post('store', 'Referensi\KotaController@store')->name('referensi.kota.store');
            Route::post('delete', 'Referensi\KotaController@delete')->name('referensi.kota.delete');
            Route::get('datatable', 'Referensi\KotaController@datatable')->name('referensi.kota.datatable');
        });

        Route::prefix('ormas')->group(function(){
            Route::get('index', 'Referensi\OrmasController@index')->name('referensi.ormas.index');
            Route::post('create', 'Referensi\OrmasController@create')->name('referensi.ormas.create');
            Route::post('edit', 'Referensi\OrmasController@edit')->name('referensi.ormas.edit');
            Route::post('store', 'Referensi\OrmasController@store')->name('referensi.ormas.store');
            Route::post('delete', 'Referensi\OrmasController@delete')->name('referensi.ormas.delete');
            Route::get('datatable', 'Referensi\OrmasController@datatable')->name('referensi.ormas.datatable');
        });

        Route::prefix('agama')->group(function(){
            Route::get('index', 'Referensi\AgamaController@index')->name('referensi.agama.index');
            Route::post('create', 'Referensi\AgamaController@create')->name('referensi.agama.create');
            Route::post('edit', 'Referensi\AgamaController@edit')->name('referensi.agama.edit');
            Route::post('store', 'Referensi\AgamaController@store')->name('referensi.agama.store');
            Route::post('delete', 'Referensi\AgamaController@delete')->name('referensi.agama.delete');
            Route::get('datatable', 'Referensi\AgamaController@datatable')->name('referensi.agama.datatable');
        });

        Route::prefix('golongandarah')->group(function(){
            Route::get('index', 'Referensi\GolonganDarahController@index')->name('referensi.golongandarah.index');
            Route::post('create', 'Referensi\GolonganDarahController@create')->name('referensi.golongandarah.create');
            Route::post('edit', 'Referensi\GolonganDarahController@edit')->name('referensi.golongandarah.edit');
            Route::post('store', 'Referensi\GolonganDarahController@store')->name('referensi.golongandarah.store');
            Route::post('delete', 'Referensi\GolonganDarahController@delete')->name('referensi.golongandarah.delete');
            Route::get('datatable', 'Referensi\GolonganDarahController@datatable')->name('referensi.golongandarah.datatable');
        });

        Route::prefix('kategoritalenta')->group(function(){
            Route::get('index', 'Referensi\KategoriTalentaController@index')->name('referensi.kategoritalenta.index');
            Route::post('create', 'Referensi\KategoriTalentaController@create')->name('referensi.kategoritalenta.create');
            Route::post('edit', 'Referensi\KategoriTalentaController@edit')->name('referensi.kategoritalenta.edit');
            Route::post('store', 'Referensi\KategoriTalentaController@store')->name('referensi.kategoritalenta.store');
            Route::post('delete', 'Referensi\KategoriTalentaController@delete')->name('referensi.kategoritalenta.delete');
            Route::get('datatable', 'Referensi\KategoriTalentaController@datatable')->name('referensi.kategoritalenta.datatable');
        });


        //referensi jenis sk
        Route::prefix('jenissk')->group(function(){
            Route::get('index', 'Referensi\JenisSkController@index')->name('referensi.jenissk.index');
            Route::post('create', 'Referensi\JenisSkController@create')->name('referensi.jenissk.create');
            Route::post('edit', 'Referensi\JenisSkController@edit')->name('referensi.jenissk.edit');
            Route::post('store', 'Referensi\JenisSkController@store')->name('referensi.jenissk.store');
            Route::post('delete', 'Referensi\JenisSkController@delete')->name('referensi.jenissk.delete');
            Route::get('datatable', 'Referensi\JenisSkController@datatable')->name('referensi.jenissk.datatable');
        });
        // referensi jenis jabatan
        Route::prefix('jenisjabatan')->group(function(){
            Route::get('index', 'Referensi\JenisJabatanController@index')->name('referensi.jenisjabatan.index');
            Route::post('create', 'Referensi\JenisJabatanController@create')->name('referensi.jenisjabatan.create');
            Route::post('edit', 'Referensi\JenisJabatanController@edit')->name('referensi.jenisjabatan.edit');
            Route::post('store', 'Referensi\JenisJabatanController@store')->name('referensi.jenisjabatan.store');
            Route::post('delete', 'Referensi\JenisJabatanController@delete')->name('referensi.jenisjabatan.delete');
            Route::get('datatable', 'Referensi\JenisJabatanController@datatable')->name('referensi.jenisjabatan.datatable');
        });

        // referensi status masa jabatan
        Route::prefix('jenisstatusmasajabatan')->group(function(){
            Route::get('index', 'Referensi\JenisStatusMasaJabatanController@index')->name('referensi.jenisstatusmasajabatan.index');
            Route::post('create', 'Referensi\JenisStatusMasaJabatanController@create')->name('referensi.jenisstatusmasajabatan.create');
            Route::post('edit', 'Referensi\JenisStatusMasaJabatanController@edit')->name('referensi.jenisstatusmasajabatan.edit');
            Route::post('store', 'Referensi\JenisStatusMasaJabatanController@store')->name('referensi.jenisstatusmasajabatan.store');
            Route::post('delete', 'Referensi\JenisStatusMasaJabatanController@delete')->name('referensi.jenisstatusmasajabatan.delete');
            Route::get('datatable', 'Referensi\JenisStatusMasaJabatanController@datatable')->name('referensi.jenisstatusmasajabatan.datatable');
        });
        // referensi jenis perusahaan
        Route::prefix('jenisperusahaan')->group(function(){
            Route::get('index', 'Referensi\JenisPerusahaanController@index')->name('referensi.jenisperusahaan.index');
            Route::post('create', 'Referensi\JenisPerusahaanController@create')->name('referensi.jenisperusahaan.create');
            Route::post('edit', 'Referensi\JenisPerusahaanController@edit')->name('referensi.jenisperusahaan.edit');
            Route::post('store', 'Referensi\JenisPerusahaanController@store')->name('referensi.jenisperusahaan.store');
            Route::post('delete', 'Referensi\JenisPerusahaanController@delete')->name('referensi.jenisperusahaan.delete');
            Route::get('datatable', 'Referensi\JenisPerusahaanController@datatable')->name('referensi.jenisperusahaan.datatable');
        });

        // referensi jenis asal instansi
        Route::prefix('jenisasalinstansi')->group(function(){
            Route::get('index', 'Referensi\JenisAsalInstansiController@index')->name('referensi.jenisasalinstansi.index');
            Route::post('create', 'Referensi\JenisAsalInstansiController@create')->name('referensi.jenisasalinstansi.create');
            Route::post('edit', 'Referensi\JenisAsalInstansiController@edit')->name('referensi.jenisasalinstansi.edit');
            Route::post('store', 'Referensi\JenisAsalInstansiController@store')->name('referensi.jenisasalinstansi.store');
            Route::post('delete', 'Referensi\JenisAsalInstansiController@delete')->name('referensi.jenisasalinstansi.delete');
            Route::get('datatable', 'Referensi\JenisAsalInstansiController@datatable')->name('referensi.jenisasalinstansi.datatable');
        });

        // referensi periode jabatan
        Route::prefix('periodejabatan')->group(function(){
            Route::get('index', 'Referensi\PeriodeJabatanController@index')->name('referensi.periodejabatan.index');
            Route::post('create', 'Referensi\PeriodeJabatanController@create')->name('referensi.periodejabatan.create');
            Route::post('edit', 'Referensi\PeriodeJabatanController@edit')->name('referensi.periodejabatan.edit');
            Route::post('store', 'Referensi\PeriodeJabatanController@store')->name('referensi.periodejabatan.store');
            Route::post('delete', 'Referensi\PeriodeJabatanController@delete')->name('referensi.periodejabatan.delete');
            Route::get('datatable', 'Referensi\PeriodeJabatanController@datatable')->name('referensi.periodejabatan.datatable');
        });
        // referensi kelas bumn
        Route::prefix('kelasbumn')->group(function(){
            Route::get('index', 'Referensi\KelasBumnController@index')->name('referensi.kelasbumn.index');
            Route::post('create', 'Referensi\KelasBumnController@create')->name('referensi.kelasbumn.create');
            Route::post('edit', 'Referensi\KelasBumnController@edit')->name('referensi.kelasbumn.edit');
            Route::post('store', 'Referensi\KelasBumnController@store')->name('referensi.kelasbumn.store');
            Route::post('delete', 'Referensi\KelasBumnController@delete')->name('referensi.kelasbumn.delete');
            Route::get('datatable', 'Referensi\KelasBumnController@datatable')->name('referensi.kelasbumn.datatable');
        });

        // referensi cluster bumn
        Route::prefix('clusterbumn')->group(function(){
            Route::get('index', 'Referensi\ClusterBumnController@index')->name('referensi.clusterbumn.index');
            Route::post('create', 'Referensi\ClusterBumnController@create')->name('referensi.clusterbumn.create');
            Route::post('edit', 'Referensi\ClusterBumnController@edit')->name('referensi.clusterbumn.edit');
            Route::post('store', 'Referensi\ClusterBumnController@store')->name('referensi.clusterbumn.store');
            Route::post('delete', 'Referensi\ClusterBumnController@delete')->name('referensi.clusterbumn.delete');
            Route::get('datatable', 'Referensi\ClusterBumnController@datatable')->name('referensi.clusterbumn.datatable');
        });

         // referensi mobility jabatan
        Route::prefix('mobilityjabatan')->group(function(){
            Route::get('index', 'Referensi\MobilityJabatanController@index')->name('referensi.mobilityjabatan.index');
            Route::post('create', 'Referensi\MobilityJabatanController@create')->name('referensi.mobilityjabatan.create');
            Route::post('edit', 'Referensi\MobilityJabatanController@edit')->name('referensi.mobilityjabatan.edit');
            Route::post('store', 'Referensi\MobilityJabatanController@store')->name('referensi.mobilityjabatan.store');
            Route::post('delete', 'Referensi\MobilityJabatanController@delete')->name('referensi.mobilityjabatan.delete');
            Route::get('datatable', 'Referensi\MobilityJabatanController@datatable')->name('referensi.mobilityjabatan.datatable');
        });

        // referensi Kategori Mobilty
        Route::prefix('kategorimobility')->group(function(){
            Route::get('index', 'Referensi\KategoriMobilityController@index')->name('referensi.kategorimobility.index');
            Route::post('create', 'Referensi\KategoriMobilityController@create')->name('referensi.kategorimobility.create');
            Route::post('edit', 'Referensi\KategoriMobilityController@edit')->name('referensi.kategorimobility.edit');
            Route::post('store', 'Referensi\KategoriMobilityController@store')->name('referensi.kategorimobility.store');
            Route::post('delete', 'Referensi\KategoriMobilityController@delete')->name('referensi.kategorimobility.delete');
            Route::get('datatable', 'Referensi\KategoriMobilityController@datatable')->name('referensi.kategorimobility.datatable');
        });

        // referensi Kategori Pemberhentian
        Route::prefix('kategoripemberhentian')->group(function(){
            Route::get('index', 'Referensi\KategoriPemberhentianController@index')->name('referensi.kategoripemberhentian.index');
            Route::post('create', 'Referensi\KategoriPemberhentianController@create')->name('referensi.kategoripemberhentian.create');
            Route::post('edit', 'Referensi\KategoriPemberhentianController@edit')->name('referensi.kategoripemberhentian.edit');
            Route::post('store', 'Referensi\KategoriPemberhentianController@store')->name('referensi.kategoripemberhentian.store');
            Route::post('delete', 'Referensi\KategoriPemberhentianController@delete')->name('referensi.kategoripemberhentian.delete');
            Route::get('datatable', 'Referensi\KategoriPemberhentianController@datatable')->name('referensi.kategoripemberhentian.datatable');
        });

        // referensi Jenis File Pendukung
        Route::prefix('jenisfilependukung')->group(function(){
            Route::get('index', 'Referensi\JenisFilePendukungController@index')->name('referensi.jenisfilependukung.index');
            Route::post('create', 'Referensi\JenisFilePendukungController@create')->name('referensi.jenisfilependukung.create');
            Route::post('edit', 'Referensi\JenisFilePendukungController@edit')->name('referensi.jenisfilependukung.edit');
            Route::post('store', 'Referensi\JenisFilePendukungController@store')->name('referensi.jenisfilependukung.store');
            Route::post('delete', 'Referensi\JenisFilePendukungController@delete')->name('referensi.jenisfilependukung.delete');
            Route::get('datatable', 'Referensi\JenisFilePendukungController@datatable')->name('referensi.jenisfilependukung.datatable');
        });

        // referensi Instansi/Asal Instansi
        Route::prefix('asalinstansi')->group(function(){
            Route::get('index', 'Referensi\AsalInstansiController@index')->name('referensi.asalinstansi.index');
            Route::post('create', 'Referensi\AsalInstansiController@create')->name('referensi.asalinstansi.create');
            Route::post('edit', 'Referensi\AsalInstansiController@edit')->name('referensi.asalinstansi.edit');
            Route::post('store', 'Referensi\AsalInstansiController@store')->name('referensi.asalinstansi.store');
            Route::post('delete', 'Referensi\AsalInstansiController@delete')->name('referensi.asalinstansi.delete');
            Route::get('datatable', 'Referensi\AsalInstansiController@datatable')->name('referensi.asalinstansi.datatable');
        });

        // referensi Alasan Pemberhentian
        Route::prefix('alasanpemberhentian')->group(function(){
            Route::get('index', 'Referensi\AlasanPemberhentianController@index')->name('referensi.alasanpemberhentian.index');
            Route::post('create', 'Referensi\AlasanPemberhentianController@create')->name('referensi.alasanpemberhentian.create');
            Route::post('edit', 'Referensi\AlasanPemberhentianController@edit')->name('referensi.alasanpemberhentian.edit');
            Route::post('store', 'Referensi\AlasanPemberhentianController@store')->name('referensi.alasanpemberhentian.store');
            Route::post('delete', 'Referensi\AlasanPemberhentianController@delete')->name('referensi.alasanpemberhentian.delete');
            Route::get('datatable', 'Referensi\AlasanPemberhentianController@datatable')->name('referensi.alasanpemberhentian.datatable');
        });

        // referensi Kategori Jabatan
        Route::prefix('kategorijabatantalent')->group(function(){
            Route::get('index', 'Referensi\KategoriJabatanTalentController@index')->name('referensi.kategorijabatantalent.index');
            Route::post('create', 'Referensi\KategoriJabatanTalentController@create')->name('referensi.kategorijabatantalent.create');
            Route::post('edit', 'Referensi\KategoriJabatanTalentController@edit')->name('referensi.kategorijabatantalent.edit');
            Route::post('store', 'Referensi\KategoriJabatanTalentController@store')->name('referensi.kategorijabatantalent.store');
            Route::post('delete', 'Referensi\KategoriJabatanTalentController@delete')->name('referensi.kategorijabatantalent.delete');
            Route::get('datatable', 'Referensi\KategoriJabatanTalentController@datatable')->name('referensi.kategorijabatantalent.datatable');
        });

        // referensi Kategori Jabatan
        Route::prefix('kategorinontalent')->group(function(){
            Route::get('index', 'Referensi\KategoriNonTalentController@index')->name('referensi.kategorinontalent.index');
            Route::post('create', 'Referensi\KategoriNonTalentController@create')->name('referensi.kategorinontalent.create');
            Route::post('edit', 'Referensi\KategoriNonTalentController@edit')->name('referensi.kategorinontalent.edit');
            Route::post('store', 'Referensi\KategoriNonTalentController@store')->name('referensi.kategorinontalent.store');
            Route::post('delete', 'Referensi\KategoriNonTalentController@delete')->name('referensi.kategorinontalent.delete');
            Route::get('datatable', 'Referensi\KategoriNonTalentController@datatable')->name('referensi.kategorinontalent.datatable');
        });

        // referensi komposisi profesional
        Route::prefix('komposisiprofesional')->group(function(){
            Route::get('index', 'Referensi\KomposisiProfesionalController@index')->name('referensi.komposisiprofesional.index');
            Route::post('create', 'Referensi\KomposisiProfesionalController@create')->name('referensi.komposisiprofesional.create');
            Route::post('edit', 'Referensi\KomposisiProfesionalController@edit')->name('referensi.komposisiprofesional.edit');
            Route::post('store', 'Referensi\KomposisiProfesionalController@store')->name('referensi.komposisiprofesional.store');
            Route::post('delete', 'Referensi\KomposisiProfesionalController@delete')->name('referensi.komposisiprofesional.delete');
            Route::get('datatable', 'Referensi\KomposisiProfesionalController@datatable')->name('referensi.komposisiprofesional.datatable');
        });

        // referensi Target Asal Instansi
        Route::prefix('targetasalinstansi')->group(function(){
            Route::get('index', 'Referensi\TargetAsalInstansiController@index')->name('referensi.targetasalinstansi.index');
            Route::post('create', 'Referensi\TargetAsalInstansiController@create')->name('referensi.targetasalinstansi.create');
            Route::post('edit', 'Referensi\TargetAsalInstansiController@edit')->name('referensi.targetasalinstansi.edit');
            Route::post('store', 'Referensi\TargetAsalInstansiController@store')->name('referensi.targetasalinstansi.store');
            Route::post('delete', 'Referensi\TargetAsalInstansiController@delete')->name('referensi.targetasalinstansi.delete');
            Route::get('datatable', 'Referensi\TargetAsalInstansiController@datatable')->name('referensi.targetasalinstansi.datatable');
        });

        // referensi Faktor Penghasilan
        Route::prefix('faktorpenghasilan')->group(function(){
            Route::get('index', 'Referensi\FaktorPenghasilanController@index')->name('referensi.faktorpenghasilan.index');
            Route::post('create', 'Referensi\FaktorPenghasilanController@create')->name('referensi.faktorpenghasilan.create');
            Route::post('edit', 'Referensi\FaktorPenghasilanController@edit')->name('referensi.faktorpenghasilan.edit');
            Route::post('store', 'Referensi\FaktorPenghasilanController@store')->name('referensi.faktorpenghasilan.store');
            Route::post('delete', 'Referensi\FaktorPenghasilanController@delete')->name('referensi.faktorpenghasilan.delete');
            Route::get('datatable', 'Referensi\FaktorPenghasilanController@datatable')->name('referensi.faktorpenghasilan.datatable');
        });

        // referensi Keahlian
        Route::prefix('keahlian')->group(function(){
            Route::get('index', 'Referensi\KeahlianController@index')->name('referensi.keahlian.index');
            Route::post('create', 'Referensi\KeahlianController@create')->name('referensi.keahlian.create');
            Route::post('edit', 'Referensi\KeahlianController@edit')->name('referensi.keahlian.edit');
            Route::post('store', 'Referensi\KeahlianController@store')->name('referensi.keahlian.store');
            Route::post('delete', 'Referensi\KeahlianController@delete')->name('referensi.keahlian.delete');
            Route::get('datatable', 'Referensi\KeahlianController@datatable')->name('referensi.keahlian.datatable');
        });

         // referensi BUMN
        Route::prefix('bumn')->group(function(){
            Route::get('index', 'Referensi\BumnController@index')->name('referensi.bumn.index');
            Route::post('create', 'Referensi\BumnController@create')->name('referensi.bumn.create');
            Route::post('edit', 'Referensi\BumnController@edit')->name('referensi.bumn.edit');
            Route::post('store', 'Referensi\BumnController@store')->name('referensi.bumn.store');
            Route::post('delete', 'Referensi\BumnController@delete')->name('referensi.bumn.delete');
            Route::get('datatable', 'Referensi\BumnController@datatable')->name('referensi.bumn.datatable');
            Route::post('aktif', 'Referensi\BumnController@aktif')->name('referensi.bumn.aktif');
            Route::get('silababumnsync', function () {
                $exitCode = Artisan::call('silaba:bumnsync');
                return redirect('referensi/bumn/index');
            })->name('referensi.bumn.silababumnsync');
        });
        // referensi Universitas
        Route::prefix('universitas')->group(function(){
            Route::get('index', 'Referensi\UniversitasController@index')->name('referensi.universitas.index');
            Route::post('create', 'Referensi\UniversitasController@create')->name('referensi.universitas.create');
            Route::post('edit', 'Referensi\UniversitasController@edit')->name('referensi.universitas.edit');
            Route::post('store', 'Referensi\UniversitasController@store')->name('referensi.universitas.store');
            Route::post('delete', 'Referensi\UniversitasController@delete')->name('referensi.universitas.delete');
            Route::get('datatable', 'Referensi\UniversitasController@datatable')->name('referensi.universitas.datatable');
        });
        // referensi Approve Nama Universitas
        Route::prefix('approve_universitas')->group(function(){
            Route::get('index', 'Referensi\ApproveUniversitasController@index')->name('referensi.approve_universitas.index');
            Route::post('delete', 'Referensi\ApproveUniversitasController@delete')->name('referensi.approve_universitas.delete');
            Route::post('store', 'Referensi\ApproveUniversitasController@store')->name('referensi.approve_universitas.store');
            Route::get('datatable', 'Referensi\ApproveUniversitasController@datatable')->name('referensi.approve_universitas.datatable');
        });

        // referensi Mata Uang
        Route::prefix('matauang')->group(function(){
            Route::get('index', 'Referensi\MataUangController@index')->name('referensi.matauang.index');
            Route::post('create', 'Referensi\MataUangController@create')->name('referensi.matauang.create');
            Route::post('edit', 'Referensi\MataUangController@edit')->name('referensi.matauang.edit');
            Route::post('store', 'Referensi\MataUangController@store')->name('referensi.matauang.store');
            Route::post('delete', 'Referensi\MataUangController@delete')->name('referensi.matauang.delete');
            Route::get('datatable', 'Referensi\MataUangController@datatable')->name('referensi.matauang.datatable');
        });

        Route::prefix('statustalenta')->group(function(){
            Route::get('index', 'Referensi\StatusTalentaController@index')->name('referensi.statustalenta.index');
            Route::post('create', 'Referensi\StatusTalentaController@create')->name('referensi.statustalenta.create');
            Route::post('edit', 'Referensi\StatusTalentaController@edit')->name('referensi.statustalenta.edit');
            Route::post('store', 'Referensi\StatusTalentaController@store')->name('referensi.statustalenta.store');
            Route::post('delete', 'Referensi\StatusTalentaController@delete')->name('referensi.statustalenta.delete');
            Route::get('datatable', 'Referensi\StatusTalentaController@datatable')->name('referensi.statustalenta.datatable');
        });

        Route::prefix('kategoridatatalent')->group(function(){
            Route::get('index', 'Referensi\KategoriDataTalentController@index')->name('referensi.kategoridatatalent.index');
            Route::post('create', 'Referensi\KategoriDataTalentController@create')->name('referensi.kategoridatatalent.create');
            Route::post('edit', 'Referensi\KategoriDataTalentController@edit')->name('referensi.kategoridatatalent.edit');
            Route::post('store', 'Referensi\KategoriDataTalentController@store')->name('referensi.kategoridatatalent.store');
            Route::post('delete', 'Referensi\KategoriDataTalentController@delete')->name('referensi.kategoridatatalent.delete');
            Route::get('datatable', 'Referensi\KategoriDataTalentController@datatable')->name('referensi.kategoridatatalent.datatable');
        });

        Route::prefix('kompetensi')->group(function(){
            Route::get('index', 'Referensi\KompetensiController@index')->name('referensi.kompetensi.index');
            Route::post('create', 'Referensi\KompetensiController@create')->name('referensi.kompetensi.create');
            Route::post('edit', 'Referensi\KompetensiController@edit')->name('referensi.kompetensi.edit');
            Route::post('store', 'Referensi\KompetensiController@store')->name('referensi.kompetensi.store');
            Route::post('delete', 'Referensi\KompetensiController@delete')->name('referensi.kompetensi.delete');
            Route::get('datatable', 'Referensi\KompetensiController@datatable')->name('referensi.kompetensi.datatable');
        });

        Route::prefix('kualifikasipersonal')->group(function(){
            Route::get('index', 'Referensi\KualifikasiPersonalController@index')->name('referensi.kualifikasipersonal.index');
            Route::post('create', 'Referensi\KualifikasiPersonalController@create')->name('referensi.kualifikasipersonal.create');
            Route::post('edit', 'Referensi\KualifikasiPersonalController@edit')->name('referensi.kualifikasipersonal.edit');
            Route::post('store', 'Referensi\KualifikasiPersonalController@store')->name('referensi.kualifikasipersonal.store');
            Route::post('delete', 'Referensi\KualifikasiPersonalController@delete')->name('referensi.kualifikasipersonal.delete');
            Route::get('datatable', 'Referensi\KualifikasiPersonalController@datatable')->name('referensi.kualifikasipersonal.datatable');
        });

        Route::prefix('karakter')->group(function(){
            Route::get('index', 'Referensi\KarakterController@index')->name('referensi.karakter.index');
            Route::post('create', 'Referensi\KarakterController@create')->name('referensi.karakter.create');
            Route::post('edit', 'Referensi\KarakterController@edit')->name('referensi.karakter.edit');
            Route::post('store', 'Referensi\KarakterController@store')->name('referensi.karakter.store');
            Route::post('delete', 'Referensi\KarakterController@delete')->name('referensi.karakter.delete');
            Route::get('datatable', 'Referensi\KarakterController@datatable')->name('referensi.karakter.datatable');
        });

        Route::prefix('statuspejabat')->group(function(){
            Route::get('index', 'Referensi\StatusPejabatController@index')->name('referensi.statuspejabat.index');
            Route::post('create', 'Referensi\StatusPejabatController@create')->name('referensi.statuspejabat.create');
            Route::post('edit', 'Referensi\StatusPejabatController@edit')->name('referensi.statuspejabat.edit');
            Route::post('store', 'Referensi\StatusPejabatController@store')->name('referensi.statuspejabat.store');
            Route::post('delete', 'Referensi\StatusPejabatController@delete')->name('referensi.statuspejabat.delete');
            Route::get('datatable', 'Referensi\StatusPejabatController@datatable')->name('referensi.statuspejabat.datatable');
        });

        Route::prefix('lembagaassessment')->group(function(){
            Route::get('index', 'Referensi\LembagaAssessmentController@index')->name('referensi.lembagaassessment.index');
            Route::post('create', 'Referensi\LembagaAssessmentController@create')->name('referensi.lembagaassessment.create');
            Route::post('edit', 'Referensi\LembagaAssessmentController@edit')->name('referensi.lembagaassessment.edit');
            Route::post('store', 'Referensi\LembagaAssessmentController@store')->name('referensi.lembagaassessment.store');
            Route::post('delete', 'Referensi\LembagaAssessmentController@delete')->name('referensi.lembagaassessment.delete');
            Route::get('datatable', 'Referensi\LembagaAssessmentController@datatable')->name('referensi.lembagaassessment.datatable');
        });

        Route::prefix('skalakesehatan')->group(function(){
            Route::get('index', 'Referensi\SkalaKesehatanController@index')->name('referensi.skalakesehatan.index');
            Route::post('create', 'Referensi\SkalaKesehatanController@create')->name('referensi.skalakesehatan.create');
            Route::post('edit', 'Referensi\SkalaKesehatanController@edit')->name('referensi.skalakesehatan.edit');
            Route::post('store', 'Referensi\SkalaKesehatanController@store')->name('referensi.skalakesehatan.store');
            Route::post('delete', 'Referensi\SkalaKesehatanController@delete')->name('referensi.skalakesehatan.delete');
            Route::get('datatable', 'Referensi\SkalaKesehatanController@datatable')->name('referensi.skalakesehatan.datatable');
        });

        Route::prefix('dynamicfilter')->group(function(){

            Route::prefix('dynamictabelsumber')->group(function(){
                Route::get('index', 'Referensi\DynamicTabelSumberController@index')->name('referensi.dynamicfilter.tabelsumber.index');
                Route::post('create', 'Referensi\DynamicTabelSumberController@create')->name('referensi.dynamicfilter.tabelsumber.create');
                Route::post('edit', 'Referensi\DynamicTabelSumberController@edit')->name('referensi.dynamicfilter.tabelsumber.edit');
                Route::post('store', 'Referensi\DynamicTabelSumberController@store')->name('referensi.dynamicfilter.tabelsumber.store');
                Route::post('delete', 'Referensi\DynamicTabelSumberController@delete')->name('referensi.dynamicfilter.tabelsumber.delete');
                Route::get('datatable', 'Referensi\DynamicTabelSumberController@datatable')->name('referensi.dynamicfilter.tabelsumber.datatable');
                Route::get('getall', 'Referensi\DynamicTabelSumberController@getAllData')->name('referensi.dynamicfilter.tabelsumber..getall');
            });

            Route::prefix('dynamicstandarvalue')->group(function(){
                Route::get('index', 'Referensi\DynamicStandarValueController@index')->name('referensi.dynamicfilter.standarvalue.index');
                Route::post('create', 'Referensi\DynamicStandarValueController@create')->name('referensi.dynamicfilter.standarvalue.create');
                Route::post('edit', 'Referensi\DynamicStandarValueController@edit')->name('referensi.dynamicfilter.standarvalue.edit');
                Route::post('store', 'Referensi\DynamicStandarValueController@store')->name('referensi.dynamicfilter.standarvalue.store');
                Route::post('delete', 'Referensi\DynamicStandarValueController@delete')->name('referensi.dynamicfilter.standarvalue.delete');
                Route::get('datatable', 'Referensi\DynamicStandarValueController@datatable')->name('referensi.dynamicfilter.standarvalue.datatable');
            });

            Route::prefix('dynamicoperator')->group(function(){
                Route::get('index', 'Referensi\DynamicOperatorController@index')->name('referensi.dynamicfilter.operator.index');
                Route::post('create', 'Referensi\DynamicOperatorController@create')->name('referensi.dynamicfilter.operator.create');
                Route::post('edit', 'Referensi\DynamicOperatorController@edit')->name('referensi.dynamicfilter.operator.edit');
                Route::post('store', 'Referensi\DynamicOperatorController@store')->name('referensi.dynamicfilter.operator.store');
                Route::post('delete', 'Referensi\DynamicOperatorController@delete')->name('referensi.dynamicfilter.operator.delete');
                Route::post('aktif', 'Referensi\DynamicOperatorController@aktif')->name('referensi.dynamicfilter.operator.aktif');
                Route::post('is_number', 'Referensi\DynamicOperatorController@is_number')->name('referensi.dynamicfilter.operator.is_number');
                Route::post('is_sorting', 'Referensi\DynamicOperatorController@is_sorting')->name('referensi.dynamicfilter.operator.is_sorting');
                Route::get('datatable', 'Referensi\DynamicOperatorController@datatable')->name('referensi.dynamicfilter.operator.datatable');
                Route::get('getall', 'Referensi\DynamicOperatorController@getAllData')->name('referensi.dynamicfilter.operator.getall');
            });
        });
    //akhir referensi
    });

	Route::prefix('pengelolaan')->group(function(){

		Route::prefix('general')->group(function(){
            Route::get('fetchpilihaninputan', 'Pengelolaan\GeneralController@fetchpilihaninputan')->name('pengelolaan.general.fetchpilihaninputan');
            Route::get('fecthwilayah', 'Pengelolaan\GeneralController@fecthwilayah')->name('pengelolaan.general.fecthwilayah');
            Route::get('fetchparentmenu', 'Pengelolaan\GeneralController@fetchparentmenu')->name('pengelolaan.general.fetchparentmenu');
            Route::get('fetchparentunit', 'Pengelolaan\GeneralController@fetchparentunit')->name('pengelolaan.general.fetchparentunit');
            Route::get('fetchkategoriuser', 'Pengelolaan\GeneralController@fetchkategoriuser')->name('pengelolaan.general.fetchkategoriuser');
            Route::get('fetchbumnactive', 'Pengelolaan\GeneralController@fetchbumnactive')->name('pengelolaan.general.fetchbumnactive');
            Route::get('fetchassessment', 'Pengelolaan\GeneralController@fetchassessment')->name('pengelolaan.general.fetchassessment');
            Route::get('fetchrole', 'Pengelolaan\GeneralController@fetchrole')->name('pengelolaan.general.fetchrole');
        });

		Route::prefix('menus')->group(function(){
            Route::get('index', 'Pengelolaan\MenuController@index')->name('pengelolaan.menus.index');
            Route::post('gettreemenu', 'Pengelolaan\MenuController@gettreemenu')->name('pengelolaan.menus.gettreemenu');
            Route::post('create', 'Pengelolaan\MenuController@create')->name('pengelolaan.menus.create');
            Route::post('edit', 'Pengelolaan\MenuController@edit')->name('pengelolaan.menus.edit');
            Route::post('store', 'Pengelolaan\MenuController@store')->name('pengelolaan.menus.store');
            Route::post('delete', 'Pengelolaan\MenuController@delete')->name('pengelolaan.menus.delete');
            Route::post('submitchangestructure', 'Pengelolaan\MenuController@submitchangestructure')->name('pengelolaan.menus.submitchangestructure');
        });

        Route::prefix('roles')->group(function(){
            Route::get('index', 'Pengelolaan\RoleController@index')->name('pengelolaan.roles.index');
            Route::post('create', 'Pengelolaan\RoleController@create')->name('pengelolaan.roles.create');
            Route::post('edit', 'Pengelolaan\RoleController@edit')->name('pengelolaan.roles.edit');
            Route::post('store', 'Pengelolaan\RoleController@store')->name('pengelolaan.roles.store');
            Route::post('delete', 'Pengelolaan\RoleController@delete')->name('pengelolaan.roles.delete');
            Route::get('datatable', 'Pengelolaan\RoleController@datatable')->name('pengelolaan.roles.datatable');
            Route::get('gettreemenubyrole/{id?}', 'Pengelolaan\RoleController@gettreemenubyrole')->name('pengelolaan.roles.gettreemenubyrole');
        });

        Route::prefix('users')->group(function(){
            Route::get('index', 'Pengelolaan\UserController@index')->name('pengelolaan.users.index');
            Route::post('create', 'Pengelolaan\UserController@create')->name('pengelolaan.users.create');
            Route::post('edit', 'Pengelolaan\UserController@edit')->name('pengelolaan.users.edit');
            Route::post('store', 'Pengelolaan\UserController@store')->name('pengelolaan.users.store');
            Route::post('delete', 'Pengelolaan\UserController@delete')->name('pengelolaan.users.delete');
            Route::get('datatable', 'Pengelolaan\UserController@datatable')->name('pengelolaan.users.datatable');
            Route::post('checkuser', 'Pengelolaan\UserController@checkuser')->name('pengelolaan.users.checkuser');
        });

        Route::prefix('pejabats')->group(function(){
            Route::get('index', 'Pengelolaan\UserPejabatController@index')->name('pengelolaan.pejabats.index');
            Route::post('create', 'Pengelolaan\UserPejabatController@create')->name('pengelolaan.pejabats.create');
            Route::post('edit', 'Pengelolaan\UserPejabatController@edit')->name('pengelolaan.pejabats.edit');
            Route::post('store', 'Pengelolaan\UserPejabatController@store')->name('pengelolaan.pejabats.store');
            Route::post('delete', 'Pengelolaan\UserPejabatController@delete')->name('pengelolaan.pejabats.delete');
            Route::get('datatable', 'Pengelolaan\UserPejabatController@datatable')->name('pengelolaan.pejabats.datatable');
            Route::post('checkuser', 'Pengelolaan\UserPejabatController@checkuser')->name('pengelolaan.pejabats.checkuser');
            Route::post('/getnamapejabat', 'Pengelolaan\UserPejabatController@getnamapejabat');
        });

        Route::prefix('permissions')->group(function(){
            Route::get('index', 'Pengelolaan\PermissionController@index')->name('pengelolaan.permissions.index');
            Route::post('create', 'Pengelolaan\PermissionController@create')->name('pengelolaan.permissions.create');
            Route::post('edit', 'Pengelolaan\PermissionController@edit')->name('pengelolaan.permissions.edit');
            Route::post('store', 'Pengelolaan\PermissionController@store')->name('pengelolaan.permissions.store');
            Route::post('delete', 'Pengelolaan\PermissionController@delete')->name('pengelolaan.permissions.delete');
            Route::get('datatable', 'Pengelolaan\PermissionController@datatable')->name('pengelolaan.permissions.datatable');
        });

        Route::prefix('user_guides')->group(function(){
            Route::get('index', 'Pengelolaan\UserGuideController@index')->name('pengelolaan.user_guides.index');
            Route::post('create', 'Pengelolaan\UserGuideController@create')->name('pengelolaan.user_guides.create');
            Route::post('edit', 'Pengelolaan\UserGuideController@edit')->name('pengelolaan.user_guides.edit');
            Route::post('store', 'Pengelolaan\UserGuideController@store')->name('pengelolaan.user_guides.store');
            Route::post('delete', 'Pengelolaan\UserGuideController@delete')->name('pengelolaan.user_guides.delete');
            Route::get('datatable', 'Pengelolaan\UserGuideController@datatable')->name('pengelolaan.user_guides.datatable');
        });
	});

    Route::prefix('report')->group(function(){
        Route::get('index', 'ReportController@index')->name('report.index');
        Route::post('create', 'ReportController@create')->name('report.create');
        Route::post('edit', 'ReportController@edit')->name('report.edit');
        Route::post('store', 'ReportController@store')->name('report.store');
        Route::post('delete', 'ReportController@delete')->name('report.delete');
        Route::get('datatable', 'ReportController@datatable')->name('report.datatable');
        Route::get('export', 'ReportController@export')->name('report.export');
    });
});

//Auth::routes();

Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::post('/keterangan/import_excel', 'CV\BoardController@store_import');
Route::post('/keterangan/import_excel2', 'CV\BoardController@store_import2');


/*Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UserController');

Route::resource('roles', 'RoleController');

Route::resource('permissions', 'PermissionController');

Route::resource('posts', 'PostController');*/
