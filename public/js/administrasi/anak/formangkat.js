$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {

    $('body').on('click','.cls-add-orang-anak',function(){
      $('#winformorang').removeData('bs.modal');
      winformorang(urlcreateorang, {}, 'Tambah Pejabat');
    });

    $('.kt-select2').select2({
        placeholder: "Pilih"
    });

    $('#id_talenta').select2({
        placeholder: "Pilih"
    });

    var pickerOptsGeneral = {
      orientation: "left",
      autoclose: true,
      format: 'dd/mm/yyyy'
    };

    $('.cls-datepicker').datepicker(pickerOptsGeneral);
    
    $('#tanggal_awal_menjabat').datepicker(pickerOptsGeneral).on('changeDate', function(ev){

        var startDate = new Date(ev.date);
        startDate.setFullYear(startDate.getFullYear() + 5);
        //startDate.setDate(startDate.getDate() + 1826);
        $('#tanggal_akhir_menjabat').datepicker('setDate', startDate);
    });

    $('#tanggal_akhir_menjabat').datepicker(pickerOptsGeneral);

		setFormValidate();
	});
});

function setFormValidate(){
    $('#form-angkat-anak').validate({
        rules: {
                             		               		                              		               		               
        },
        messages: {
                                                        		                   		                   
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
                   url: urlstoreangkat,
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
	                     datatableangkatanak.ajax.reload( null, false );
                       datatablesumangkatanak.ajax.reload( null, false );
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