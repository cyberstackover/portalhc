$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {
		setFormValidate();
    $('.kt-select2').select2({
        placeholder: "Pilih"
    });

    $('.kt-datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        orientation: "bottom left",
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    });

    $("#jumlah").inputmask('9.999.999.999', {
        numericInput: true
    });

    $(".kt-inputmask").inputmask({ alias : "currency", prefix: '', groupSeparator: ".", digits: 0 });
	});
});

function setFormValidate(){
    $('#form-board').validate({
        rules: {
               tahun:{
                       required: true
               },
               id_bumn:{
                       required: true
               },
               id_faktor_penghasilan:{
                       required: true
               },
               jumlah:{
                       required: true
               },
               id_mata_uang:{
                       required: true
               }           		               		                              		               		               
        },
        messages: {
                   tahun: {
                       required: "Tahun wajib diinput"
                   },
                   id_bumn:{
                           required: "BUMN wajib diinput"
                   },
                   id_faktor_penghasilan:{
                           required: "Faktor Penghasilan wajib diinput"
                   },
                   jumlah:{
                           required: "Jumlah wajib diinput"
                   },
                   id_mata_uang:{
                           required: "Mata Uang wajib diinput"
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