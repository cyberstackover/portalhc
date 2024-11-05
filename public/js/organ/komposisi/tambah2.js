var datatable;

$(document).ready(function(){

  $('body').on('click','.cls-add',function(){
    winform(urlcreate, {'perusahaan_id':$('input[name=perusahaan_id]').val()}, 'Tambah Jabatan');
  });

  $('body').on('click','.cls-button-edit',function(){
    winform(urledit, {'id':$(this).data('id'), 'perusahaan_id':$('input[name=perusahaan_id]').val()}, 'Ubah Jabatan');
  });
	
  $('body').on('click','.cls-button-delete',function(){
    onbtndelete(this);
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
      bFilter: false,
      aLengthMenu: [[25, 50, 75, -1], [25, 50, 75, "All"]],
      pageLength: 25,
      ajax: {
          url: urldatatable,
          type: 'POST',
            data: function (d) {
                d.perusahaan_id = $('input[name=perusahaan_id]').val();
            }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'grup_nama', name: 'grup_nama', searchable: false},
        { data: 'nomenklatur_nama', name: 'nomenklatur_nama', searchable: true},
        { data: 'nomenklatur_baru', name: 'nomenklatur_baru', searchable: true},
        { data: 'jabatan_nama', name: 'jabatan_nama', searchable: false},
        { data: 'bidang_jabatan_nama', name: 'bidang_jabatan_nama', searchable: false},
        { data: 'aktif', name: 'aktif', searchable: false},
        { data: 'urut', name: 'urut', searchable: false,  className: "text-right"},
        { data: 'action', name: 'action', searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatable.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );

        var api = this.api();
        var rows = api.rows({page: 'current'}).nodes();
        var last = null;

        api.column(1, {page: 'current'}).data().each(function(group, i) {
            if (last !== group) {
              $(rows).eq(i).before(
                '<tr class="group"><td colspan="7"><b>' + group + '</b></td></tr>',
              );
              last = group;
            }
        });

      },
      columnDefs: [
        {
          // hide columns by index number
          targets: [0,1],
          visible: false,
        },
        
      ],
  });	
}

function onbtndelete(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Komposisi ?",
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

function submitAktif(id, bolehNambah)
{ 
    var required = (bolehNambah)?'t':'f';
    $.ajax({
         url: urlchangeaktif,
         data:{id:id, required:required},
         type:'post',
         dataType:'json',               
         success: function(data){
             swal.closeModal();
          
             swal.fire(data.title, data.msg, data.flag);
          
             if(data.flag == 'success') {
                datatable.ajax.reload( null, false );
             }
         },
          error: function(jqXHR, exception) {
                  swal.closeModal();
                  var msgerror = ''; 
                  if (jqXHR.status === 0) {
                      msgerror = 'jaringan tidak terkoneksi.';
                  } else if (jqXHR.status == 404) {
                      msgerror = 'Halamam tidak ditemukan. [404]';
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
                  swal.fire('Error', msgerror, 'error');
          }                           
      });
}