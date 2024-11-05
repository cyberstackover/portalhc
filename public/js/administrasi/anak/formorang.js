$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {

    $('.kt-select2').select2({
        placeholder: "Pilih"
    });
    validasiNIK();
    $('.kewarganegaraan').change(function () {
      kewarganegaraan = $(this).val();
      console.log(kewarganegaraan);
      $('#nik').val('');
      validasiNIK();
    })
    setFormOrangValidate();
	});
});

var nik = '';
function validasiNIK(){
  $('#nik').keypress(function (e) {
      var kewarganegaraan = $("input[type='radio'][name='kewarganegaraan']:checked").val();
      console.log(kewarganegaraan)
      if(kewarganegaraan=='WNI'){
        if (e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) {
          e.preventDefault();
        } else{
          nik = $(this).val();
          nik_array = nik.split("");
          if(nik_array.length>0){
            if(nik_array[0]==0){
              $('#nik').val('');
            } else if(nik_array.length>15){
                e.preventDefault();
                $('#error_msg').html(`NIK tidak boleh lebih dari 16 digit`);
              } else{
                $('#error_msg').html('');
              }
          }
        }
      }
    })
}

function setFormOrangValidate(){
    $('#form-orang-anak').validate({
        rules: {
               nama_lengkap:{
                     required: true,
                     maxlength: 256
                },
                nik:{
                     required: true,
                     remote: {
                       url: urlchecknik,
                       type: 'get',
                       dataType : 'json',
                       data: {
                         nik: function() {
                           return $( "#nik" ).val();
                         }
                       }
                     }
                },                                                                                                      
        },
        messages: {
               nik: {
                    remote: "NIK Sudah digunakan!!"
                }                                                                                      
        },
        // ignore: ':hidden:not(.summernote)',          
        highlight: function(element) {
            $(element).closest('.form-control').addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).closest('.form-control').removeClass('is-invalid');
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        errorPlacement: function(error, element) {
            if(element.parent('.validated').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
       submitHandler: function(form){
               var typesubmit = $("input[type=submit][clicked=true]").val();

               $(form).ajaxSubmit({
                   type: 'post',
                   url: urlstoreorang,
                   data: {source : typesubmit},
                   dataType : 'json',
                   beforeSend: function(){
                  KTApp.block('.kt-form', {
                      overlayColor: '#000000',
                      type: 'v2',
                      state: 'primary',
                      message: 'Sedang proses, silahkan tunggu ...'
                  });
                   },
                   success: function(data){
                     KTApp.unblock('.kt-form');

                 swal.fire({
                      title: data.title,
                      html: data.msg,
                      type: data.flag,

                      buttonsStyling: false,

                      confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                      confirmButtonClass: "btn btn-default"
                 });                     

                     if(data.flag == 'success') {
                       $("#id_talenta").append($("<option></option>").attr("selected", "selected").attr("value", data.key).text(data.text));
                        $("#id_talenta").select2("destroy");
                        $("#id_talenta").select2({
                            placeholder: "Pilih..."
                        });
                        $("#id_talenta").trigger("change");
                        $('#winformorang').modal('hide');
                        $('#winformorang').removeData('bs.modal');

                     }
                   },
                   error: function(jqXHR, exception){
                     KTApp.unblock('.kt-form');
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
               return false;
       }
    });   
}