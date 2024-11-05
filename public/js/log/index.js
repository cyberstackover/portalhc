var datatable;

$(function() {
    loadstatus();
});

$(document).ready(function(){
	
  $('body').on('click','.cls-button-edit',function(){
    winform(urledit, {'id':$(this).data('id')}, 'Ubah Status Penguncian');
  });

  $('body').on('click','.cls-button-delete',function(){
    onbtndelete(this);
  });

	setDatatable();
});

function loadstatus(){

    setDatatable(unit,tahun);
}

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
      destroy: true,
      responsive: true,
      bFilter: false,
      ajax: {
          url: urldatatable,
          type: 'GET'
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'description', name: 'description', searchable: true, orderable: false},
        { data: 'name', name: 'name', searchable: true, orderable: false},
        { data: 'created_at', name: 'created_at', searchable: true, orderable: false}
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