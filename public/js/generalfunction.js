function winform(url, param, caption, tipe = 'post'){
      $.ajax({
         url: url,
         data:param,
         type:tipe,
         beforeSend: function(){
            KTApp.block('.cls-content-data', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Sedang proses, silahkan tunggu ...'
            });
         },
         success: function(html){
              KTApp.unblock('.cls-content-data');
              $('#winform .modal-title').text(caption);
              $('#winform .modal-body').html(html);
              // $("#winform").draggable({handle:".modal-title"});
              $('#winform').modal('show');
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

function winformorang(url, param, caption, tipe = 'post'){
      $.ajax({
         url: url,
         data:param,
         type:tipe,
         beforeSend: function(){
            KTApp.block('.cls-content-data', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Sedang proses, silahkan tunggu ...'
            });
         },
         success: function(html){
              KTApp.unblock('.cls-content-data');
              $('#winformorang .modal-title').text(caption);
              $('#winformorang .modal-body').html(html);
              // $("#winform").draggable({handle:".modal-title"});
              $('#winformorang').modal('show');
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

function onRemoveSpace(txb){
    txb.value = txb.value.replace(/\s+/g,"");
}

function onRemoveWhiteSpaceCharact(txb){
    txb.value = txb.value.replace(/[, ]|[: ]|[; ]|[' ]|[" ]|[~ ]|[` ]+/g, " ").trim();
}

function onAllKapitalize(txb){
  txb.value = txb.value.toUpperCase();
}

function onAllLowerCase(txb){
  txb.value = txb.value.toLowerCase();
}

function popupwindow(url, title, w, h){
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    window.focus();
}

function numericFilter(txb) {
    txb.value = txb.value.replace(/[^\0-9]/ig, "");
}

function loadContent(div,varurl,loadingmessage){
    $.ajax({
        headers:{
             'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        url: varurl,
        beforeSend: function(){
          if(loadingmessage){
            swal({
              title: "Proses ...",
              text: "Sedang Proses. Mohon untuk ditunggu sesaat.",
              imageUrl : loading_gif,
              showConfirmButton: false
            });
          }
        },
        success: function(response){
            $(div).html(response);
            if(loadingmessage){
              swal.closeModal();
            }
        },
        error: function (jqXHR, exception) {
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
            swal("Error System", msgerror+', coba ulangi kembali !!!', 'error');
        },
        dataType:"html"
    });
    return false;
}
