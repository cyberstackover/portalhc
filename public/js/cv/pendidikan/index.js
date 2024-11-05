var datatable;

$(document).ready(function(){
	$('body').on('click','.pendidikan', function(){
		winform(urlcreate, {}, 'Tambah Data Pendidikan');
	});

  $('body').on('click','.pelatihan', function(){
    winform(urlcreate2, {}, 'Tambah Data Pelatihan');
  });

	$('body').on('click','.pendidikan-edit',function(){
    winform(urledit, {'id':$(this).data('id')}, 'Ubah Data Pendidikan');
  });

  $('body').on('click','.pelatihan-edit',function(){
    winform(urledit2, {'id':$(this).data('id')}, 'Ubah Data Pelatihan');
  });

	$('body').on('click','.pendidikan-delete',function(){
		onbtndelete(this);
	});

  $('body').on('click','.pelatihan-delete',function(){
    onbtndelete2(this);
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
  setDatatable2();
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
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'id_jenjang_pendidikan', name: 'id_jenjang_pendidikan', searchable: true},
        { data: 'perguruan_tinggi', name: 'perguruan_tinggi', searchable: true},
        { data: 'tahun', name: 'tahun', searchable: true},
        { data: 'kota', name: 'kota', searchable: true},
        { data: 'penghargaan', name: 'penghargaan', searchable: true},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatable.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });	
}

function setDatatable2(){
  datatable2 = $('#datatable2').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      ajax: urldatatable2,
      ajax: {
          url: urldatatable2,
          type: 'GET'
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'pengembangan_kompetensi', name: 'pengembangan_kompetensi', searchable: true},
        { data: 'kota', name: 'kota', searchable: true},
        { data: 'jenis_diklat', name: 'jenis_diklat', searchable: true},
        { data: 'tingkat_diklat', name: 'tingkat_diklat', searchable: true},
        { data: 'lama_hari', name: 'lama_hari', searchable: true},
        { data: 'tahun_diklat', name: 'tahun_diklat', searchable: true},
        { data: 'nomor_sertifikasi', name: 'kota', searchable: true},
        { data: 'action', orderable: false, searchable: false}
        // { data: 'tahun', name: 'tahun', searchable: true},
        // { data: 'gaji_pokok', name: 'gaji_pokok', searchable: true, sClass: 'text-right'},
        // { data: 'tantiem_board', name: 'tantiem_board', searchable: true, sClass: 'text-right'}
      ],
      drawCallback: function( settings ) {
        var info = datatable2.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatable2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function onbtndelete(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data pendidikan "+$(element).data('periode')+" ?",
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

function onbtndelete2(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data pelatihan "+$(element).data('periode')+" ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldelete2,
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