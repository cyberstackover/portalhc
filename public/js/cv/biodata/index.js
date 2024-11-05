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

  $('#suku').select2({
        placeholder: "Pilih",
        allowClear: false
    });

  $('#id_jenis_asal_instansi').select2({
        placeholder: "Pilih"
    });

    $('#id_asal_instansi').select2({
        placeholder: "Pilih"
    });

  $('.kt-select2').select2({
        placeholder: "Pilih",
        allowClear: false
    });
	
  
});

function onAsalInstansi(id_jenis_asal_instansi){
    $.ajax({
        url: "/administrasi/kelengkapansk/getasalinstansi?id_jenis_asal_instansi="+id_jenis_asal_instansi,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $("#id_asal_instansi").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $("#id_asal_instansi").append(contentData);
                 $("#id_asal_instansi").trigger("change");
                                     
        }
    });
}


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