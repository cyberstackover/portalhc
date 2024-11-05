var tabledetail;
var tablerekap;

$(document).ready(function(){
    
    $('.kt-select2').select2({
        tags: true,
        placeholder: "Pilih"
    });
    
    $('body').on('click','.cls-detail_rekap',function(){
        winform(urldetail_rekap, {'id_status_talenta':$(this).data('id_status_talenta'),'bumn':$(this).data('bumn')}, 'Detail Talenta');
    })

    var tablerekap = $('#tablerekap').on( 'preXhr.dt', function ( e, settings, processing ) {
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
        destroy: true,
        bFilter: true,
        aLengthMenu: [[25, 50, 75, -1], [25, 50, 75, "All"]],
        pageLength: 25,
        ajax: {
            url : 'datatablerekap',
            data: []
        },
        columns: [
            { data: null, orderable: false, searchable: false},
            { data: 'bumn', name: 'bumn', searchable: true},
            { data: 'jumlah_selected', searchable: true, className: "text-right"},
            { data: 'jumlah_nominated', searchable: true, className: "text-right"},
            { data: 'jumlah_eligible', searchable: true, className: "text-right"},
            { data: 'jumlah_qualified', searchable: true, className: "text-right"},
            { data: 'jumlah', searchable: true, className: "text-right"},
        ],
        footerCallback: function (row, data, start, end, display) {
              var api = this.api();
  
              var intVal = function ( i ) {
                  return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
              };

              var intVal2 = function ( i ) {
                  var el = $( '<div></div>' );
                  el.html(i);
                  return $(el).text();
              };
  
              $(api.column(2).footer()).html(api.column(2).data().reduce(function (a, b) {
                      return intVal(intVal2(a)) + intVal(intVal2(b));
                  })
              );
              $(api.column(3).footer()).html(api.column(3).data().reduce(function (a, b) {
                      return intVal(intVal2(a)) + intVal(intVal2(b));
                  })
              );
              $(api.column(4).footer()).html(api.column(4).data().reduce(function (a, b) {
                      return intVal(intVal2(a)) + intVal(intVal2(b));
                  })
              );
              $(api.column(5).footer()).html(api.column(5).data().reduce(function (a, b) {
                      return intVal(intVal2(a)) + intVal(intVal2(b));
                  })
              );
              $(api.column(6).footer()).html(api.column(6).data().reduce(function (a, b) {
                      return intVal(intVal2(a)) + intVal(intVal2(b));
                  })
              );
          },
        drawCallback: function( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;   

            var info = tablerekap.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                tablerekap.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                } );
            }
                
        });
    initchartjumlahtalenta();
});

function initchartjumlahtalenta(){
    options = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Chart Status Talent'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah'
            }
        },
        exporting: {
          enabled: true
        },
        legend: {
            enabled: false
        }
    };
    
    $.ajax({
        type: 'GET',
        url: urlchartjumlahtalenta,
        beforeSend: function(){
           KTApp.block('#divChart', {
               overlayColor: '#000000',
               type: 'v2',
               state: 'primary',
               message: 'Sedang proses, silahkan tunggu ...'
           });
         },
        success: function(json) {
            KTApp.unblock('#divChart');
            series = [{
                name: 'Jumlah',
                colorByPoint: true,
                data: json,
                dataLabels: {
                    enabled: true,
                    align: 'center',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                },
                point: {
                    events: {
                        click: function () {
                            idx     = this.index;
                            ondetaildata(idx);
                        }
                    }
                }
            }];
            options.series = series;
            chart = new Highcharts.Chart('divChart', options);
        },
        error: function (jqXHR, exception) {
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
              alert('coba ulangi kembali !!!');
        }
    });
}

function ondetaildata(idx){
    $.ajax({
        headers:{
             'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        url: urlgettabledetail,
        data: {
            'idx' : idx
        },
        beforeSend: function(){

        },
        success: function(response){
            $('#divviewtable').html(response);

            $('#dash-modal').modal('show');
        },
        error: function (jqXHR, exception) {
            KTApp.unblock('.cls-content-data');
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
            swal("Error System", msgerror+', coba ulangi kembali !!!', 'error');
        },
        dataType:"html"
    });
    return false;
}
