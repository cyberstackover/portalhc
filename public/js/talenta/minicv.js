var datatablependidikan;
var datatablepelatihan;
var datatablejabatan;
var datatablejabatanlain;
var datatableorganisasi;
var datatableorganisasinonformal;
var datatablepenghargaan;
var datatablekaryailmiah;
var datatablepengalaman;
var datatablekeluarga;
var datatableanak;
var datatablepajak;
var datatablekesehatan;
var datatablelhkpn;
var datatabletani;
var datatablereferensi;
var datatableassessment;

$(document).ready(function(){

    $('body').on('click', '.cls-urlpendukung', function(){
      popupwindow($(this).data('url'), $(this).data('keterangan'), 500, 500);
    });

	setDatatablePendidikan();
	setDatatablePelatihan();
	setDatatablejabatan();
	setDatatablejabatanlain();
	setDatatableorganisasi();
	setDatatableorganisasinonformal();
	setDatatablepenghargaan();
	setDatatablekaryailmiah();
	setDatatablepengalaman();
	setDatatablekeluarga();
	setDatatableanak();
	setDatatablepajak();
	setDatatablekesehatan();
	setDatatablelhkpn();
	setDatatabletani();
	setDatatablereferensi();
	setDatatableassessment();
});

function setDatatableassessment(){
    datatableassessment = $('#datatableassessment').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        language: {
          emptyTable: "Tidak Memiliki Referensi"
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: urldatatableassessment,
        columns: [
            { data: null, orderable: false, searchable: false},
            { data: 'file_name', name: 'file_name', searchable: true},
            { data: 'tanggal', name: 'tanggal', searchable: true},
            { data: 'user', name: 'user', searchable: false},
            { data: 'action', orderable: false, searchable: false, visible:false}
        ],
        drawCallback: function( settings ) {
          var info = datatableassessment.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatableassessment.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }

  function setDatatablereferensi(){
    datatablereferensi = $('#datatablereferensi').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        language: {
          emptyTable: "Tidak Memiliki Referensi"
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: urldatatablereferensi,
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'nama', name: 'nama', searchable: true},
          { data: 'perusahaan', name: 'perusahaan', searchable: true},
          { data: 'jabatan', name: 'jabatan', searchable: true},
          { data: 'nomor_handphone', name: 'nomor_handphone', searchable: true},
          { data: 'action', orderable: false, searchable: false, visible:false}
        ],
        drawCallback: function( settings ) {
          var info = datatablereferensi.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablereferensi.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }

function setDatatablePendidikan(){
    datatablependidikan = $('#datatablependidikan').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablependidikan,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'id_jenjang_pendidikan', name: 'id_jenjang_pendidikan', searchable: true},
          { data: 'perguruan_tinggi', name: 'perguruan_tinggi', searchable: true},
          { data: 'tahun', name: 'tahun', searchable: true},
          { data: 'kota', name: 'kota', searchable: true},
          { data: 'penghargaan', name: 'penghargaan', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablependidikan.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablependidikan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
}

function setDatatablePelatihan(){
    datatablepelatihan = $('#datatablepelatihan').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablepelatihan,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'pengembangan_kompetensi', name: 'pengembangan_kompetensi', searchable: true},
          { data: 'kota', name: 'kota', searchable: true},
          { data: 'jenis_diklat', name: 'jenis_diklat', searchable: true},
          { data: 'tahun_diklat', name: 'tahun_diklat', searchable: true},
          { data: 'nomor_sertifikasi', name: 'kota', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablepelatihan.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablepelatihan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    }); 
  }

  function setDatatablejabatan(){
    datatablejabatan = $('#datatablejabatan').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablejabatan,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'jabatan', name: 'jabatan', searchable: true},
          { data: 'tupoksi', name: 'tupoksi', searchable: true},
          { data: 'tanggal_awal', name: 'tanggal_awal', searchable: true},
          { data: 'tanggal_akhir', name: 'tanggal_akhir', searchable: true},
          { data: 'bidang_jabatan', name: 'bidang_jabatan', searchable: true},
          { data: 'achievement', name: 'achievement', searchable: true},
        ],
        drawCallback: function( settings ) {
          var info = datatablejabatan.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablejabatan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }
  
  function setDatatablejabatanlain(){
    datatablejabatanlain = $('#datatablejabatanlain').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablejabatanlain,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'penugasan', name: 'penugasan', searchable: true},
          { data: 'tupoksi', name: 'tupoksi', searchable: true},
          { data: 'tanggal_awal', name: 'tanggal_awal', searchable: true},
          { data: 'tanggal_akhir', name: 'tanggal_akhir', searchable: true},
          { data: 'bidang_jabatan', name: 'bidang_jabatan', searchable: true},
          { data: 'instansi', name: 'instansi', searchable: true},
        ],
        drawCallback: function( settings ) {
          var info = datatablejabatanlain.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablejabatanlain.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    }); 
  }
  
function setDatatableorganisasi(){
    datatableorganisasi = $('#datatableorganisasi').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        language: {
          emptyTable: "Tidak Memiliki Organisasi"
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatableorganisasi,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'nama_organisasi', name: 'nama_organisasi', searchable: true},
          { data: 'jabatan', name: 'jabatan', searchable: true},
          { data: 'tahun_awal', name: 'tahun_awal', searchable: true},
          { data: 'kegiatan_organisasi', name: 'kegiatan_organisasi', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatableorganisasi.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatableorganisasi.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }
  
  function setDatatableorganisasinonformal(){
    datatableorganisasinonformal = $('#datatableorganisasinonformal').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        language: {
          emptyTable: "Tidak Memiliki Organisasi"
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatableorganisasinonformal,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'nama_organisasi', name: 'nama_organisasi', searchable: true},
          { data: 'jabatan', name: 'jabatan', searchable: true},
          { data: 'tanggal_awal', name: 'tanggal_awal', searchable: true},
          { data: 'kegiatan_organisasi', name: 'kegiatan_organisasi', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatableorganisasinonformal.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatableorganisasinonformal.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    }); 
  }
  
  
function setDatatablepenghargaan(){
    datatablepenghargaan = $('#datatablepenghargaan').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        language: {
          emptyTable: "Tidak Memiliki Penghargaan"
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablepenghargaan,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'jenis_penghargaan', name: 'jenis_penghargaan', searchable: true},
          { data: 'tingkat', name: 'tingkat', searchable: true},
          { data: 'pemberi_penghargaan', name: 'pemberi_penghargaan', searchable: true},
          { data: 'tahun', name: 'tahun', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablepenghargaan.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablepenghargaan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
            
        }
    });	
  }
  
  function setDatatablekaryailmiah(){
    datatablekaryailmiah = $('#datatablekaryailmiah').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        language: {
          emptyTable: "Tidak Memiliki Karya Ilmiah"
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablekaryailmiah,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'judul', name: 'judul', searchable: true},
          { data: 'tahun', name: 'tahun', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablekaryailmiah.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablekaryailmiah.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    }); 
  }
  
function setDatatablepengalaman(){
    datatablepengalaman = $('#datatablepengalaman').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablepengalaman,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'acara', name: 'acara', searchable: true},
          { data: 'penyelenggara', name: 'penyelenggara', searchable: true},
          { data: 'periode', name: 'periode', searchable: true},
          { data: 'lokasi', name: 'lokasi', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablepengalaman.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablepengalaman.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }
  
function setDatatablekeluarga(){
    datatablekeluarga = $('#datatablekeluarga').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        language: {
          emptyTable: "Belum Memiliki keluarga"
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablekeluarga,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'nama', name: 'nama', searchable: true},
          { data: 'tempat_lahir', name: 'tempat_lahir', searchable: true},
          { data: 'tanggal_lahir', name: 'tanggal_lahir', searchable: true},
          { data: 'tanggal_menikah', name: 'tanggal_menikah', searchable: true},
          { data: 'pekerjaan', name: 'pekerjaan', searchable: true},
          { data: 'keterangan', name: 'keterangan', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablekeluarga.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablekeluarga.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }
  
function setDatatableanak(){
    datatableanak = $('#datatableanak').on( 'preXhr.dt', function ( e, settings, processing ) {
                KTApp.block('.cls-content-data', {
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'primary',
                    message: 'Sedang proses, silahkan tunggu ...'
                });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        language: {
            emptyTable: "Tidak Memiliki Anak"
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatableanak,
            type: 'GET'
        },
        columns: [
            { data: null, orderable: false, searchable: false},
            { data: 'nama', name: 'nama', searchable: true},
            { data: 'tempat_lahir', name: 'tempat_lahir', searchable: true},
            { data: 'tanggal_lahir', name: 'tanggal_lahir', searchable: true},
            { data: 'jenis_kelamin', name: 'jenis_kelamin', searchable: true},
            { data: 'pekerjaan', name: 'pekerjaan', searchable: true},
            { data: 'keterangan', name: 'keterangan', searchable: true},
            { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
            var info = datatableanak.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatableanak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
            
        }
    }); 
}
  
function setDatatablepajak(){
    datatablepajak = $('#datatablepajak').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: {
            url: urldatatablepajak,
            type: 'GET'
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'file_name', name: 'file_name', searchable: true},
          { data: 'tahun', name: 'tahun', searchable: true},
          { data: 'user', name: 'user', searchable: true},
          { data: 'action', name: 'action', visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablepajak.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablepajak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }
  
function setDatatablekesehatan(){
    datatablekesehatan = $('#datatablekesehatan').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: urldatatablekesehatan,
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'tahun_kesehatan', name: 'tahun_kesehatan', searchable: true},
          { data: 'nilai_kesehatan', name: 'nilai_kesehatan', searchable: true},
          { data: 'instansi_kesehatan', name: 'instansi_kesehatan', searchable: true},
          { data: 'action', orderable: false, searchable: false, visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablekesehatan.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablekesehatan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }

function setDatatablelhkpn(){
    datatablelhkpn = $('#datatablelhkpn').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: urldatatablelhkpn,
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'file_name', name: 'file_name', searchable: true},
          { data: 'tgl_pelaporan', name: 'tgl_pelaporan', searchable: true},
          { data: 'jml_kekayaan_rp', name: 'jml_kekayaan_rp', searchable: true, sClass: 'text-right'},
          { data: 'user', name: 'user', searchable: true},
          { data: 'action', orderable: false, searchable: false, visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatablelhkpn.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatablelhkpn.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }

  function setDatatabletani(){
    datatabletani = $('#datatabletani').on( 'preXhr.dt', function ( e, settings, processing ) {
              KTApp.block('.cls-content-data', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
    })
    .on('xhr.dt', function ( e, settings, json, xhr ) {
        KTApp.unblock('.cls-content-data');
    }).DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        search: {
            caseInsensitive: true
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
        searchHighlight: true,
        ajax: urldatatabletani,
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'tgl_awal', name: 'tgl_awal', searchable: true},
          { data: 'tgl_akhir', name: 'tgl_akhir', searchable: true},
          { data: 'tani', name: 'tani', searchable: true, sClass: 'text-right'},
          { data: 'action', orderable: false, searchable: false, visible: false}
        ],
        drawCallback: function( settings ) {
          var info = datatabletani.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatabletani.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
        }
    });	
  }