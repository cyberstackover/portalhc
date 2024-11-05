$(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        var $target = $(e.target);
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {
        var $active = $('.nav-tabs li>.active');
        $active.parent().next().find('.nav-link').removeClass('disabled');
        nextTab($active);
    });
    
    $(".prev-step").click(function (e) {
        var $active = $('.nav-tabs li>a.active');
        prevTab($active);
    });

    $('body').on('click','.save-tambah2',function(){
        onbtnsavetambah2(this);
      });

    
});

function onbtnsavetambah2(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin Simpan Kelengkapan SK ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Simpan data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urlsavetambah2,
               data:{id:$('input[name=id_talenta]').val()},
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
                      var base_url = $('input[name=base_url]').val()
                      //window.location = "index";
                      //production
                      //window.location = "http://sdmdev.bumn.go.id/administrasi/bumn/index";
                      //stage
                      window.location = base_url+"/administrasi/kelengkapansk/index";
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

function goBack() {
  window.history.back();
}

function nextTab(elem) {
    $(elem).parent().next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).parent().prev().find('a[data-toggle="tab"]').click();
}

