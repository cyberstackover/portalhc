var datatablesumangkatanak;

$(document).ready(function(){
	
	setDatatableAngkatAnak();
});

function setDatatableAngkatAnak(){
  datatablesumangkatanak = $('#datatable-angkat-anak').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      search: {
          caseInsensitive: true
      },
      searchHighlight: true,
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
        var info = datatableangkatanak.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatableangkatanak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });	
}