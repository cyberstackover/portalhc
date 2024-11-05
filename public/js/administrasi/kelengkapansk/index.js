var datatable;
var portlet = new KTPortlet('kt_portlet_tools_6');

portlet.on('afterExpand', function(portlet) {
    $(".kt-select2").select2({
        placeholder: "Pilih...",
        allowClear: true
    });
    
});

portlet.on('afterExpand', function(portlet) {
    $(".kt-select2").select2({
        placeholder: "Pilih...",
        allowClear: true
    });
    
});

$(document).ready(function(){

	$('body').on('click','.cls-button-edit',function(){
		winform(urledit, {'id':$(this).data('id'), 'id_grup_jabatan':$(this).data('id_grup_jabatan'), 'id_perusahaan':$(this).data('id_perusahaan')}, 'Ubah Kelengkapan SK');
	});

  var pickerOptsGeneral = {
      orientation: "left",
      autoclose: true,
      format: 'dd/mm/yyyy'
  };

  $('.cls-datepicker').datepicker(pickerOptsGeneral);

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
      search: {
          caseInsensitive: true
      },
      searchHighlight: true,
      ajax: {
          url: urldatatable,
          type: 'POST',
            data: function (d) {
                d.id_bumn = $('select[name=id_perusahaan]').val();
                d.jabatan = $('select[name=jabatan]').val();
                d.nama = $('input[name=nama_lengkap]').val();
                d.nomor = $('input[name=nomor_sk]').val();
                d.tanggal_sk = $('input[name=tgl_sk]').val();
            }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'nama', name: 'nama', searchable: true},
        { data: 'perusahaan', name: 'perusahaan', searchable: true},
        { data: 'nomorsk', name: 'nomorsk', searchable: true},
        { data: 'prosentase', name: 'prosentase', searchable: true},
        { data: 'action', orderable: false, searchable: false}
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
      $("#id_perusahaan").val('').trigger("change");
      $("#jabatan").val('').trigger("change");
      $('#nama_lengkap').val('');
      $('#nomor_sk').val('');
      $('#tgl_sk').val('');
      datatable.ajax.reload( null, false );
  });	
}