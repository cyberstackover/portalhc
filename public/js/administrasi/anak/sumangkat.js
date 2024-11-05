var datatablesumangkat;
var datatablesumhenti;
var datatablesumklatur;
var datatablesumplt;
var datatablesumalt;
var datatablesumindependen;

$(document).ready(function(){
	
   setDatatableSumAngkat();
   setDatatableSumHenti();
   setDatatableSumKlatur();
   setDatatablesumplt();
   setDatatablesumalt();
   setDatatableSumIndependen();
});

function setDatatableSumAngkat(){
  datatablesumangkat = $('#datatable-sum-angkat').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      info: false,
      searching: false,
      lengthChange: false,
      ajax: {
          url: urldatatablesumangkat,
          type: 'GET',
          data: function (d) {
              d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nomenklatur_jabatan', name: 'nomenklatur_jabatan', searchable: true},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'nama', name: 'nama', searchable: true},
        { data: 'tanggal_awal_menjabat', name: 'tanggal_awal_menjabat', searchable: true},
        { data: 'tanggal_akhir_menjabat', name: 'tanggal_akhir_menjabat', searchable: true}
      ],
      drawCallback: function( settings ) {
        var info = datatablesumangkat.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumangkat.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function setDatatableSumHenti(){
  datatablesumhenti = $('#datatable-sum-berhenti').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      info: false,
      searching: false,
      lengthChange: false,
      ajax: {
          url: urldatatablesumhenti,
          type: 'GET',
          data: function (d) {
              d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nomenklatur_jabatan', name: 'nomenklatur_jabatan', searchable: true},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'keterangan', name: 'keterangan', searchable: true},
        { data: 'tanggal_akhir_menjabat', name: 'tanggal_akhir_menjabat', searchable: true}
      ],
      drawCallback: function( settings ) {
        var info = datatablesumhenti.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumhenti.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function setDatatableSumKlatur(){
  datatablesumklatur = $('#datatable-sum-klatur').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      info: false,
      searching: false,
      lengthChange: false,
      ajax: {
          url: urldatatablesumklatur,
          type: 'GET',
          data: function (d) {
              d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nomenklatur_jabatan', name: 'nomenklatur_jabatan', searchable: true},
        { data: 'nomenklatur_baru', name: 'nomenklatur_baru', searchable: true}
      ],
      drawCallback: function( settings ) {
        var info = datatablesumklatur.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumklatur.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function setDatatablesumplt(){
  datatablesumplt = $('#datatable-sum-plt').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      info: false,
      searching: false,
      lengthChange: false,
      ajax: {
          url: urldatatablesumplt,
          type: 'GET',
          data: function (d) {
              d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nomenklatur_jabatan', name: 'nomenklatur_jabatan', searchable: true},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'tanggal_awal', name: 'tanggal_awal', searchable: true},
        { data: 'tanggal_akhir', name: 'tanggal_akhir', searchable: true}
      ],
      drawCallback: function( settings ) {
        var info = datatablesumplt.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumplt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function setDatatablesumalt(){
  datatablesumalt = $('#datatable-sum-alt').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      info: false,
      searching: false,
      lengthChange: false,
      ajax: {
          url: urldatatablesumalt,
          type: 'GET',
          data: function (d) {
              d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'pejabat', name: 'pejabat', searchable: true},
        { data: 'jabatan_alih_tugas', name: 'jabatan_alih_tugas', searchable: true},
        { data: 'tanggal_awal', name: 'tanggal_awal', searchable: true},
        { data: 'tanggal_akhir', name: 'tanggal_akhir', searchable: true}
      ],
      drawCallback: function( settings ) {
        var info = datatablesumalt.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumalt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function setDatatableSumIndependen(){
  datatablesumindependen = $('#datatable-sum-independen').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      info: false,
      searching: false,
      lengthChange: false,
      ajax: {
          url: urldatatablesumindependen,
          type: 'GET',
          data: function (d) {
              d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'nomenklatur_jabatan', name: 'nomenklatur_jabatan', searchable: true},
        { data: 'tanggal_awal_menjabat', name: 'tanggal_awal_menjabat', searchable: true},
        { data: 'tanggal_akhir_menjabat', name: 'tanggal_akhir_menjabat', searchable: true}
      ],
      drawCallback: function( settings ) {
        var info = datatablesumindependen.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumindependen.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}