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
    
    $("#reset").on('click',function(){
        $("#nama_lengkap").val('').trigger("change");
        $("#jabatan").val('').trigger("change");
        datatable.ajax.reload( null, false );
    }); 

	$('.btn-submit').on('click',function(){
		submit();
    });

	setDatatable();
    checkbox_check();
    
});

function submit() {
    swal.fire({
        title: "Pemberitahuan",
        text: "Submit data talent ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, submit",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
            url: urlupdate_talenta,
            data:{
                checked_list:$(".checked_list").val(),
                reject_list:$(".reject_list").val()
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

function talenta_approve(element) {
    if ( element.checked && !$(".checked_all").is(":checked") ) {
        swal.fire({
            title: "Pemberitahuan",
            text: "Approve data talenta "+$(element).data('nama_lengkap')+" ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, approve",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: urlapprove,
                    data:{
                        id:$(element).data('id')
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
}

function talenta_reject(element) {
    if ( element.checked && !$(".reject_all").is(":checked") ) {
        swal.fire({
            title: "Pemberitahuan",
            text: "Reject data talenta "+$(element).data('nama_lengkap')+" ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, reject",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: urlreject,
                    data:{
                        id:$(element).data('id')
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
      ajax: urldatatable,
      ajax: {
          url: urldatatable,
          type: 'GET',
          data: function (d) {
              d.nama_lengkap = $('#nama_lengkap').val();
              d.lembaga_assessment = $('#lembaga_assessment').val();
          }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', orderable: false, searchable: false},
        { data: 'jabatan', name: 'jabatan', orderable: false, searchable: false},
        { data: 'lembaga_assessment', name: 'lembaga_assessment', orderable: false, className: "text-center", searchable: false},
        { data: 'hasil', orderable: false, className: "text-center", searchable: false},
        { data: 'action', className: "text-center", orderable: false, searchable: false}
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
      $("#lembaga_assessment").val('').trigger("change");
      datatable.ajax.reload( null, false );
  }); 
}

function checkbox_check(){  
    //approve all
    $('.checked_all').on('change', function(e) {
        if ($(this).is(':checked')) {
            $('.checked_item').prop("checked", true).change();
        } else {
            $('.checked_item').prop("checked", false).change();
        }
    })

    //reject all
    $('.reject_all').on('change', function(e) {
        if ($(this).is(':checked')) {
            $('.reject_item').prop("checked", true).change();
        } else {
            $('.reject_item').prop("checked", false).change();
        }
    })

    //update check list
    $('#datatable').on('change', '.checked_item', function(e) {
        let item = $(this).val();
        if ($(this).is(":checked")) {
            if (list_checked.indexOf($(this).val()) == -1) {
                list_checked.push(item);
            }

            $(this).closest("tr").find(".reject_item").each(function(){
                $(this).attr('disabled', true);
            });
        } else {
            list_checked.splice(list_checked.indexOf(item), 1);

            $(this).closest("tr").find(".reject_item").each(function(){
                $(this).attr('disabled', false);
            });
        }
        $(".checked_list").val(list_checked);
    })
    
    //update reject list
    $('#datatable').on('change', '.reject_item', function(e) {
        let item = $(this).val();
        if ($(this).is(":checked")) {
            if (list_reject.indexOf($(this).val()) == -1) {
                list_reject.push(item);
            }
            
            $(this).closest("tr").find(".checked_item").each(function(){
                $(this).attr('disabled', true);
            });
        } else {
            list_reject.splice(list_reject.indexOf(item), 1);
            
            $(this).closest("tr").find(".checked_item").each(function(){
                $(this).attr('disabled', false);
            });
        }
        $(".reject_list").val(list_reject);
    })
}
