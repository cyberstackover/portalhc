$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {
		$('.cls-routing').keyup(function(){
			onRemoveSpace(this);
		});
		setParentSelect2();
		setFormValidate();
		onReadEvent();
	});
});

function setParentSelect2(){
    $('#parent_id').select2({
        width:'100%',
        allowClear: true,
        placeholder: '[ - Pilih Parent Menu - ]',
        ajax: {
            url: urlfetchparentmenu,
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

function setFormValidate(){
    $('#form-menu').validate({
        rules: {
               label:{
                       required: true,
                       maxlength: 255
               },
               parent_id:{
                       required: true
               },               
               route_name:{
                       required: false,
                       maxlength: 256
               }                            		               		                              		               		               
        },
        messages: {
                   label: {
                       required: "Nama menu wajib diinput",
                       maxlength: "Nama menu maksimal 255 karakter"
                   },
                   parent_id: {
                       required: "Parent menu wajib dipilih"
                   },                   
                   route_name: {
                       required: "Routing menu wajib diinput",
                       maxlength: "Routing menu maksimal 256 karakter"
                   }                                                       		                   		                   
        },       
        ignore: [],
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
	                     setTreeMenu();
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
	let actionform = $('#actionform').val();
	if(actionform == 'update'){
	   let parent = JSON.parse($('#parent_id_hidden').val());	
       $('#parent_id').select2("trigger", "select", {
       	  data: {
       	  	'id':parseInt(parent.id),
       	  	'text':parent.text
       	  }
       	});		
	}
}