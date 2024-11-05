var datatable;

$(document).ready(function(){
	$('body').on('click','.cls-add',function(){
		winform(urlcreate, {}, 'Tambah Board');
	});

	$('body').on('click','.cls-button-edit',function(){
		winform(urledit, {'id':$(this).data('id')}, 'Ubah Board');
	});

	$('body').on('click','.cls-button-delete',function(){
		onbtndelete(this);
	});
	
  $('.kt-select2').select2({
        placeholder: "Pilih"
    });

  $('body').on('change', '#form_tahun', function() {
    datatable.ajax.reload( null, false );
  });
  
  $('body').on('change', '#form_perusahaan', function() {
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
      search: {
          caseInsensitive: true
      },
      searchHighlight: true,
      ajax: urldatatable,
      ajax: {
          url: urldatatable,
          type: 'GET',
          data: function (d) {
              d.tahun = $('#form_tahun').val();
          }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama', name: 'nama', searchable: true},
        { data: 'tahun', name: 'tahun', searchable: true},
        { data: 'gaji_pokok', name: 'gaji_pokok', searchable: true, sClass: 'text-right'},
        { data: 'tantiem_board', name: 'tantiem_board', searchable: true, sClass: 'text-right'}
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
        text: "Yakin hapus data Board "+$(element).data('nama')+" ?",
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