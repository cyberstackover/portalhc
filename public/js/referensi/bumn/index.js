var datatable;

$(document).ready(function(){
	// $('body').on('click','.cls-add',function(){
	// 	winform(urlcreate, {}, 'Tambah BUMN');
	// });

    setDatatable();
    $('a[href="#grup"]').on('click',function(){
        $(".cari").hide();
       });
       $('a[href="#induk"]').on('click',function(){
        $(".cari").show();
       });

	$('body').on('click','.cls-button-edit',function(){
		winform(urledit, {'id':$(this).data('id')}, 'Ubah BUMN');
	});

    $("body").on("click", "#is_active", function () {
        onbtnaktif(this);
    });

	// $('body').on('click','.cls-button-delete',function(){
	// 	onbtndelete(this);
	// });

	

	// setDatatable();

  $("#cari").click(function(){
    var url = window.location.origin + '/referensi/bumn/index';
    var perusahaan = $('#perusahaan').val();
    var id_jenis_perusahaan = $('#id_jenis_perusahaan').val();

    window.location.href = url + '?perusahaan=' + perusahaan + '&id_jenis_perusahaan=' + id_jenis_perusahaan;

  });

  $("#reset").on('click',function(){
      $("#id_jenis_perusahaan").val('').trigger("change");
      $('#perusahaan').val('');
  });
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
      aLengthMenu: [[25, 50, 75, -1], [25, 50, 75, "All"]],
      pageLength: 50,
      search: {
          caseInsensitive: true
      },
      searchHighlight: true,
      ajax: urldatatable,
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'jenis_perusahaan', name: 'jenis_perusahaan', searchable: true},
        { data: 'kepemilikan', name: 'kepemilikan', searchable: true},
        { data: 'kelas', name: 'kelas', searchable: true},
        { data: 'is_active', searchable: false},
        // { data: 'created_at', name: 'created_at', searchable: true},
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

function onbtndelete(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data BUMN "+$(element).data('nama')+" ?",
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

function onbtnaktif(element) {
    swal.fire({
        title: "Pemberitahuan",
        text:
            $(element).data("original-title") +
            " " +
            $(element).data("nama") +
            " ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: urlaktif,
                data: {
                    id: $(element).data("id"),
                    is_active: $(element).data("is_active"),
                },
                type: "post",
                dataType: "json",
                beforeSend: function () {
                    KTApp.block(".cls-content-data", {
                        overlayColor: "#000000",
                        type: "v2",
                        state: "primary",
                        message: "Sedang proses, silahkan tunggu ...",
                    });
                },
                success: function (data) {
                    KTApp.unblock(".cls-content-data");

                    swal.fire({
                        title: data.title,
                        html: data.msg,
                        type: data.flag,

                        buttonsStyling: false,

                        confirmButtonText:
                            "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default",
                    });

                    if (data.flag == "success") {
                        // datatable.ajax.reload(null, false);
                        if($(element).data("is_active")==0){
                            $(element).removeClass('badge-success');
                            $(element).addClass('badge-danger');
                            $(element).data('is_active',1);
                            $(element).data('original-title','Aktifkan');
                            $(element).text('Tidak Aktif');
                        } else{
                            $(element).removeClass('badge-danger');
                            $(element).addClass('badge-success');
                            $(element).data('is_active',0);
                            $(element).data('original-title','Nonaktifkan');
                            $(element).text('Aktif');
                        }
                    }
                },
                error: function (jqXHR, exception) {
                    KTApp.unblock(".cls-content-data");
                    var msgerror = "";
                    if (jqXHR.status === 0) {
                        msgerror = "jaringan tidak terkoneksi.";
                    } else if (jqXHR.status == 404) {
                        msgerror = "Halaman tidak ditemukan. [404]";
                    } else if (jqXHR.status == 500) {
                        msgerror = "Internal Server Error [500].";
                    } else if (exception === "parsererror") {
                        msgerror = "Requested JSON parse gagal.";
                    } else if (exception === "timeout") {
                        msgerror = "RTO.";
                    } else if (exception === "abort") {
                        msgerror = "Gagal request ajax.";
                    } else {
                        msgerror = "Error.\n" + jqXHR.responseText;
                    }
                    swal.fire({
                        title: "Error System",
                        html: msgerror + ", coba ulangi kembali !!!",
                        type: "error",

                        buttonsStyling: false,

                        confirmButtonText:
                            "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default",
                    });
                },
            });
        } else {
            if ($(element).data("is_active") == 0) {
                $(element).prop("checked", true);
            } else {
                $(element).prop("checked", false);
            }
        }
    });
}