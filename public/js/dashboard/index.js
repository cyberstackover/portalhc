Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Masa Jabatan Direksi/Dekomwas'
    },
    xAxis: {
        categories: [
            '< 3 Bulan',
            '< 6 Bulan',
            'Expired',
            'Rangkap',
            'Kosong'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Jumlah'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Direksi',
        data: [5, 15, 4, 7, 10]

    }, {
        name: 'Dekomwas',
        data: [6, 7, 3, 2, 8]

    }]
});