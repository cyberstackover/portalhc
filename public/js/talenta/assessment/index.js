var datatable;
let list_checked = [];
let list_reject = [];

$(document).ready(function(){
    $('body').on('click','.cls-minicv',function(){
        winform(urlminicv, {'id':$(this).data('id')}, 'Curriculum Vitae');
    })

    $('body').on('click','.cls-logstatus',function(){
        winform(urllogstatus, {'id':$(this).data('id')}, 'Log Status');
    })

    $('.kt-select2').select2({
            placeholder: "Pilih"
        });

    $('#cari').on('click', function() {
        datatable.ajax.reload( null, false );
    });
    
    $('#instansi').on('change', function() {
        datatable.ajax.reload( null, false );
    });
    
    $("#reset").on('click',function(){
        $("#nama_lengkap").val('').trigger("change");
        $("#jabatan").val('').trigger("change");
        datatable.ajax.reload( null, false );
    }); 

	setDatatable();
    
});

function approve(element) {
    swal.fire({
        title: "Pemberitahuan",
        text: "Pilih "+$("option:selected",element).text()+" ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, pilih",
        cancelButtonText: "Batal"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
            url: urlapprove,
            data:{
                id_lembaga_assessment:$(element).val(),
                id:$(element).data('id_talenta')
            },
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
                    $('#jumlah_nominated').text(data.data.jumlah_nominated);
                    $('#jumlah_eligible1').text(data.data.jumlah_eligible1);
                    $('#jumlah_eligible2').text(data.data.jumlah_eligible2);
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
      ajax: {
          url: urldatatable,
          type: 'GET',
          data: function (d) {
              d.instansi = $('#instansi').val()
          }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', orderable: false, searchable: false},
        { data: 'stalenta', name: 'stalenta', orderable: false, searchable: false},
        { data: 'jabatan', name: 'jabatan', orderable: false, searchable: false},
        { data: 'action', className: "text-center", orderable: false, searchable: false}
      ],
      drawCallback: function( settings ) {
        var info = datatable.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
            
        $('.kt-select2').select2({
            placeholder: "Pilih"
        });
        
        $('.id_lembaga_assessment').on('change',function(){
            approve($(this));
        });
      }
  });
}
