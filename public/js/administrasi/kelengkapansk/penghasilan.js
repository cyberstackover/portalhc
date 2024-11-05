var datatablepenghasilan;
var datatablesumpenghasilan;

$(document).ready(function(){
  $('body').on('click','.cls-add-penghasilan',function(){

    winform(urlcreatepenghasilan, {'id_talenta':$('input[name=id_talenta]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val(), 'id_struktur_organ':$('input[name=id_struktur_organ]').val()}, 'Tambah penghasilan');
  });

  $('body').on('click','.cls-button-edit-penghasilan',function(){
    winform(urleditpenghasilan, {'id':$(this).data('id'),'id_talenta':$('input[name=id_talenta]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val(), 'id_struktur_organ':$('input[name=id_struktur_organ]').val()}, 'Ubah penghasilan');
  });

  $('body').on('click','.cls-button-delete-penghasilan',function(){
    onbtndeletepenghasilan(this);
  });
  
  setDatatablePenghasilan();
  setDatatableSumPenghasilan();
});

function setDatatablePenghasilan(){
  datatablepenghasilan = $('#datatable-penghasilan').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      search: {
          caseInsensitive: true
      },
      searchHighlight: true,
      ajax: {
          url: urldatatablepenghasilan,
          type: 'GET',
          data: function (d) {
              d.id_talenta = $('input[name=id_talenta]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
              d.id_struktur_organ = $('input[name=id_struktur_organ]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'tahun', name: 'tahun', searchable: true},
        { data: 'gaji_pokok', name: 'gaji_pokok', searchable: true, sClass: 'text-right'},
        { data: 'tantiem', name: 'tantiem', searchable: true, sClass: 'text-right'},
        { data: 'tunjangan', name: 'tunjangan', searchable: true, sClass: 'text-right'},
        { data: 'takehomepay', name: 'takehomepay', searchable: true, sClass: 'text-right'},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatablepenghasilan.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablepenghasilan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function setDatatableSumPenghasilan(){
  datatablesumpenghasilan = $('#datatable-sum-penghasilan').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      info: false,
      searching: false,
      lengthChange: false,
      ajax: {
          url: urldatatablesumpenghasilan,
          type: 'GET',
          data: function (d) {
              d.id_talenta = $('input[name=id_talenta]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
              d.id_struktur_organ = $('input[name=id_struktur_organ]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'tahun', name: 'tahun', searchable: true},
        { data: 'gaji_pokok', name: 'gaji_pokok', searchable: true},
        { data: 'tantiem', name: 'tantiem', searchable: true},
        { data: 'tunjangan', name: 'tunjangan', searchable: true},
        { data: 'takehomepay', name: 'takehomepay', searchable: true},
      ],
      drawCallback: function( settings ) {
        var info = datatablesumpenghasilan.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumpenghasilan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function onbtndeletepenghasilan(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data penghasilan ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeletepenghasilan,
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
                      datatablepenghasilan.ajax.reload( null, false );
                      datatablesumpenghasilan.ajax.reload( null, false );
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