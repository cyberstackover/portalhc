$(document).ready(function(){

    onloaddireksi();

    $('body').on('click','.tambahHeading',function(){
      winform(urlcreategrupjabatan, {'perusahaan_id':$(this).data('perusahaanid')}, 'Tambah Grup Jabatan');
    });

    $('body').on('click','.addAnak',function(){
      winform(urlcreateanak, {'id':$(this).data('id'),'parent_id':$(this).data('parentid'),'perusahaan_id':$(this).data('perusahaanid')}, 'Tambah Anak');
    });

    $('body').on('click','.deleteDataDireksi',function(){
      onbtndelete(this);
    });

    $('body').on('click','.deleteAllData',function(){
      onbtndeleteall(this);
    });

    $('body').on('click','.updateDataAnak',function(){
      winform(urleditanak, {'id':$(this).data('id')}, 'Ubah Anak');
    });
});

function onloaddireksi() {
   perusahaan_id = $("#perusahaan_id").val();
   
   
   if (perusahaan_id == '') {
       swal.fire("Gagal pencarian!", "Perusahaan harus dipilih", "warning");
   }   
   
   
   var urlpage = "/organ/komposisi/showkomposisi"; 
   
   if (perusahaan_id != '') {
       loadContentdireksi("#divresultdireksi", urlpage+"?perusahaan_id="+perusahaan_id);
   }
   return false;
}

function loadContentdireksi(div,varurl){
     
     $.ajax({
        url: varurl,
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
                    
            $(div).html(response);
            $('.tree').treegrid({
                'saveState': false,
                'initialState' : 'expanded'
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
              swal.fire("Error System", msgerror+', coba ulangi kembali !!!', 'error');
              $(processing_div).dialog('close').remove();
        },
        dataType:"html"
     });
     return false;
}

function onbtndeletedireksi(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Direksi ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeletedireksi,
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
                      onloaddireksi();
                      onloadkomwas();
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

function onbtndelete(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeleteanak,
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
                      onloaddireksi();
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

function onbtndeleteall(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus Semua data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus Semua data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeleteall,
               data:{perusahaan_id:$(element).data('perusahaanid')},
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
                      onloaddireksi();
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

function submitAktif(id, bolehNambah)
{ 
    var required = (bolehNambah)?'t':'f';
    $.ajax({
         url: urlchangeaktif,
         data:{id:id, required:required},
         type:'post',
         dataType:'json',               
         success: function(data){
             swal.closeModal();
          
             swal.fire(data.title, data.msg, data.flag);
          
             if(data.flag == 'success') {
                onloaddireksi();
             }
         },
          error: function(jqXHR, exception) {
                  swal.closeModal();
                  var msgerror = ''; 
                  if (jqXHR.status === 0) {
                      msgerror = 'jaringan tidak terkoneksi.';
                  } else if (jqXHR.status == 404) {
                      msgerror = 'Halamam tidak ditemukan. [404]';
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
                  swal.fire('Error', msgerror, 'error');
          }                           
      });
}