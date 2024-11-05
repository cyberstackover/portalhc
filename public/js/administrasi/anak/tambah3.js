var datatableangkatanak;
var datatablesumangkatanak;

$(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        var $target = $(e.target);
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {
        var $active = $('.nav-tabs li>.active');
        $active.parent().next().find('.nav-link').removeClass('disabled');
        nextTab($active);
    });
    
    $(".prev-step").click(function (e) {
        var $active = $('.nav-tabs li>a.active');
        prevTab($active);
    });

    $('body').on('click','.cls-add-angkat-anak',function(){
        winform(urlcreateangkat, {'id_surat_keputusan':$('input[name=id_surat_keputusan]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Tambah Pengangkatan Anak/Cucu');
    });

    $('body').on('click','.cls-add-angkatlagi-anak',function(){
      winform(urlcreateangkatlagi, {'id_surat_keputusan':$('input[name=id_surat_keputusan]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Tambah Pengangkatan Kembali Anak/Cucu');
    });

    $('body').on('click','.cls-button-edit-angkat',function(){
        winform(urleditangkat, {'id':$(this).data('id'),'id_surat_keputusan':$('input[name=id_surat_keputusan]').val(), 'id_perusahaan':$('input[name=id_perusahaan]').val(), 'grup_jabatan_id':$('input[name=grup_jabatan_id]').val()}, 'Ubah Pengangkatan');
    });

    $('body').on('click','.cls-button-delete-angkat',function(){
        onbtndeleteangkat(this);
    });

    $('body').on('click','.save-tambah2',function(){
      onbtnsavetambah2(this);
    });
    
    setDatatableAngkatAnak();
    setDatatableSumAngkatAnak();
});

function setDatatableAngkatAnak(){
  datatableangkatanak = $('#datatable-angkat-anak').on( 'preXhr.dt', function ( e, settings, processing ) {
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
            url: urldatatableangkat,
            type: 'GET',
            data: function (d) {
                d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
                d.id_perusahaan = $('input[name=id_perusahaan]').val();
            }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nomenklatur_jabatan', name: 'nomenklatur_jabatan', searchable: true},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'nama', name: 'nama', searchable: true},
        { data: 'tanggal_awal_menjabat', name: 'tanggal_awal_menjabat', searchable: true},
        { data: 'tanggal_akhir_menjabat', name: 'tanggal_akhir_menjabat', searchable: true},
        { data: 'action', orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatableangkatanak.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatableangkatanak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });   
}

function setDatatableSumAngkatAnak(){
  datatablesumangkatanak = $('#datatable-sum-angkat-anak').on( 'preXhr.dt', function ( e, settings, processing ) {
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
          url: urldatatablesumangkat,
          type: 'GET',
          data: function (d) {
              d.id_surat_keputusan = $('input[name=id_surat_keputusan]').val();
              d.id_perusahaan = $('input[name=id_perusahaan]').val();
          }
    },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nomenklatur_jabatan', name: 'nomenklatur_jabatan', searchable: true},
        { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
        { data: 'nama', name: 'nama', searchable: true},
        { data: 'tanggal_awal_menjabat', name: 'tanggal_awal_menjabat', searchable: true},
        { data: 'tanggal_akhir_menjabat', name: 'tanggal_akhir_menjabat', searchable: true}
      ],
      drawCallback: function( settings ) {
        var info = datatablesumangkatanak.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatablesumangkatanak.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  }); 
}

function onbtndeleteangkat(element){
    swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data Pengangkatan Anak/Cucu ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urldeleteangkat,
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
                      datatableangkatanak.ajax.reload( null, false );
                      datatablesumangkatanak.ajax.reload( null, false );
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

function onbtnsavetambah2(element){
  swal.fire({
        title: "Pemberitahuan",
        text: "Yakin Simpan data Jenis SK Anak/Cucu?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Simpan data",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urlsavetambah2,
               data:{id:$('input[name=id_surat_keputusan]').val()},
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
                      window.location = "index";
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

function goBack() {
  window.history.back();
}

function nextTab(elem) {
    $(elem).parent().next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).parent().prev().find('a[data-toggle="tab"]').click();
}