var datatable;
var datatable2;
let list_checked = [];

$(document).ready(function(){
    
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
        event.preventDefault();
        return false;
        }
    });
    
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

	$('body').on('click','.cls-button-delete',function(){
		onbtndelete(this);
    });

	$('body').on('click','.cls-button-cancel',function(){
		onbtncancel(this);
    });

	$('body').on('click','.btn-submit',function(){
		submit_selected();
    });

    $('.kt-select2').select2({
            placeholder: "Pilih"
        });

    // $('#nama_lengkap').on('change', function() {
    //     datatable.ajax.reload( null, false );
    //     datatable2.ajax.reload( null, false );
    // });

    $("#reset").on('click',function(){
        $("#nama_lengkap").val('').trigger("change");
        $("#id_status_talenta").val('').trigger("change");
        datatable.ajax.reload( null, false );
        datatable2.ajax.reload( null, false );
    }); 

    $('#cari').on('click', function() {
        datatable.ajax.reload( null, false );
        datatable2.ajax.reload( null, false );
    });

    $('.btn-talent').on('click', function() {
        $('.petunjuk').html('<b>Petunjuk :</b><br>Untuk menambahkan talent, search talent kemudian klik button <font style="color:red;"> Register </font>. Jika talent tidak ditemukan, klik <font style="color:red;">Tambah Talenta</font> untuk input data talent baru.');
        $('.btn-submit').hide();
    });

    $('.btn-register').on('click', function() {
        $('.petunjuk').html('<b>Petunjuk :</b><br>Setelah semua talent ditambah maka proses selanjutnya klik button <font style="color:red;"> Submit Talent </font>');
        $('.btn-submit').show();
    });

	setDatatable();
	setDatatable2();
    $('.btn-submit').hide();
    
});

function submit_selected() {
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
            url: urlsubmittalenta,
            data:{checked_list:$(".checked_list").val()},
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
                    // update jumlah register
                    $('#jumlah').text(data.jumlah);

                    datatable.ajax.reload( null, false );
                    datatable2.ajax.reload( null, false );
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

function cancel_selected(element) {
    if (!element.checked) {
        swal.fire({
            title: "Pemberitahuan",
            text: "Yakin batalkan data talenta "+$(element).data('nama_lengkap')+" ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, batalkan",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlcancelselected,
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
                        // update jumlah register
                        $('#jumlah').text(data.jumlah);
                    
                        datatable.ajax.reload( null, false );
                        datatable2.ajax.reload( null, false );
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

function talenta_selected(element) {
    if (element.checked) {
        $.ajax({
            url: urlselected,
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
                if(data.flag == 'success') {
                    // update jumlah register
                    $('#jumlah').text(data.jumlah);
                    $(element).attr('disabled', true);

                    datatable.ajax.reload( null, false );
                    datatable2.ajax.reload( null, false );
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
}

function select2talenta(){
    $.ajax({
        url: "/fetch/referensi/gettalenta",
        type: "POST",
        dataType: "json", 
        success: function(data){
                var contentData = "";
                $(".talenta").empty();
                contentData += "<option></option>";
                for(var i = 0, len = data.length; i < len; ++i) {
                    contentData += "<option value='"+data[i].nama+"'>"+data[i].nama+"</option>";
                }
                $(".talenta").append(contentData);
                $(".talenta").trigger("change");     
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
              d.nama_lengkap = $('#nama_lengkap').val(),
              d.id_status_talenta = $('#id_status_talenta').val()
          }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', orderable: false, searchable: false},
        { data: 'stalenta', name: 'stalenta', orderable: false, searchable: false},
        { data: 'jabatan', name: 'jabatan', orderable: false, searchable: false},
        { data: 'status_pengisian', orderable: false, className: "text-right", searchable: false},
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
}

function setDatatable2(){
    datatable2 = $('#datatableselected').on( 'preXhr.dt', function ( e, settings, processing ) {
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
            url: urldatatableselected,
            type: 'GET',
            data: function (d) {
                d.nama_lengkap = $('#nama_lengkap').val(),
                d.id_status_talenta = $('#id_status_talenta').val()
            }
        },
        columns: [
          { data: null, orderable: false, searchable: false},
          { data: 'nama_lengkap', name: 'nama_lengkap', orderable: false, searchable: false},
          { data: 'stalenta', name: 'stalenta', orderable: false, searchable: false},
          { data: 'jabatan', name: 'jabatan', orderable: false, searchable: false},
          { data: 'status_pengisian', orderable: false, className: "text-right", searchable: false},
          { data: 'action', className: "text-center", orderable: false, searchable: false}
        ],
        drawCallback: function( settings ) {
            
          var info = datatable2.page.info();
            $('[data-toggle="tooltip"]').tooltip();
            datatable2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            } );
            
            //simpan check ke variable list
            $('#datatableselected .checked_item').each(function(index, item) {
                let val = $(item).val();
                if ($(this).is(":checked")) {
                    if (list_checked.indexOf($(this).val()) == -1) {
                        list_checked.push(val);
                    }
                } else {
                    list_checked.splice(list_checked.indexOf(val), 1);
                }
            })
            $(".checked_list").val(list_checked);

            //update check list
            $('#datatableselected').on('change', '.checked_item', function(e) {
                let item = $(this).val();
                if ($(this).is(":checked")) {
                    if (list_checked.indexOf($(this).val()) == -1) {
                        list_checked.push(item);
                    }
                } else {
                    list_checked.splice(list_checked.indexOf(item), 1);
                }

                $(".checked_list").val(list_checked);
            })
        }
    });
  }

function onbtndelete(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin hapus data talenta "+$(element).data('nama_lengkap')+" ?",
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
                        // update jumlah register
                        $('#jumlah').text(data.jumlah);
                        
                        datatable.ajax.reload( null, false );
                        datatable2.ajax.reload( null, false );
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


function onbtncancel(element){
	swal.fire({
        title: "Pemberitahuan",
        text: "Yakin batalkan data talenta "+$(element).data('nama_lengkap')+" ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, batalkan",
        cancelButtonText: "Tidak"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
               url: urlcancelselected,
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
                        // update jumlah register
                        $('#jumlah').text(data.jumlah);
                        
                        datatable.ajax.reload( null, false );
                        datatable2.ajax.reload( null, false );
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