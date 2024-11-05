var datatable;
var portlet = new KTPortlet('kt_portlet_tools_6');

portlet.on('afterExpand', function(portlet) {
    $(".kt-select2").select2({
        placeholder: "Pilih...",
        allowClear: true
    });

});
$(document).ready(function(){
	$('body').on('click','.cls-add',function(){
		winform(urlcreate, {}, 'Tambah Talenta');
	});

    $('body').on('click','.cls-import',function(){
        winform(urlimport, {}, 'import Talenta');
    });

    $('body').on('click','.cls-minicv',function(){
        winform(urlminicv, {'id':$(this).data('id')}, 'Curriculum Vitae');
    })

    $('body').on('click','.cls-logstatus',function(){
        winform(urllogstatus, {'id':$(this).data('id')}, 'Log Status');
    })

    $('body').on('click','.cls-talentjabatan',function(){
        winform(urljabatantalent, {'id':$(this).data('id')}, 'Jabatan Talent');
        $('#id_kategori_data_talent,#id_kategori_jabatan_talent,#id_kategori_non_talent').select2({
            placeholder: "Pilih",
            allowClear: true
        });
    });

    $('body').on('click','.cls-editstatus',function(){
        winform(urleditstatus, {'id':$(this).data('id')}, 'Edit Status Talenta');
    });

	$('body').on('click','.cls-button-delete',function(){
		onbtndelete(this);
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
      ajax: urldatatable,
      ajax: {
          url: urldatatable,
          type: 'GET',
          data: function (d) {
              d.nama_lengkap = $('input[name=nama_lengkap]').val();
              d.nik = $('input[name=nik]').val();
              d.asal_instansi = $('select[name=asal_instansi]').val();
              d.jabatan = $('select[name=jabatan]').val();
              d.instansi = $('select[name=instansi]').val();
              d.kategori = $('select[name=kategori]').val();
              d.klaster = $('select[name=klaster]').val();
              d.perusahaan = $('select[name=perusahaan]').val();
          }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', orderable: false, searchable: false},
        { data: 'stalenta', name: 'stalenta', orderable: false, searchable: false},
        { data: 'jabatan', name: 'jabatan', orderable: false, searchable: false},
        { data: 'status_pengisian', orderable: false, className: "text-right", searchable: false},
        { data: 'talent_jabatan', orderable: false, searchable: false},
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

  $("#cari").click(function(){
      datatable.ajax.reload( null, false );
  });

  $("#reset").on('click',function(){
      $("#nama_lengkap").val('').trigger("change");
      $("#nik").val('').trigger("change");
      $("#asal_instansi").val('').trigger("change");
      $("#jabatan").val('').trigger("change");
      $("#instansi").val('').trigger("change");
      $("#kategori").val('').trigger("change");
      $("#klaster").val('').trigger("change");
      $("#perusahaan").val('').trigger("change");
      datatable.ajax.reload( null, false );
  });
}

function onbtndelete(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data talenta "+$(element).data('periode')+" ?",
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

function datatalentchange(id, id_talenta)
{
    $.ajax({
        type: 'post',
        data: {'id': id, 'id_talenta' : id_talenta},
        url: urldatatalent,
        dataType: 'json',
        success: function(data){
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

function jabatantalentchange(id, id_talenta)
{
    $.ajax({
        type: 'post',
        data: {'id': id, 'id_talenta' : id_talenta},
        url: urldatajabatantalent,
        dataType: 'json',
        success: function(data){
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

function nontalentchange(id, id_talenta)
{
    $.ajax({
        type: 'post',
        data: {'id': id, 'id_talenta' : id_talenta},
        url: urldatanontalent,
        dataType: 'json',
        success: function(data){
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
