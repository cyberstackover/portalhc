var tabledetail;
var id_filter = 0;

$(document).ready(function(){
    
    $('.kt-select2').select2({
        tags: true,
        placeholder: "Pilih"
    });
  
    $('#select_chart').on("select2:select", function (evt) {
        var value = $(this).val();
        selectchart(value);
    });

    $("#filter-bumn").on("click", function(){
        id_filter = 0;
        initchartmasajabatan(id_filter);
    });
    $("#filter-anak").on("click", function(){
        id_filter = 1;
        initchartmasajabatan(id_filter);
    });
    $("#filter-cucu").on("click", function(){
        id_filter = 2;
        initchartmasajabatan(id_filter);
    });
    
    $('#select_demografi').on("select2:select", function (evt) {
        var value = $(this).val();
        selectdemografi(value);
    });

    $('#select_jumlah').on("select2:select", function (evt) {
        var value = $(this).val();
        selectjumlah(value);
    });
    
    $('#perusahaan').on("select2:select", function (evt) {
        var value = $(this).val();
        perusahaantable(value);
    });

    $('#talenta').on("select2:select", function (evt) {
        var value = $(this).val();
        talentatable(value);
    });

    selectchart(1);
});

function selectchart(value){

    $("#divChart").css("padding-top", '0px');
    $("#divChart").html("");
    $("#divChart1").html("");
    $("#divChart2").html("");
    $("#filter_masa").hide();
    $("#filter_demografi").hide();
    $("#filter_jumlah").hide();

    if(value == '1'){
        $("#filter_masa").show();
        initchartmasajabatan(0);
    }
    else if(value == '2'){
        initchartkontribusi();
    }
    else if(value == '3'){
        initchartinstansi();
    }
    else if(value == '4'){
        $("#filter_demografi").show();
        selectdemografi($('#select_demografi').val());
    }
    else if(value == '5'){
        $("#filter_jumlah").show();
        selectjumlah($('#select_jumlah').val());
    }
}

function selectjumlah(value){
    $("#divChart").css("padding-top", '0px');
    $("#divChart").html("");
    $("#divChart1").html("");
    $("#divChart2").html("");

    if(value == '1'){
        initchartjumlah(1);
    }
    else if(value == '2'){
        initchartjumlah(2);
    }
}

function selectdemografi(value){
    $("#divChart").css("padding-top", '0px');
    $("#divChart").html("");
    $("#divChart1").html("");
    $("#divChart2").html("");
    
    if(value == '1'){
        initchartdemografijk();
    }
    else if(value == '2'){
        initchartdemografiusia();
    }
    else if(value == '3'){
        initchartdemografipendidikan();
    }
}

function perusahaantable(id){
    $.ajax({
        headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        url: urlgettableperusahaan,
        data: {
            'id' : id
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

function talentatable(id){
    $.ajax({
        headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        url: urlgettabletalenta,
        data: {
            'id' : id
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

function initchartmasajabatan(id_filter){
     options = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Massa Jabatan'
        },
        exporting: {
          enabled: false
        },
        xAxis: {
            categories: [
                '< 3 Bulan','< 6 Bulan', 'Expired','Rangkap', 'Kosong'
                //'< 3 Bulan','< 6 Bulan','Rangkap'
            ],
            crosshair: true
        },
        yAxis: {
            // min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<div style="color:#434349"><span style="font-size:13px"><b>{point.key}</b></span><table>',
            pointFormat: '<tr><td style="padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table></div>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            },
            series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    id = this.series.userOptions.id;
                                    name    = this.series.name;
                                    idx     = this.index + 1;
                                    ondetaildata(id, idx, id_filter);
                                }
                            }
                        }
                    }
        },
        series: []
    };

    $.ajax({
        type: 'GET',
        url: urlchartmasajabatans,
        data: {
            'id_filter': id_filter
        },
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
            options.series = json[0];
            chart = new Highcharts.Chart('divChart', options);
            chart.setTitle({text: json[1]['name']});
            chart.setTitle(null, {text: json[2]['name']});
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

function ondetaildata(mode, waktu_pekerjaan,id_filter){
        $.ajax({
            headers:{
                 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: urlgettabledetail+'/'+mode+'/'+waktu_pekerjaan+'/'+id_filter,
            data: {
                  'id_filter': id_filter
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

function initchartkontribusi(){
    options = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Kontribusi Dekom dari Kementerian Lembaga'
        },
        subtitle: {
            text: ''
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
          enabled: false
        },
        legend: {
            enabled: false
        }
    };
    
    $.ajax({
        type: 'GET',
        url: urlchartkontribusi,
        data: {
        },
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
                            ondetaildatakontribusi(idx);
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

function ondetaildatakontribusi(idx){
    $.ajax({
        headers:{
             'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        url: urlgettablekontribusi,
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

function initchartinstansi(){
    options1 = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Direksi'
        },
        tooltip: {
            pointFormat: 'Jumlah {point.y}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.2f} %'
                },
                showInLegend: true
            }
        },
        exporting: {
          enabled: false
        },
    };

    options2 = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Dewan Komisaris/Dewan Pengawas'
        },
        tooltip: {
            pointFormat: 'Jumlah {point.y}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.2f} %'
                },
                showInLegend: true
            }
        },
        exporting: {
          enabled: false
        },
    };

    options3 = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Dewan Komisaris/Dewan Pengawas dari Ormas dan Lainnya'
        },
        tooltip: {
            pointFormat: 'Jumlah {point.y}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.2f} %'
                },
                showInLegend: true
            }
        },
        exporting: {
          enabled: false
        },
    };
    
    $.ajax({
        type: 'GET',
        url: urlchartinstansi,
        data: {
        },
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
                name: 'Direksi',
                colorByPoint: true,
                data: json[0]
            }];
            options1.series = series;
            chart = new Highcharts.Chart('divChart1', options1);
            
            series = [{
                name: 'Dewan Komisaris/Dewan Pengawas',
                colorByPoint: true,
                data: json[1]
            }];
            options2.series = series;
            chart = new Highcharts.Chart('divChart2', options2);
            
            series = [{
                name: 'Dewan Komisaris/Dewan Pengawas dari Ormas dan Lainnya',
                colorByPoint: true,
                data: json[2]
            }];
            options3.series = series;
            chart = new Highcharts.Chart('divChart', options3);
            $("#divChart").css("padding-top", '70px');
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

function initchartdemografi(){

    options1 = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Direksi'
        },
        subtitle: {
            text: ''
        },
        tooltip: {
            pointFormat: 'Jumlah {point.y}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.2f} %'
                },
                showInLegend: true
            }
        },
        exporting: {
          enabled: false
        },
    };

    options2 = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Dewan Komisaris/Dewan Pengawas'
        },
        subtitle: {
            text: ''
        },
        tooltip: {
            pointFormat: 'Jumlah {point.y}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.2f} %'
                },
                showInLegend: true
            }
        },
        exporting: {
          enabled: false
        },
    };

    options3 = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Komposisi Usia BOD/BOC'
        },
        exporting: {
          enabled: false
        },
        xAxis: {
            categories: [
                '30-39 thn','40-49 thn', '50-59 thn','60-69 thn', '70-79 thn'
            ],
            crosshair: true
        },
        yAxis: {
            // min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<div style="color:#434349"><span style="font-size:13px"><b>{point.key}</b></span><table>',
            pointFormat: '<tr><td style="padding:0">Jumlah</td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table></div>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            },
        },
        series: []
    };
    
    options4 = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Jenjang Pendidikan'
        },
        exporting: {
          enabled: false
        },
        xAxis: {
            categories: [
                'SMA/SMK Sederajat','D1','D3','D4','S1','S2','S3'
            ],
            crosshair: true
        },
        yAxis: {
            // min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<div style="color:#434349"><span style="font-size:13px"><b>{point.key}</b></span><table>',
            pointFormat: '<tr><td style="padding:0">Jumlah</td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table></div>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            },
        },
        series: []
    };
    
    $.ajax({
        type: 'GET',
        url: urlchartdemografi,
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
                colorByPoint: true,
                data: json[0]
            }];
            options1.series = series;
            chart = new Highcharts.Chart('divChart1', options1);
            
            series = [{
                colorByPoint: true,
                data: json[1]
            }];
            options2.series = series;
            chart = new Highcharts.Chart('divChart2', options2);
            
            options3.series = json[2];
            chart = new Highcharts.Chart('divChart', options3);
            
            options4.series = json[3];
            chart = new Highcharts.Chart('divChart', options4);
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

function initchartdemografijk(){

    options1 = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Direksi'
        },
        subtitle: {
            text: ''
        },
        tooltip: {
            pointFormat: 'Jumlah {point.y}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.2f} %'
                },
                showInLegend: true
            }
        },
        exporting: {
          enabled: false
        },
    };

    options2 = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Dewan Komisaris/Dewan Pengawas'
        },
        subtitle: {
            text: ''
        },
        tooltip: {
            pointFormat: 'Jumlah {point.y}: <b>{point.percentage:.2f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.2f} %'
                },
                showInLegend: true
            }
        },
        exporting: {
          enabled: false
        },
    };

    $.ajax({
        type: 'GET',
        url: urlchartdemografijk,
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
                colorByPoint: true,
                data: json[0]
            }];
            options1.series = series;
            chart = new Highcharts.Chart('divChart1', options1);
            
            series = [{
                colorByPoint: true,
                data: json[1]
            }];
            options2.series = series;
            chart = new Highcharts.Chart('divChart2', options2);
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

function initchartdemografiusia(){

    options3 = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Komposisi Usia BOD/BOC'
        },
        exporting: {
          enabled: false
        },
        xAxis: {
            categories: [
                '30-39 thn','40-49 thn', '50-59 thn','60-69 thn', '70-79 thn'
            ],
            crosshair: true
        },
        yAxis: {
            // min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<div style="color:#434349"><span style="font-size:13px"><b>{point.key}</b></span><table>',
            pointFormat: '<tr><td style="padding:0">{series.name}</td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table></div>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            },
        },
        series: []
    };
    
    $.ajax({
        type: 'GET',
        url: urlchartdemografiusia,
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
            options3.series = json[0];
            chart = new Highcharts.Chart('divChart', options3);
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

function initchartdemografipendidikan(){
    
    options4 = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Jenjang Pendidikan'
        },
        exporting: {
          enabled: false
        },
        xAxis: {
            categories: [
                'SMA/SMK Sederajat','D1','D3','D4','S1','S2','S3'
            ],
            crosshair: true
        },
        yAxis: {
            // min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<div style="color:#434349"><span style="font-size:13px"><b>{point.key}</b></span><table>',
            pointFormat: '<tr><td style="padding:0">{series.name}</td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table></div>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            },
        },
        series: []
    };
    
    $.ajax({
        type: 'GET',
        url: urlchartdemografipendidikan,
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
            options4.series = json[0];
            chart = new Highcharts.Chart('divChart', options4);
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

function initchartjumlah(id_filter){
    
    options = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Jumlah Direksi & Dekom/Dewas per-tahun'
        },
        exporting: {
          enabled: false
        },
        xAxis: {
            categories: [
                '2016','2017','2018','2019','2020','2021'
            ],
            crosshair: true
        },
        yAxis: {
            // min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<div style="color:#434349"><span style="font-size:13px"><b>{point.key}</b></span><table>',
            pointFormat: '<tr><td style="padding:0">{series.name}</td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table></div>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            },
        },
        series: []
    };
    
    $.ajax({
        type: 'GET',
        url: urlchartjumlah,
        data: {
            'id_filter' : id_filter
        },
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
            options.series = json[0];
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