var datatable;

$(document).ready(function(){
	$('body').on('click','.first_table', function(){
		winform(urlcreate, {}, 'Tambah Data Pengalaman Lain');
	});

	$('body').on('click','.first_table-edit',function(){
        winform(urledit, {'id':$(this).data('id')}, 'Ubah Data Pengalaman Lain');
    });

	$('body').on('click','.first_table-delete',function(){
		onbtndelete(this);
	});

    $('body').on('click','.tidak-memiliki',function(){
        onbtntidakmemiliki(this);
    });

    $('body').on('click','.tidak-memiliki-cancel',function(){
        onbtntidakmemilikicancel(this);
    });
	
    $('.kt-select2').select2({
        placeholder: "Pilih"
    });

    $('body').on('change', '#form_tahun', function() {
        datatable.ajax.reload( null, false );
    });
  
    $('body').on('change', '#form_perusahaan', function() {
        datatable.ajax.reload( null, false );
    });

	setDatatable();
});

function setDatatable(){
  datatable = $('#datatable').on( 'preXhr.dt', function ( e, settings, processing ) {
            KTApp.block('.cls-content-data', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Sedang proses, silahkan tunggu ...'
            });
  })
  .on('xhr.dt', function ( e, settings, json, xhr ) {
      KTApp.unblock('.cls-content-data');
  }).DataTable({
      serverSide: true,
      processing: true,
      searching: false,
      search: {
          caseInsensitive: true
      },
      pageLength: 5,
      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
      searchHighlight: true,
      ajax: urldatatable,
      ajax: {
          url: urldatatable,
          type: 'GET'
      },
      language: {
        emptyTable: "Tidak Memiliki Pengalaman Sebagai Pembicara/Narasumber/Juri"
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'acara', name: 'acara', searchable: true},
        { data: 'penyelenggara', name: 'penyelenggara', searchable: true},
        { data: 'periode', name: 'periode', searchable: true},
        { data: 'lokasi', name: 'lokasi', searchable: true},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatable.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );

          if ( datatable.data().any() ) {
              $(".tidak-memiliki").hide();
              $(".tidak-memiliki-cancel").hide();
          }
      }
  });	
}

function onbtndelete(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data "+$(element).data('periode')+" ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldelete,
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
                   	  datatable.ajax.reload( null, false );
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

function onbtntidakmemiliki(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Tidak Memiliki Pengalaman Sebagai Pembicara/Narasumber/Juri ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Tidak Memiliki",
        cancelButtonText: "Cancel"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urltidakmemiliki,
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

                   if($(".tidak-memiliki").is(":visible")){
                        $(".tidak-memiliki").hide();
                        $(".tidak-memiliki-cancel").show();
                        $(".cls-add.first_table").hide();
                        $(".cls-add-disabled").show();
                    }else{
                        $(".tidak-memiliki").show();
                        $(".tidak-memiliki-cancel").hide();
                        $(".cls-add.first_table").show();
                        $(".cls-add-disabled").hide();
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

function onbtntidakmemilikicancel(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Memiliki Pengalaman Sebagai Pembicara/Narasumber/Juri ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Memiliki",
        cancelButtonText: "Cancel"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urltidakmemiliki,
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

                   if($(".tidak-memiliki").is(":visible")){
                        $(".tidak-memiliki").hide();
                        $(".tidak-memiliki-cancel").show();
                        $(".cls-add.first_table").hide();
                        $(".cls-add-disabled").show();
                   }else{
                        $(".tidak-memiliki").show();
                        $(".tidak-memiliki-cancel").hide();
                        $(".cls-add.first_table").show();
                        $(".cls-add-disabled").hide();
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
