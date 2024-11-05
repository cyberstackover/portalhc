$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {
		setFormValidate();

    $('.kt-select2').select2({
        placeholder: "Pilih"
    });

    
    $("body").on('click', '#tambah-penghasilan', function(e) {
      //save penghasilan
      $.ajax({
        url: 'store-data-penghasilan',
        refresh: true,
        type:'post',
        data: {
          'id_talenta' : $('#id_talenta').val(),
          'tahun' : $('#tahun').val(),
          'gaji_pokok' : $('#gaji_pokok').val(),
          'tantiem' : $('#tantiem').val(),
          'tunjangan' : $('#tunjangan').val(),
          'takehomepay' : $('#takehomepay').val()
        },
        dataType:'json',
        beforeSend: function(){
            $('.processing').show();
            
        },
        success: function(res){
            $('.processing').hide();

            $('#tabel-penghasilan tr:last').after('<tr><td>'+res.data.tahun+'</td><td>'+res.data.gaji_pokok+'</td><td>'+res.data.tantiem+'</td><td>'+res.data.tunjangan+'</td><td>'+res.data.takehomepay+'</td><td><a href="#" class="btn btn-icon btn-sm mr-2 btn-danger"><i class="flaticon2-trash"></i></a></td></tr>');
        },
        error: function(jqXHR, exception) {

        }
      });
    });

    // lanjut
    $("body").on('click', '.lanjut-step-2', function(e) {
      //save talenta
      $.ajax({
        url: 'store-asal-instansi',
        refresh: true,
        type:'post',
        data: {
          'id_talenta' : $('#id_talenta').val(),
          'id_jenis_asal_instansi' : $('#id_jenis_asal_instansi').val(),
          'id_asal_instansi' : $('#id_asal_instansi').val(),
          'jabatan_asal_instansi' : $('#jabatan_asal_instansi').val()
        },
        dataType:'json',
        beforeSend: function(){
            $('.processing').show();
            
        },
        success: function(data){
            datatable.ajax.reload( null, false );
            $('.a-step-2').trigger('click');
            $('.progress-bar').width('50%').text('50%');
            $('.processing').hide();
        },
        error: function(jqXHR, exception) {

        }
      });
    });

    $("body").on('click', '.lanjut-step-3', function(e) {
      //save data asesmen
      $.ajax({
        url: 'store-data-assesmen',
        refresh: true,
        type:'post',
        data: {
          'id_grup_jabatan' : $('#id_grup_jabatan').val(),
          'id_talenta' : $('#id_talenta').val(),
          'id_perusahaan' : $('input[name=id_perusahaan]').val(),
          'nilai_asesmen_global' : $('#nilai_asesmen_global').val(),
          'nilai_asesmen_domestik' : $('#nilai_asesmen_domestik').val(),
          'penilaian' : $('#penilaian').val()
        },
        dataType:'json',
        beforeSend: function(){
            $('.processing').show();
        },
        success: function(data){
            datatable.ajax.reload( null, false );
            $('.a-step-3').trigger('click');
            $('.progress-bar').width('75%').text('75%');
            $('.processing').hide();
        },
        error: function(jqXHR, exception) {

        }
      });
    });

    $("body").on('click', '.lanjut-step-4', function(e) {
      $.ajax({
        url: 'store-prosentase',
        refresh: true,
        type:'post',
        data: {
          'id_talenta' : $('#id_talenta').val(),
          'prosentase' : 75
        },
        dataType:'json',
        beforeSend: function(){
            $('.processing').show();
        },
        success: function(data){
            datatable.ajax.reload( null, false );
            $('.a-step-4').trigger('click');
            $('.progress-bar').width('100%').text('100%');
            $('.processing').hide();
        },
        error: function(jqXHR, exception) {

        }
      });
    });

    $("body").on('click', '.selesai', function(e) {
      $.ajax({
        url: 'store-prosentase',
        refresh: true,
        type:'post',
        data: {
          'id_talenta' : $('#id_talenta').val(),
          'prosentase' : 100
        },
        dataType:'json',
        beforeSend: function(){
            $('.processing').show();
        },
        success: function(data){
            datatable.ajax.reload( null, false );
            $('.processing').hide();
            $('.modal').modal('hide');
        },
        error: function(jqXHR, exception) {

        }
      });
    });

    // kembali
    $("body").on('click', '.kembali-step-1', function(e) {
      $('.a-step-1').trigger('click');
      $('.progress-bar').width('25%').text('25%');
    });

    $("body").on('click', '.kembali-step-2', function(e) {
      $('.a-step-2').trigger('click');
      $('.progress-bar').width('50%').text('50%');
    });

    $("body").on('click', '.kembali-step-3', function(e) {
      $('.a-step-3').trigger('click');
      $('.progress-bar').width('75%').text('75%');
    });

    $("body").on('click', '.kembali-step-4', function(e) {
      $('.a-step-4').trigger('click');
      $('.progress-bar').width('100%').text('100%');
    });
    
	});
});

function setFormValidate(){
    $('#file-kelengkapan').validate({
        rules: {
               id_jenis_file_pendukung:{
                       required: true
               },
               file_pendukung:{
                       required: true
               }           		               		                              		               		               
        },
        messages: {
                   id_jenis_file_pendukung: {
                       required: "Pilih Jenis File Pendukung"
                   },
                   file_pendukung: {
                       required: "File Pendukung harus diisi"
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
               $(form).ajaxSubmit({
                   type: 'post',
                   url: urlfilekelengkapan,
                   data: {},
                   dataType : 'json',
                   beforeSend: function(){

                   },
                   success: function(res){
                      var base_url = window.location.origin;
	                    $('#table-file-pendukung tr:last').after('<tr><td>'+res.data.jenis_file_pendukung+'</td><td><a href="'+base_url+'/'+res.data.filename+'" target="_blank">'+res.data.filename.replace("storage/kelengkapansk_files/", "  ")+'</a></td><td><a href="#" class="btn btn-icon btn-sm mr-2 btn-danger"><i class="flaticon2-trash"></i></a></td></tr>');
                   },
                   error: function(jqXHR, exception){
                      	                               
                   }
               });
               return false;
       }
    });		
}