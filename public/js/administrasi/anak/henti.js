var datatablehentianak;
var datatablesumhentianak;

$(document).ready(function(){
	$('body').on('click','.cls-add-berhenti-anak',function(){

		winform(urlcreatehenti, {'id_surat_keputusan':$('input[name=id_surat_keputusan]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Tambah Pemberhentian Anak/Cucu');
	});

	$('body').on('click','.cls-button-edit-henti',function(){
		winform(urledithenti, {'id':$(this).data('id'),'id_surat_keputusan':$('input[name=id_surat_keputusan]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Ubah Pemberhentian Anak/Cucu');
	});

	$('body').on('click','.cls-button-delete-henti',function(){
		onbtndeletehenti(this);
	});
	
	setDatatableHentiAnak();
  setDatatableSumHentiAnak();
});

function setDatatableHentiAnak(){
  datatablehentianak = $('#datatable-berhenti-anak').on( 'preXhr.dt', function ( e, settings, processing ) {
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
	        url: urldatatablehenti,
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
        { data: 'tanggal_akhir_menjabat', name: 'tanggal_akhir_menjabat', searchable: true},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatablehentianak.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablehentianak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });	
}

function setDatatableSumHentiAnak(){
  datatablesumhentianak = $('#datatable-sum-berhenti-anak').on( 'preXhr.dt', function ( e, settings, processing ) {
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
        var info = datatablesumhentianak.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumhentianak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function onbtndeletehenti(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Pemberhentian Anak/Cucu ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeletehenti,
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
                   	  datatablehentianak.ajax.reload( null, false );
                      datatablesumhentianak.ajax.reload( null, false );
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