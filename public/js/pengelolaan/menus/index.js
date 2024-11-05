$(document).ready(function(){
	$('#nestable_list_3').nestable();
	setTreeMenu();

	$('body').on('click','.cls-add',function(){
		winform(urlcreate, {}, 'Tambah Menu');
	});	

	$('body').on('click','.cls-button-edit',function(){
		winform(urledit, {'id':$(this).data('id')}, 'Ubah Menu');
	});		

  $('body').on('change', '.dd', function() {
      onNestChanged(this);
  });  

	$('body').on('click','.cls-button-delete',function(){
		onBtnDelete(this);
	});			
});

function setTreeMenu(){
    $.ajax({
       url: urlgettreemenu,
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

           $('#nestable_list_3').html(data.html);
           $('[data-toggle="tooltip"]').tooltip();
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

function onBtnDelete(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data menu "+$(element).data('label')+" ?",
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
                   	  setTreeMenu();
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

function onNestChanged(element){
         $.ajax({
          type: 'post',
          url: urlsubmitchangestructure,
          data:{'serialized' : $('.dd').nestable('serialize')},
          dataType : 'json',
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
                setTreeMenu();
          },
          error: function (jqXHR, exception) {
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