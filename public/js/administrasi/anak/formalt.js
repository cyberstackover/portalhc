$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {
    $('.kt-select2').select2({
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
        $('#tanggal_akhir_menjabat').datepicker('setDate', startDate);
    });

    $('#tanggal_akhir_menjabat').datepicker(pickerOptsGeneral);

		setFormValidate();
	});
});

function setFormValidate(){
    $('#form-alt-anak').validate({
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
                   url: urlstorealt,
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
	                     datatablealtanak.ajax.reload( null, false );
                       datatablesumaltanak.ajax.reload( null, false );
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

function onNamaJabat(sk_id){
    $.ajax({
        url: "/administrasi/bumn/getnamajabat?sk_id="+sk_id,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $("#id_talenta").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $("#id_talenta").append(contentData);
                 $("#id_talenta").trigger("change");
                                     
        }
    });
}

function onJabatan(sk_id,grup_id){
    $.ajax({
        url: "/administrasi/bumn/getjabatan?sk_id="+sk_id+"&grup_id="+grup_id,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $("#id_struktur_organ").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $("#id_struktur_organ").append(contentData);
                 $("#id_struktur_organ").trigger("change");
                                     
        }
    });
}

function onTglJabat(id_talenta, id_perusahaan){
    $.ajax({
        url: "/administrasi/bumn/gettgljabat?id_talenta="+id_talenta+"&id_perusahaan="+id_perusahaan,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 $("#tanggal_awal_menjabat").val(data[0].tanggal_awal);
                 $("#tanggal_akhir_menjabat").val(data[0].tanggal_akhir);
                                     
        }
    });
}