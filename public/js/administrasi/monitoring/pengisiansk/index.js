var datatable;
var datatablegrup;
var unit;

$(function() {
    loadDatatableGrup();
});
/*var portlet = new KTPortlet('kt_portlet_tools_6');

portlet.on('afterExpand', function(portlet) {
    $(".kt-select2").select2({
        placeholder: "Pilih...",
        allowClear: true
    });
    
});*/

$(document).ready(function(){

    $('a[href="#grup"]').on('click',function(){
        $(".cari").hide();
       });
       $('a[href="#induk"]').on('click',function(){
        $(".cari").show();
       });

  $('.kt-select2').select2({
      placeholder: "Pilih Perusahaan",
      allowClear: false 
  });

  


  /*var pickerOptsGeneral = {
      orientation: "left",
      autoclose: true,
      format: 'dd/mm/yyyy'
  };

  $('.cls-datepicker').datepicker(pickerOptsGeneral);*/



  $('body').on('change', '#form_perusahaan', function() {
    $('.export2').attr('data-param',$('#form_perusahaan').val());
    loadDatatableGrup();
  });

  $('.export2').click(function(){
    var param = $('#form_perusahaan').val();
    if(param !=''){
        export2_param = export2_param.replace(':param',param);
        window.open(export2_param,"_blank");
    } else{
        window.open(export2,"_blank");
    }
  })

  setDatatableGrup();
	setDatatable();
});

function loadDatatableGrup(){
    unit = $('#form_perusahaan').val();

   
    if(unit){
      setDatatableGrup(unit);
    }
}

function setDatatableGrup(unit){
  datatablegrup = $('#datatablegrup').on( 'preXhr.dt', function ( e, settings, processing ) {
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
      responsive: true,
      bFilter: false,
      ajax: {
          url: urldatatablegrup,
          type: 'POST',
          data: function (d) {
              d.id_bumn = unit
          }
      },
      columns: [
        { data: 'bumn_nama', name: 'bumn_nama', searchable: true},
        { data: 'jumlah_direksi', name: 'jumlah_direksi', searchable: false, className: "text-right"},
        { data: 'jumlah_dirkomwas', name: 'jumlah_dirkomwas', searchable: false, className: "text-right"},
        { data: 'jumlah_organ_isi', name: 'jumlah_organ_isi', searchable: false, className: "text-right"},
        { data: 'presentase_isi', name: 'presentase_isi', searchable: false, className: "text-right"}
      ],
      drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          var last=null;
          var subTotal = new Array();
          var groupID = -1;
          var aData = new Array();
          var index = 0;

          api.column(1, { page: 'current' }).data().each(function (group, i) {

              //console.log(group+">>>"+i);
              
              var vals = api.row(api.row($(rows).eq(i)).index()).data();

              var intVall = function ( i ) {
                  return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
              };

              var jumlah_direksi = vals['jumlah_direksi'] ? intVall(vals['jumlah_direksi']) : 0;
              var jumlah_dirkomwas = vals['jumlah_dirkomwas'] ? intVall(vals['jumlah_dirkomwas']) : 0;
              var jumlah_organ_isi = vals['jumlah_organ_isi'] ? intVall(vals['jumlah_organ_isi']) : 0;
              var presentase_isi = vals['presentase_isi'] ? intVall(vals['presentase_isi']) : 0;

              //console.log(intVall(vals['utang']));

              if (typeof aData[group] == 'undefined') {
               aData[group] = new Array();
               aData[group].rows = [];
               aData[group].jumlah_direksi = [];
               aData[group].jumlah_dirkomwas = [];
               aData[group].jumlah_organ_isi = [];
               aData[group].presentase_isi = [];
              }
        
              aData[group].rows.push(i); 
              aData[group].jumlah_direksi.push(jumlah_direksi);
              aData[group].jumlah_dirkomwas.push(jumlah_dirkomwas);
              aData[group].jumlah_organ_isi.push(jumlah_organ_isi);
              aData[group].presentase_isi.push(presentase_isi);

              /*if (last !== group) {

                  $(rows).eq(i).before(
                      '<tr class="group"><td colspan="8" style="BACKGROUND-COLOR:rgb(237, 208, 0);font-weight:700;color:#006232;">' + group  + '</td></tr>'
                  );

                  last = group;
              }*/
          });

          var idx= 0;

    
          for(var bumn_nama in aData){
     
              idx =  Math.max.apply(Math,aData[bumn_nama].rows);
    
              var sum1 = 0;
              var sum2 = 0;
              var sum3 = 0;
              var sum4 = 0; 
                 $.each(aData[bumn_nama].pokok,function(k,v){
                      sum1 = sum1 + v;
                 });
                 $.each(aData[bumn_nama].denda,function(k,v){
                      sum2 = sum2 + v;
                 });
                 $.each(aData[bumn_nama].interim,function(k,v){
                      sum3 = sum3 + v;
                 });
                 $.each(aData[bumn_nama].utang,function(k,v){
                      sum4 = sum4 + v;
                 });
                
                  
          };
      },
      footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            };

            $(api.column(1).footer()).html(api.column(1).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                })
            );
            $(api.column(2).footer()).html(api.column(2).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                })
            );
            $(api.column(3).footer()).html(api.column(3).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                })
            );
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
      responsive: true,
      serverSide: true,
      processing: true,
      bFilter: false,
      bLengthChange : false,
      bInfo:false,
      pageLength: -1,
      ajax: {
          url: urldatatable,
          type: 'POST',
          data: function (d) {
              d.id_bumn = $('select[name=id_bumn]').val();
              /*d.id_jabatan = $('select[name=id_jabatan]').val();
              d.id_grup_jabat = $('select[name=id_grup_jabat]').val();
              d.id_periode = $('select[name=id_periode]').val();
              d.pejabat = $('input[name=pejabat]').val();
              d.nomor_sk = $('input[name=nomor_sk]').val();
              d.tgl_sk = $('input[name=tgl_sk]').val();
              d.awal_tgl = $('input[name=awal_tgl]').val();
              d.akhir_tgl = $('input[name=akhir_tgl]').val();*/
          }
      },
      columns: [
        { data: 'bumn_nama', name: 'bumn_nama', searchable: true},
        { data: 'jumlah_direksi', name: 'jumlah_direksi', searchable: false, className: "text-right"},
        { data: 'jumlah_dirkomwas', name: 'jumlah_dirkomwas', searchable: false, className: "text-right"},
        { data: 'jumlah_organ_isi', name: 'jumlah_organ_isi', searchable: false, className: "text-right"},
        { data: 'jumlah_direksi_anak', name: 'jumlah_direksi_anak', searchable: false, className: "text-right"},
        { data: 'jumlah_dirkomwas_anak', name: 'jumlah_dirkomwas_anak', searchable: false, className: "text-right"},
        { data: 'jumlah_organ_isi_anak', name: 'jumlah_organ_isi_anak', searchable: false, className: "text-right"},
        { data: 'presentase_isi', name: 'presentase_isi', searchable: false, className: "text-right"}
      ],
      drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          var last=null;
          var subTotal = new Array();
          var groupID = -1;
          var aData = new Array();
          var index = 0;

          api.column(1, { page: 'current' }).data().each(function (group, i) {

              //console.log(group+">>>"+i);
              
              var vals = api.row(api.row($(rows).eq(i)).index()).data();

              var intVall = function ( i ) {
                  return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
              };

              var jumlah_direksi = vals['jumlah_direksi'] ? intVall(vals['jumlah_direksi']) : 0;
              var jumlah_dirkomwas = vals['jumlah_dirkomwas'] ? intVall(vals['jumlah_dirkomwas']) : 0;
              var jumlah_organ_isi = vals['jumlah_organ_isi'] ? intVall(vals['jumlah_organ_isi']) : 0;
              var jumlah_direksi_anak = vals['jumlah_direksi_anak'] ? intVall(vals['jumlah_direksi_anak']) : 0;
              var jumlah_dirkomwas_anak = vals['jumlah_dirkomwas_anak'] ? intVall(vals['jumlah_dirkomwas_anak']) : 0;
              var jumlah_organ_isi_anak = vals['jumlah_organ_isi_anak'] ? intVall(vals['jumlah_organ_isi_anak']) : 0;
              var presentase_isi = vals['presentase_isi'] ? intVall(vals['presentase_isi']) : 0;

              //console.log(intVall(vals['utang']));

              if (typeof aData[group] == 'undefined') {
               aData[group] = new Array();
               aData[group].rows = [];
               aData[group].jumlah_direksi = [];
               aData[group].jumlah_dirkomwas = [];
               aData[group].jumlah_organ_isi = [];
               aData[group].jumlah_direksi_anak = [];
               aData[group].jumlah_dirkomwas_anak = [];
               aData[group].jumlah_organ_isi_anak = [];
               aData[group].presentase_isi = [];
              }
        
              aData[group].rows.push(i); 
              aData[group].jumlah_direksi.push(jumlah_direksi);
              aData[group].jumlah_dirkomwas.push(jumlah_dirkomwas);
              aData[group].jumlah_organ_isi.push(jumlah_organ_isi);
              aData[group].jumlah_direksi_anak.push(jumlah_direksi_anak);
              aData[group].jumlah_dirkomwas_anak.push(jumlah_dirkomwas_anak);
              aData[group].jumlah_organ_isi_anak.push(jumlah_organ_isi_anak);
              aData[group].presentase_isi.push(presentase_isi);

              /*if (last !== group) {

                  $(rows).eq(i).before(
                      '<tr class="group"><td colspan="8" style="BACKGROUND-COLOR:rgb(237, 208, 0);font-weight:700;color:#006232;">' + group  + '</td></tr>'
                  );

                  last = group;
              }*/
          });

          var idx= 0;

    
          for(var bumn_nama in aData){
     
              idx =  Math.max.apply(Math,aData[bumn_nama].rows);
    
              var sum1 = 0;
              var sum2 = 0;
              var sum3 = 0;
              var sum4 = 0;
              var sum5 = 0;
              var sum6 = 0; 
                 $.each(aData[bumn_nama].pokok,function(k,v){
                      sum1 = sum1 + v;
                 });
                 $.each(aData[bumn_nama].denda,function(k,v){
                      sum2 = sum2 + v;
                 });
                 $.each(aData[bumn_nama].interim,function(k,v){
                      sum3 = sum3 + v;
                 });
                 $.each(aData[bumn_nama].utang,function(k,v){
                      sum4 = sum4 + v;
                 });
                 $.each(aData[bumn_nama].utang,function(k,v){
                    sum5 = sum5 + v;
                });
               $.each(aData[bumn_nama].utang,function(k,v){
                      sum6 = sum6 + v;
                 });
                
                  
          };
      },
      footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            };

            $(api.column(1).footer()).html(api.column(1).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                })
            );
            $(api.column(2).footer()).html(api.column(2).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                })
            );
            $(api.column(3).footer()).html(api.column(3).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                })
            );
            $(api.column(4).footer()).html(api.column(4).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            })
        );
        $(api.column(5).footer()).html(api.column(5).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            })
        );
        $(api.column(6).footer()).html(api.column(6).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            })
        );
        }
  });
  $("#cari").click(function(){
      console.log('masuk');
      datatable.ajax.reload( null, false );
  });

  $("#reset").on('click',function(){
      $("#id_bumn").val('').trigger("change");
    //   $("#id_jabatan").val('').trigger("change");
    //   $("#id_grup_jabat").val('').trigger("change");
    //   $("#id_periode").val('').trigger("change");
    //   $('#pejabat').val('');
    //   $('#nomor_sk').val('');
    //   $('#tgl_sk').val('');
    //   $('#awal_tgl').val('');
    //   $('#akhir_tgl').val('');
      datatable.ajax.reload( null, false );
  });
}
