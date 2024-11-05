var datatableklaturanak;
var datatablesumklaturanak;

$(document).ready(function(){
	$('body').on('click','.cls-add-klatur-anak',function(){

		winform(urlcreateklatur, {'id_surat_keputusan':$('input[name=id_surat_keputusan]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Tambah Nomenklatur Anak/Cucu');
	});

	$('body').on('click','.cls-button-edit-klatur',function(){
		winform(urleditklatur, {'id':$(this).data('id'),'id_surat_keputusan':$('input[name=id_surat_keputusan]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Ubah Nomenklatur Anak/Cucu');
	});

	$('body').on('click','.cls-button-delete-klatur',function(){
		onbtndeleteklatur(this);
	});
	
	setDatatableKlaturAnak();
  setDatatableSumKlaturAnak();

});

function setDatatableKlaturAnak(){
  datatableklaturanak = $('#datatable-klatur-anak').on( 'preXhr.dt', function ( e, settings, processing ) {
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
	        url: urldatatableklatur,
	        type: 'GET',
	        data: function (d) {
	            d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
	            d.id_perusahaan = $('input[name=id_perusahaan]').val();
	        }
	  },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nomenklatur_jabatan', name: 'nomenklatur_jabatan', searchable: true},
        { data: 'nomenklatur_baru', name: 'nomenklatur_baru', searchable: true},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatableklaturanak.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatableklaturanak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });	
}

function setDatatableSumKlaturAnak(){
  datatablesumklaturanak = $('#datatable-sum-klatur-anak').on( 'preXhr.dt', function ( e, settings, processing ) {
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
        var info = datatablesumklaturanak.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumklaturanak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function onbtndeleteklatur(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Perubahan Nomenklatur Anak/Cucu?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeleteklatur,
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
                   	  datatableklaturanak.ajax.reload( null, false );
                      datatablesumklaturanak.ajax.reload( null, false );
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