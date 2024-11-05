var datatable;

$(document).ready(function(){

    setDatatable();
});

function setDatatable(){
  datatable = $('#datatable').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      ajax: urldatatable,
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'bumn_nama', name: 'bumn_nama', searchable: true},
        { data: 'kelas_nama', name: 'kelas_nama', searchable: true},
        { data: 'jumlah_direksi', name: 'jumlah_direksi', searchable: false, className: "text-right"},
        { data: 'jumlah_dirkomwas', name: 'jumlah_dirkomwas', searchable: false, className: "text-right"},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatable.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}