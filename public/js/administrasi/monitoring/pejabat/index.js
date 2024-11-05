var datatable;
var portlet = new KTPortlet('kt_portlet_tools_6');

portlet.on('afterExpand', function(portlet) {
    $(".kt-select2").select2({
        placeholder: "Pilih...",
        allowClear: true
    });

});

$(document).ready(function(){

  var pickerOptsGeneral = {
      orientation: "left",
      autoclose: true,
      format: 'dd/mm/yyyy'
  };

  $('.cls-datepicker').datepicker(pickerOptsGeneral);

  $('body').on('click','.cls-minicv',function(){
    winform(urlminicv, {'id':$(this).data('id')}, 'Curriculum Vitae');
  })

  $('body').on('click', '.cls-urlpendukung', function(){
     popupwindow($(this).data('url'), $(this).data('keterangan'), 500, 500);
  });

  $('body').on('click','.cls-button-detail',function(){
    winform(urldetailsk, {'id':$(this).data('id_keputusan'), 'id_perusahaan':$(this).data('id_perusahaan')}, 'Detail Surat Keputusan '+$(this).data('nomor_sk'));

  });

  $('body').on('click','.cls-detailjunto',function(){
      winform(urldetailjo, {'id':$(this).data('id_keputusan'), 'id_perusahaan':$(this).data('id_perusahaan'), 'id_talenta':$(this).data('id_talenta')}, 'Detail Junto '+$(this).data('pejabat'));

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
      responsive: true,
      serverSide: true,
      processing: true,
      bFilter: false,
      aLengthMenu: [[25, 50, 75, -1], [25, 50, 75, "All"]],
      pageLength: 25,
      ajax: {
          url: urldatatable,
          type: 'POST',
            data: function (d) {
                d.id_bumn = $('select[name=id_bumn]').val();
                d.id_jabatan = $('select[name=id_jabatan]').val();
                d.id_grup_jabat = $('select[name=id_grup_jabat]').val();
                d.id_periode = $('select[name=id_periode]').val();
                d.pejabat = $('input[name=pejabat]').val();
                d.nomor_sk = $('input[name=nomor_sk]').val();
                d.tgl_sk = $('input[name=tgl_sk]').val();
                d.awal_tgl = $('input[name=awal_tgl]').val();
                d.akhir_tgl = $('input[name=akhir_tgl]').val();
                d.id_asal_instansi = $('select[name=id_asal_instansi]').val();
                d.pejabataktif = $('select[name=pejabataktif]').val();
                d.jenis_kelamin = $('select[name=jenis_kelamin]').val();
                d.id_agama = $('select[name=id_agama]').val();
                d.masa_jabatan = $('select[name=masa_jabatan]').val();
                d.kewarganegaraan = $('select[name=kewarganegaraan]').val();
                d.id_suku = $('select[name=id_suku]').val();
                d.kelas = $('select[name=kelas]').val();
                d.id_klaster = $('select[name=id_klaster]').val();
            }
      },
      columns: [
        { data: null, orderable: false, searchable: false},
        { data: 'bumns', name: 'bumns', searchable: false},
        {
          data: 'grup_jabat_nama', name: 'grup_jabat_nama', searchable: false,
          render: function (data) {
          html = '';
          if(data.grup_jabat_nama == 'Direksi'){
            html += '<span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill kt-badge--rounded">'+data.grup_jabat_nama+'</span>';
          } else {
            html += '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">'+data.grup_jabat_nama+'</span>';
          }

          return html;
          }
        },
        {
          data: 'fieldpejabat', name: 'fieldpejabat', searchable: true,
          render: function (data) {
            html = '';
            if(data.periode == 2){
              html_periode = 'kt-badge--danger';
            } else {
              html_periode = 'kt-badge--success';
            }

            if(data.expire == 't'){
              html_expire = '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">E</span>';
            } else if (data.kurang3 == 't') {
              html_expire = '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded"><3</span>';
            } else if (data.kurang6 == 't') {
              html_expire = '<span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill kt-badge--rounded"><6</span>';
            } else {
              html_expire = '&nbsp;';
            }

            if(data.plt == 't'){
              html += '<a href="javascript:;" class="cls-minicv" data-id="'+data.talenta_id+'" data-toggle="tooltip" data-original-title="CV"><b>'+data.pejabat+'</b>&nbsp;&nbsp;<span class="kt-badge '+html_periode+' kt-badge--inline kt-badge--pill kt-badge--rounded">'+data.periode+'</span>&nbsp;&nbsp;'+html_expire+'&nbsp;&nbsp;<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">PLT</span><br/>'+data.nama+'</a>';
            } else if (data.komisaris_independen == 't') {
              html += '<a href="javascript:;" class="cls-minicv" data-id="'+data.talenta_id+'" data-toggle="tooltip" data-original-title="CV"><b>'+data.pejabat+'</b>&nbsp;&nbsp;<span class="kt-badge '+html_periode+' kt-badge--inline kt-badge--pill kt-badge--rounded">'+data.periode+'</span>&nbsp;&nbsp;'+html_expire+'<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Komisaris Independen</span><br/>'+data.nama+'</a>';
            } else {
              html += '<a href="javascript:;" class="cls-minicv" data-id="'+data.talenta_id+'" data-toggle="tooltip" data-original-title="CV"><b>'+data.pejabat+'</b>&nbsp;&nbsp;<span class="kt-badge '+html_periode+' kt-badge--inline kt-badge--pill kt-badge--rounded">'+data.periode+'</span>&nbsp;&nbsp;'+html_expire+'<br/>'+data.nama+'</a>';
            }

            return html;
          }
        },
        {
          data: 'fieldjuntopejabat', name: 'fieldjuntopejabat', searchable: false,
          render: function (data) {
            html = '';
            if(data.juntopejabat == 'Junto'){

                html += '<a href="javascript:;" class="cls-detailjunto" data-toggle="tooltip" data-id_keputusan="'+data.sk_id+'" data-id_perusahaan="'+data.id_perusahaan+'" data-nomor_sk="'+data.nomor+'" data-id_talenta="'+data.talenta_id+'" data-pejabat="'+data.pejabat+'"><b>JO</b></a>';
            } else {
                html += '&nbsp';
            }
            return html;
          }
        },
        {
          data: 'fieldnomor', name: 'fieldnomor', searchable: false,
          render: function (data) {
            html = '';
              if(data.isExists){
                  html += '<a href="javascript:;" data-toggle="tooltip"><b data-id_keputusan="'+data.sk_id+'" data-id_perusahaan="'+data.id_perusahaan+'" data-nomor_sk="'+data.nomor+'" class="cls-button-detail">'+data.nomor+'</b>&nbsp;<a style="cursor:pointer" class="cls-urlpendukung" data-url="'+data.link_url+'" data-keterangan="'+data.nomor+'" ><i class="flaticon2-file" ></i>&nbsp;</a><br/>'+data.tanggal_sk+'</a>';
              } else {
                  html += '<a href="javascript:;" data-toggle="tooltip"><b data-id_keputusan="'+data.sk_id+'" data-id_perusahaan="'+data.id_perusahaan+'" data-nomor_sk="'+data.nomor+'" class="cls-button-detail">'+data.nomor+'</b>&nbsp;<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Belum Ada PDF</span><br/>'+data.tanggal_sk+'</a>';
              }

            return html;
          }
        },
        { data: 'tanggal_awal', name: 'tanggal_awal', searchable: false},
        { data: 'tanggal_akhir', name: 'tanggal_akhir', searchable: false},
        {
          data: 'fieldinstansi', name: 'fieldinstansi', searchable: false,
          render: function (data) {
            html = '';
            html += '<b>'+data.asal_instansi+'</b><br/>'+data.instansi;

            return html;
          }
        }
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
                '<tr class="group"><td colspan="7" style="BACKGROUND-COLOR:rgb(180, 218, 252);font-weight:700;"><b>' + group + '</b></td></tr>',
              );
              last = group;
            }
        });

        api.column(2, {page: 'current'}).data().each(function(group, i) {
            if (last !== group) {
              $(rows).eq(i).before(
                '<tr class="group"><td colspan="6">&nbsp;&nbsp;&nbsp;<b>' + group + '</b></td></tr>',
              );
              last = group;
            }
        });

      },
      columnDefs: [
        {
          // hide columns by index number
          targets: [0,1,2],
          visible: false,
        },

      ],
  });
  $("#cari").click(function(){
      datatable.ajax.reload( null, false );
  });

  $("#reset").on('click',function(){
      $("#id_bumn").val('').trigger("change");
      $("#id_jabatan").val('').trigger("change");
      $("#id_grup_jabat").val('').trigger("change");
      $("#id_periode").val('').trigger("change");
      $("#id_jenis_asal_instansi").val('').trigger("change");
      $("#id_asal_instansi").val('').trigger("change");
      $("#pejabataktif").val('AKTIF').trigger("change");
      $('#pejabat').val('');
      $('#nomor_sk').val('');
      $('#tgl_sk').val('');
      $('#awal_tgl').val('');
      $('#akhir_tgl').val('');
      $("#jenis_kelamin").val('').trigger("change");
      $("#id_agama").val('').trigger("change");
      $("#masa_jabatan").val('').trigger("change");
      $("#kewarganegaraan").val('').trigger("change");
      $("#id_suku").val('').trigger("change");
      $("#kelas").val('').trigger("change");
      $("#id_klaster").val('').trigger("change");
      datatable.ajax.reload( null, false );
  });
}

function onAsalInstansi(id_jenis_asal_instansi){
    $.ajax({
        url: "/administrasi/kelengkapansk/getasalinstansi?id_jenis_asal_instansi="+id_jenis_asal_instansi,
        type: "POST",
        dataType: "json",
        success: function(data){
                 var contentData = "";
                 $("#id_asal_instansi").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $("#id_asal_instansi").append(contentData);
                 $("#id_asal_instansi").trigger("change");

        }
    });
}
