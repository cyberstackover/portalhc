var datatableinstansi;
var datatablesuminstansi;

$(document).ready(function(){
	$('body').on('click','.cls-add-instansi',function(){

		winform(urlcreateinstansi, {'id_talenta':$('input[name=id_talenta]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Tambah Instansi');
	});

	$('body').on('click','.cls-button-edit-instansi',function(){
		winform(urleditinstansi, {'id':$(this).data('id'),'id_talenta':$('input[name=id_talenta]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Ubah Instansi');
	});

	$('body').on('click','.cls-button-delete-instansi',function(){
		onbtndeleteinstansi(this);
	});
	
	setDatatableInstansi();
  setDatatableSumInstansi();
});

function setDatatableInstansi(){
  datatableinstansi = $('#datatable-instansi').on( 'preXhr.dt', function ( e, settings, processing ) {
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
	        url: urldatatableinstansi,
	        type: 'GET',
	        data: function (d) {
	            d.id_talenta = $('input[name=id_talenta]').val();
	        }
	  },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'nama_instansi', name: 'nama_instansi', searchable: true},
        { data: 'nama_asal_instansi', name: 'nama_asal_instansi', searchable: true},
        { data: 'jabatan_asal_instansi', name: 'jabatan_asal_instansi', searchable: true},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatableinstansi.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatableinstansi.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });	
}

function setDatatableSumInstansi(){
  datatablesuminstansi = $('#datatable-sum-instansi').on( 'preXhr.dt', function ( e, settings, processing ) {
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
          url: urldatatablesuminstansi,
          type: 'GET',
          data: function (d) {
              d.id_talenta = $('input[name=id_talenta]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'nama_instansi', name: 'nama_instansi', searchable: true},
        { data: 'nama_asal_instansi', name: 'nama_asal_instansi', searchable: true},
        { data: 'jabatan_asal_instansi', name: 'jabatan_asal_instansi', searchable: true},
      ],
      drawCallback: function( settings ) {
        var info = datatablesuminstansi.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesuminstansi.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function onbtndeleteinstansi(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Instansi ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeleteinstansi,
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
                   	  datatableinstansi.ajax.reload( null, false );
                      datatablesuminstansi.ajax.reload( null, false );
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