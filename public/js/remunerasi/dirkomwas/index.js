var datatable;

$(document).ready(function(){
  $('body').on('click','.cls-add',function(){
    winform(urlcreate, {}, 'Tambah Remunerasi Dirkomwas');
  });

  $('body').on('click','.cls-button-edit',function(){
    winform(urledit, {'id':$(this).data('id')}, 'Ubah Remunerasi Dirkomwas');
  });

	$('body').on('click','.cls-button-delete',function(){
		onbtndelete(this);
	});

  $('body').on('change', '#form_tahun', function() {
    setresult();
  });

  $('body').on('change', '#form_perusahaan', function() {
    setresult();
  });

  setresult();

  $('.kt-select2').select2({
        placeholder: "Pilih",
        allowClear: false
    });
	
  
});


function setresult(){
  $.ajax({
      type: 'get',
      url: urldetail,
      data: {
        perusahaan: $('#form_perusahaan').val(), 
        tahun: $('#form_tahun').val()
      },
      beforeSend: function(){
          KTApp.block('.cls-content-data', {
              overlayColor: '#000000',
              type: 'v2',
              state: 'primary',
              message: 'Sedang proses, silahkan tunggu ...'
          });
      },
      success: function(response){
          KTApp.unblock('.cls-content-data');
          $('#remun-result').html(response);
          $('.tree').treegrid({
            initialState : 'expanded',
            treeColumn : 1,
            indentTemplate : '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>'
          });
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
      },
      dataType:"html"
   });
   return false;
}


function onbtndelete(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Remunerasi Dirkomwas "+$(element).data('dirkomwas')+" ?",
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
                      // datatable.ajax.reload( null, false );
                      location.reload(); 
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