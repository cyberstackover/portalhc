var datatable;

$(document).ready(function(){
	$('body').on('click','.cls-add', function(){
		winform(urlcreate, {}, 'Tambah Data Hasil Assessment');
	});

	$('body').on('click','.cls-button-upload-spa',function(){
        winform(urlupload_spa, {'id':$(this).data('id')}, 'Upload File');
    });

	$('body').on('click','.cls-button-upload-short',function(){
        winform(urlupload_short, {'id':$(this).data('id')}, 'Upload File');
    });

	$('body').on('click','.cls-button-upload-full',function(){
        winform(urlupload_full, {'id':$(this).data('id')}, 'Upload File');
    });

	$('body').on('click','.cls-button-edit',function(){
        winform(urledit, {'id':$(this).data('id')}, 'Ubah Data Hasil Assessment');
    });

	$('body').on('click','.cls-button-delete',function(){
		onbtndelete(this);
	});
	
  $('.kt-select2').select2({
        placeholder: "Pilih"
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
      search: {
          caseInsensitive: true
      },
      pageLength: 5,
      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
      searchHighlight: true,
      ajax: urldatatable,
      ajax: {
          url: urldatatable,
          type: 'GET'
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'id_lembaga_assessment', name: 'id_lembaga_assessment', orderable: false, searchable: true},
        { data: 'tanggal', name: 'tanggal', orderable: false, searchable: true},
        { data: 'tanggal_expired', name: 'tanggal_expired', orderable: false, searchable: true},
        { data: 'spa_report', name: 'spa_report', orderable: false, searchable: true},
        { data: 'hasil', name: 'hasil', orderable: false, searchable: true},
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

function onbtndelete(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus hasil Assessment "+$(element).data('tanggal')+" ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldelete,
               data:{id:$(element).data('id')},
               type:'post',
               dataType:'json',
               beforeSend: function(){
                KTApp.block('.cls-content-data', {
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'primary',
                    message: 'Sedang proses, silahkan tunggu ...'
                });
               },
               success: function(data){
                   KTApp.unblock('.cls-content-data');

               swal.fire({
                    title: data.title,
                    html: data.msg,
                    type: data.flag,

                    buttonsStyling: false,

                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    confirmButtonClass: "btn btn-default"
               });

                   if(data.flag == 'success') {
                      datatable.ajax.reload( null, false );
                   }
               },
                error: function(jqXHR, exception) {
                  KTApp.unblock('.cls-content-data');
                  var msgerror = '';
                  if (jqXHR.status === 0) {
                      msgerror = 'jaringan tidak terkoneksi.';
                  } else if (jqXHR.status == 404) {
                      msgerror = 'Halaman tidak ditemukan. [404]';
                  } else if (jqXHR.status == 500) {
                      msgerror = 'Internal Server Error [500].';
                  } else if (exception === 'parsererror') {
                      msgerror = 'Requested JSON parse gagal.';
                  } else if (exception === 'timeout') {
                      msgerror = 'RTO.';
                  } else if (exception === 'abort') {
                      msgerror = 'Gagal request ajax.';
                  } else {
                      msgerror = 'Error.\n' + jqXHR.responseText;
                  }
                  swal.fire({
                      title: "Error System",
                      html: msgerror+', coba ulangi kembali !!!',
                      type: 'error',

                      buttonsStyling: false,

                      confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                      confirmButtonClass: "btn btn-default"
                  });  
                }
            });
        }
    }); 
}