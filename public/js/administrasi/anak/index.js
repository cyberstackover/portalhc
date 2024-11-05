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
		winform(urlcreate, {}, 'Tambah Administrasi SK Anak/Cucu');
	});

	$('body').on('click','.cls-button-delete',function(){
		onbtndelete(this);
	});

  $('body').on('click', '.cls-urlpendukung', function(){
     popupwindow($(this).data('url'), $(this).data('keterangan'), 500, 500);
  });

  $('body').on('click','.cls-button-detail',function(){
    winform(urldetailsk, {'id':$(this).data('id_keputusan'), 'id_perusahaan':$(this).data('id_perusahaan')}, 'Detail Surat Keputusan '+$(this).data('nomor_sk'));

  });

  $(".kt-select2").select2({
      placeholder: "Pilih...",
      allowClear: true
  });

  var pickerOptsGeneral = {
      orientation: "left",
      autoclose: true,
      format: 'dd/mm/yyyy'
  };

  $('.cls-datepicker').datepicker(pickerOptsGeneral);
	
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
      responsive: true,
      serverSide: true,
      processing: true,
      bFilter: false,
      bInfo : false,
      aLengthMenu: [[25, 50, 75, -1], [25, 50, 75, "All"]],
      pageLength: 25,
      ajax: {
          url: urldatatable,
          type: 'POST',
            data: function (d) {
                d.id_bumn = $('select[name=id_perusahaan]').val();
                d.status = $('select[name=id_status]').val();
                d.id_grup_jabat = $('select[name=id_grup_jabat]').val();
                d.id_jenis_sk = $('select[name=id_jenis_sk]').val();
                d.tanggal_sk = $('input[name=tgl_sk]').val();
                d.nomor = $('input[name=nomor_sk]').val();
          }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nomor', name: 'nomor', searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'jenis_sk_nama', name: 'jenis_sk_nama', searchable: false},
        { data: 'status', name: 'status', searchable: false},
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
      $("#form_induk").val('').trigger("change");
      $("#id_perusahaan").val('').trigger("change");
      $("#id_grup_jabat").val('').trigger("change");
      $("#id_grup_jabat").val('').trigger("change");
      $("#id_jenis_sk").val('').trigger("change");
      $("#id_status").val('').trigger("change");
      $('#nomor_sk').val('');
      $('#tgl_sk').val('');
      datatable.ajax.reload( null, false );
  });	
}

function onbtndelete(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Administrasi SK Anak/Cucu ?",
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

function onNamaPerusahaan(id_induk){
    $.ajax({
        url: "/administrasi/anak/getanak?id_induk="+id_induk,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $("#id_perusahaan").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $("#id_perusahaan").append(contentData);
                 $("#id_perusahaan").trigger("change");
                                     
        }
    });
}