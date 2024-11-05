var datatableassesmen;
var datatablesumassesmen;

$(document).ready(function(){
	$('body').on('click','.cls-add-assesmen',function(){

		winform(urlcreateassesmen, {'id_talenta':$('input[name=id_talenta]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Tambah Assesmen');
	});

	$('body').on('click','.cls-button-edit-assesmen',function(){
		winform(urleditassesmen, {'id':$(this).data('id'),'id_talenta':$('input[name=id_talenta]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Ubah Assesmen');
	});

	$('body').on('click','.cls-button-delete-assesmen',function(){
		onbtndeleteassesmen(this);
	});
	
	setDatatableAssesmen();
  setDatatableSumAssesmen();
});

function setDatatableAssesmen(){
  datatableassesmen = $('#datatable-assesmen').on( 'preXhr.dt', function ( e, settings, processing ) {
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
	        url: urldatatableassesmen,
	        type: 'GET',
	        data: function (d) {
	            d.id_talenta = $('input[name=id_talenta]').val();
	        }
	  },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'nilai_asesmen_domestik', name: 'nilai_asesmen_domestik', searchable: true},
        { data: 'nilai_asesmen_global', name: 'nilai_asesmen_global', searchable: true},
        { data: 'penilaian', name: 'penilaian', searchable: true},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatableassesmen.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatableassesmen.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });	
}

function setDatatableSumAssesmen(){
  datatablesumassesmen = $('#datatable-sum-assesmen').on( 'preXhr.dt', function ( e, settings, processing ) {
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
          url: urldatatablesumassesmen,
          type: 'GET',
          data: function (d) {
              d.id_talenta = $('input[name=id_talenta]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'nilai_asesmen_domestik', name: 'nilai_asesmen_domestik', searchable: true},
        { data: 'nilai_asesmen_global', name: 'nilai_asesmen_global', searchable: true},
        { data: 'penilaian', name: 'penilaian', searchable: true},
      ],
      drawCallback: function( settings ) {
        var info = datatablesumassesmen.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumassesmen.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function onbtndeleteassesmen(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Assesmen ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeleteassesmen,
               data:{id:$(element).data('id'), id_talenta:$(element).data('id_talenta')},
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
                   	  datatableassesmen.ajax.reload( null, false );
                      datatablesumassesmen.ajax.reload( null, false );
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