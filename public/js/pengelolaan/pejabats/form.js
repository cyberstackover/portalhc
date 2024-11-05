$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {
    $('#id_talenta').select2({
        placeholder: "Pilih"
    });

    setKategoriUser();
    setBumn();
		setFormValidate();
    onReadEvent();
	});
});

function setFormValidate(){
    $('#form-user').validate({
        rules: {
               kategori_user_id:{
                       required: true
               },
               name:{
                       required: true
               },
               email:{
                       required: true
               }               		               		                              		               		               
        },
        messages: {
                   name: {
                       required: "Nama wajib diinput"
                   },
                   email: {
                       required: "Email wajib diinput"
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
                   url: urlstore,
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
	                     $('#winform').modal('hide');
	                     datatable.ajax.reload( null, false );
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

function onReadEvent(){

  $('.cls-username').keyup(function(){
    onRemoveWhiteSpaceCharact(this);
  }); 

  $('.cls-check-user').click(function(){
    onCheckUserName();
  });

  //piihan kategori user
  $('#kategori_user_id').change(function(){
    if(Boolean($(this).find(':selected').data('ad'))){
      $("input[name='name']").prop('readonly', true).css({"cursor":"not-allowed"});
            $("input[name='email']").prop('readonly', true).css({"cursor":"not-allowed"});
    }else{
            $("input[name='name']").prop('readonly', false).removeAttr( 'style' );
            $("input[name='email']").prop('readonly', false).removeAttr( 'style' );
    }

    switch(parseInt($(this).find(':selected').data('inputan'))){
      //BUMN
      case 1 : $('.divasalinstansi').css('display', 'none');
               $('.divbumn').removeAttr('style');

               $('#id_bumn').rules('add', {
                required: true,
                messages: {
                  required: "BUMN wajib dipilih"
                }
               });                     

               $('#asal_instansi').rules('remove');
      break;

      //Asal Instansi
      case 2 : $('.divasalinstansi').removeAttr('style');
               $('.divbumn').css('display', 'none');

               $('#asal_instansi').rules('add', {
                required: true,
                maxlength: 256,
                messages: {
                  required: "Asal instansi wajib diinput",
                  maxlength: "Asal instansi maksimal 256 karakter"
                }
               });                     

                             
      break;  

            default: $('.divasalinstansi').css('display', 'none');
                     $('.divbumn').css('display', 'none');

                     $('#asal_instansi').rules('remove');

                     $('#id_bumn').rules('remove');    
            break;          
    }
  });

  let actionform = $('#actionform').val();
  if(actionform === 'update'){
    let bumnhidden = jQuery.parseJSON($('#bumnhidden').val());
    let kategori = jQuery.parseJSON($('#kategori').val());

        $('#kategori_user_id').select2("trigger", "select", {
          data: {
            'id':kategori.id,
            'text':kategori.kategori
          }              
        });   

        if(bumnhidden != ''){
          $('#id_bumn').select2("trigger", "select", {
            data: {
              'id':bumnhidden.id,
              'text':bumnhidden.nama_lengkap
            }              
          });
        } 

    $(".cls-inti").prop('readonly', true).css({"cursor":"not-allowed"});   
  }   
}



function onCheckUserName(){
    let username = $('#username').val();

    let array_error = [];

    if(username === null || username === '') {
       array_error.push("Username wajib diinput");
    }

    if(array_error.length > 0){
      //jika ada error
      swal.fire({
            title: "Pemberitahuan",
            html: array_error.join(',<br/>'),
            type: 'warning'
      });       
    }else{
      checkUser(username);  
    }    
}

function setBumn(){
    $('#id_bumn').select2({
        width:'100%',
        allowClear: true,
        placeholder: '[ - Pilih BUMN - ]',
        ajax: {
            url: urlfetchbumnactive,
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, 
                    page: params.page
                };
            },
            processResults: function(data, params) {
                return {
                    results: data.item
                }
            },
            cache: true
        }
    }); 
}

function setKategoriUser(){
    $('#kategori_user_id').select2({width:'100%'});
    // $('#kategori_user_id').select2({
    //     width:'100%',
    //     allowClear: true,
    //     placeholder: '[ - Pilih Kategori User - ]',
    //     ajax: {
    //         url: urlfetchkategoriuser,
    //         dataType: 'json',
    //         data: function(params) {
    //             return {
    //                 q: params.term, 
    //                 page: params.page
    //             };
    //         },
    //         processResults: function(data, params) {
    //             return {
    //                 results: data.item
    //             }
    //         },
    //         cache: false
    //     },
     //    templateSelection: function(container) {
     //        $(container.element).attr("data-ad", container.ad);
     //        $(container.element).attr("data-inputan", container.inputan);
     //        return container.text;
     //    }        
    // });  
}

function checkUser(username){
      $.ajax({
         url: urlcheckuser,
         data:{
          username:username
         },
         type:'post',
         dataType:'json',
         beforeSend: function(){
              KTApp.block('#form-user', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
         },
         success: function(rest){
             KTApp.unblock('#form-user');

             if(Boolean(rest.data)){
              //jika true
              if(Boolean(rest.access_portal)){
                //jika AD
                swal.fire({
                    title: "Pemberitahuan",
                    html: "Username <strong>"+username+"</strong> Sudah Terdaftar di Middleware",
                    type: 'warning',
                    buttonsStyling: false,
                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    confirmButtonClass: "btn btn-default"
                  });
               //$('#name').val(rest.data.name);
               //$('#email').val(rest.data.email);           
              }else{
                swal.fire({
                  title: "Pemberitahuan",
                  html: "Username <strong>"+username+"</strong> Belum Punya Akses",
                  type: 'success',
                  buttonsStyling: false,
                  confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                  confirmButtonClass: "btn btn-default"
                });
                $('#name').val(rest.data.name);
               $('#email').val(rest.data.email);                  
              }
             }else{
              //jika false
              /*swal.fire({
                title: "Pemberitahuan",
                html: rest.status,
                type: 'error',
                buttonsStyling: false,
                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                confirmButtonClass: "btn btn-default"
              });*/
              swal.fire({
                  title: "Pemberitahuan",
                  html: "Username <strong>"+username+"</strong> Belum Terdaftar",
                  type: 'warning',
                  buttonsStyling: false,
                  confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                  confirmButtonClass: "btn btn-default"
                });               
             }
         },
          error: function(jqXHR, exception) {
                KTApp.unblock('#form-user');
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

function onNamaPejabat(id_talenta){
    $.ajax({
        //url: "/administrasi/bumn/gettgljabat?id_talenta="+id_talenta+"&id_perusahaan="+id_perusahaan,
        url: "/pengelolaan/pejabats/getnamapejabat?id_talenta="+id_talenta,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 $("#username").val(data[0].email);
                 $("#name").val(data[0].nama_lengkap);
                 $("#email").val(data[0].email);
                                     
        }
    });
}

function checkUser(username){
      $.ajax({
         url: urlcheckuser,
         data:{
          username:username
         },
         type:'post',
         dataType:'json',
         beforeSend: function(){
              KTApp.block('#form-user', {
                  overlayColor: '#000000',
                  type: 'v2',
                  state: 'primary',
                  message: 'Sedang proses, silahkan tunggu ...'
              });
         },
         success: function(rest){
             KTApp.unblock('#form-user');

             if(Boolean(rest.data)){
              //jika true
              if(Boolean(rest.access_portal)){
                //jika AD
                swal.fire({
                    title: "Pemberitahuan",
                    html: "Username <strong>"+username+"</strong> Sudah Terdaftar di Kementerian BUMN",
                    type: 'warning',
                    buttonsStyling: false,
                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    confirmButtonClass: "btn btn-default"
                  });
               //$('#name').val(rest.data.name);
               //$('#email').val(rest.data.email);           
              }else{
                swal.fire({
                  title: "Pemberitahuan",
                  html: "Username <strong>"+username+"</strong> Belum Punya Akses",
                  type: 'success',
                  buttonsStyling: false,
                  confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                  confirmButtonClass: "btn btn-default"
                });
                $('#name').val(rest.data.name);
               $('#email').val(rest.data.email);                  
              }
             }else{
              //jika false
              /*swal.fire({
                title: "Pemberitahuan",
                html: rest.status,
                type: 'error',
                buttonsStyling: false,
                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                confirmButtonClass: "btn btn-default"
              });*/
              swal.fire({
                  title: "Pemberitahuan",
                  html: "Username <strong>"+username+"</strong> Belum Terdaftar",
                  type: 'warning',
                  buttonsStyling: false,
                  confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                  confirmButtonClass: "btn btn-default"
                });               
             }
         },
          error: function(jqXHR, exception) {
                KTApp.unblock('#form-user');
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