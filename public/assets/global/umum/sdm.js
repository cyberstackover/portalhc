$(document).ready(function(){

    $(".bumnselect").select2({
        placeholder: "Pilih...",
        allowClear: true
    }).prop('disabled', true);

    $(".periodeselect").select2({
        placeholder: "Pilih...",
        allowClear: true
    }).prop('disabled', true);

    $(".select2").select2({
        placeholder: "Pilih...",
        allowClear: true
    }).prop('disabled', true);

    $('#btnaddsdm').click(function(){
         onwindformsdm('insert');
    });

    var oTable = $('#tableSdm').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/operasional/sdm/getdatatable',
            "type": "POST",
            data: function (d) {
                d.tahun = $('input[name=tahun]').val();
                d.bumn = $('select[name=bumn]').val();
                d.periode = $('select[name=periode]').val();
            }
        },
        "columns": [
            {data: null, sortable: false, searchable: false, width: "20px", className: "text-center"},
            {
                "data":           'expander',
                "defaultContent": '',
                "searchable": false
            },
            {data: 'bumn', name: 'bumn'},
            {data: 'tahun', name: 'tahun'},
            {data: 'periode_nama', name: 'periode_nama'},
            {data: 'total_karyawan', name: 'total_karyawan'},
            {data: 'actions', name: 'actions', searchable: false, sortable: false, width: "20%"}
        ],
        "order": [
            [1, 'asc']
        ],
        "lengthMenu": [
            [10, 20, -1],
            [10, 20, "All"]
        ],
        "pageLength": 20,
        "dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        "rowCallback": function( row, data, iDisplayIndex ) {
            var info = oTable.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = (page * length + (iDisplayIndex +1));
            $('td:eq(0)', row).html(index);
    
            $('.deleteData', row).click(function(){
                var that = this;
                bootbox.confirm("Anda yakin akan menghapus data ini?", function(result) {
                   if (result){
                        $(that).closest('form').submit();
                   }
                });
            });

            $(row).on('click', '.row-details', function () {
                var nTr = row;
                if (oTable.fnIsOpen(nTr)) {
                    $(this).addClass("row-details-close").removeClass("row-details-open");
                    oTable.fnClose(nTr);
                } else {
                    $(this).addClass("row-details-open").removeClass("row-details-close");
                    oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr, data), 'details');
                }
            });
        }
    });
    var tableWrapper = $('#tableData_wrapper');
    tableWrapper.find('.dataTables_length select').select2();

    $("#cari").click(function(){
        oTable.fnDraw();
    });

    function fnFormatDetails(oTable, nTr, data) {
        var sOut = '<div class="well abstrak no-margin">';
        sOut += '<h3>'+data.bumn+'</h3>';
        sOut += '<p> Rincian Karyawan : ';
        sOut += '<table class="tg">';
        sOut += '<tr><th class="tg-031e"></th><th class="tg-e3zv">Orang</th></tr>';
        sOut += '<tr><td class="tg-031e">Karyawan Tetap<br></td><td class="tg-031e">'+data.karyawan_tetap+'</td></tr>';
        sOut += '<tr><td class="tg-031e">Karyawan Tidak Tetap<br></td><td class="tg-031e">'+data.karyawan_nontetap+'</td></tr>';
        sOut += '<tr><td class="tg-0ord">Total Karyawan<br></td><td class="tg-031e">'+data.total_karyawan+'</td></tr>'
        sOut += '</table>';
        sOut += '</div>';
        return sOut;

    }

});

function onwindformsdm(mode){
     var texttitle = ''; 
     switch(mode){
          case 'insert' : texttitle = 'Tambah Nilai Buku / Nilai Wajar';break;
          case 'update' : texttitle = 'Ubah Nilai Buku / Nilai Wajar';break;
     }
     $('#formsdm .modal-title').text(texttitle);
     $('#formsdm').on('shown.bs.modal', function(e) {
        $("#actionform").val(mode);
     });         
     $('#formsdm').modal('show');
     var validator = $( "#frm-sdm" ).validate();
     validator.resetForm();     
}