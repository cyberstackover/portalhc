var datatable;

$(document).ready(function(){
    $('body').on('click','.cls-minicv',function(){
        winform(urlminicv, {'id':$(this).data('id')}, 'Curriculum Vitae');
    })

    $('body').on('click','.cls-logstatus',function(){
        winform(urllogstatus, {'id':$(this).data('id')}, 'Log Status');
    })

    $('.kt-select2').select2({
            placeholder: "Pilih"
        });

    $('#nama_lengkap').on('change', function() {
        datatable.ajax.reload( null, false );
    });
    
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
      searching: false,
      ajax: urldatatable,
      ajax: {
          url: urldatatable,
          type: 'GET',
          data: function (d) {
              d.nama_lengkap = $('#nama_lengkap').val();
          }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', orderable: false, searchable: false},
        { data: 'jabatan', name: 'jabatan', orderable: false, searchable: false},
        { data: 'status_pengisian', orderable: false, className: "text-right", searchable: false},
        { data: 'log_status', orderable: false, className: "text-center", searchable: false},
        { data: 'action', className: "text-center", orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatable.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });

  $("#cari").click(function(){
      datatable.ajax.reload( null, false );
  });

  $("#reset").on('click',function(){
      $("#nama_lengkap").val('').trigger("change");
      datatable.ajax.reload( null, false );
  }); 
}
