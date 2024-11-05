var submenu;
var submenu_text;
var operator;
var opsi;
var opsi_text;
var alias;
var tipe;
var input_nilai;
var select_nilai;
var multi_nilai = [];
var multi_text = [];
var sorting = [];
var nilai = [];
var nilai_text = [];
var jml = 1;
let list_checked = [];

$(document).ready(function(){
    
    $("#tambah").on('click',function(){
        jml = $('.search-dynamic').length + 1;
        var search_master = $('.search-master').clone();
        $('.append-search').append(search_master);

        search_last = $('.append-search').find('.search-dynamic').last();
        search_last.addClass('search-append search-append-ke-' + jml);
        search_last.removeClass('search-master');
    
        menu_last = search_last.find('.menu');
        menu_last.addClass('menu-ke-' + jml);
        menu_last.removeClass('menu-ke-1');
        select2menu(jml);

        submenu_last = search_last.find('.submenu');
        submenu_last.addClass('submenu-ke-' + jml);
        submenu_last.removeClass('submenu-ke-1');
        select2submenu(jml);

        nilai_last = search_last.find('.nilai');
        nilai_last.addClass('nilai-ke-' + jml);
        nilai_last.removeClass('nilai-ke-1');
        select2nilai(jml);
        
        opsi_last = search_last.find('.opsi');
        opsi_last.addClass('opsi-ke-' + jml);
        opsi_last.removeClass('opsi-ke-1');
        select2opsi(jml);

        operator_last = search_last.find('.operator');
        operator_last.addClass('operator-ke-' + jml);
        operator_last.removeClass('operator-ke-1');
        select2operator(jml);

        sorting_last = search_last.find('.sorting');
        sorting_last.addClass('sorting-ke-' + jml);
        sorting_last.removeClass('sorting-ke-1');
        select2sorting(jml);

        delete_last = search_last.find('.delete');
        delete_last.addClass('delete-ke-' + jml);
        delete_last.removeClass('delete-ke-1');
        buttondelete(jml);
        
        multi_nilai[jml] = null;
        multi_text[jml] = null;
    }); 

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
        event.preventDefault();
        return false;
        }
    });
    
    $("#reset").on('click',function(event){
        $("#status_talenta").val('').trigger("change");
        $("#perusahaan").val('').trigger("change");
        $("#kelas").val('').trigger("change");
        $("#cluster").val('').trigger("change");
        $("select[name='submenu[]']").val('').trigger("change");
        $("select[name='operator[]']").val('').trigger("change");
        $("select[name='opsi[]']").val('').trigger("change");
        $("input[name='tipe[]']").val('').trigger("change");
        $("input[name='nilai[]']").val('').trigger("change");
        $("input[name='alias[]']").val('').trigger("change");
        $("select[name='select_nilai[]']").val('').trigger("change");
        $("select[name='multi_nilai[]']").val('').trigger("change");
    }); 

	$('body').on('click','#compare',function(){
        var ids = $(".checked_list").val();
		winform(urlcompare, {'id':ids, 'dynamic_filter_id':submenu}, 'Compare Talenta');
	});

	$('body').on('click','.cls-add',function(){
		winform(urlcreate, {}, 'Tambah Talenta');
	});

    $('body').on('click','.cls-minicv',function(){
        winform(urlminicv, {'id':$(this).data('id')}, 'Curriculum Vitae');
    })

    $('body').on('click','.cls-logstatus',function(){
        winform(urllogstatus, {'id':$(this).data('id')}, 'Log Status');
    })
    
	$('body').on('click','#export',function(){
        var validate = validateform();
        
        if(validate){
            getinputvalue();
            exportExcel();
        }
	});

    $('.kt-select2').select2({
            placeholder: "Pilih"
        });

    multi_nilai[0] = null;
    multi_text[0] = null;
    sorting[0] = null;
    $('.select-sorting').change(function() {
        var id = $(this).attr("data-id");
        sorting[id-1] = $(this).val();
    }); 

    $('#cari').on('click', function(event){
        var validate = validateform();
        
        if(validate){
            $('#datatable').DataTable().clear();
            $('#datatable').DataTable().destroy();
            $('#kt_portlet_tools_6').find('#datatable').DataTable().clear();
            $('#kt_portlet_tools_6').find('#datatable').DataTable().destroy();
            var fields = $(".table");
            $.each(fields, function(i, field) {
                $(field).DataTable().clear();
                $(field).DataTable().clear();
            });

            getinputvalue();
            addcolumn();
            show_query();
            $(".hasil-search").show();
            $(".btn-header").show();
            
            list_checked = [];
            setDatatable();
        }
    });

    select2submenu(1);
    select2opsi(1);
});

function talenta_checked(element) {
    
    let item = $(element).val();
    if ($(element).is(":checked")) {
        if (list_checked.indexOf($(element).val()) == -1) {
            list_checked.push(item);
        }
    } else {
        list_checked.splice(list_checked.indexOf(item), 1);
    }
    $(".checked_list").val(list_checked);

    var checked_list = $(".checked_list").val();
    var array = checked_list.split(",");
    var length = array.length;
    console.log(checked_list);
    console.log(length);

    if(length < 2){
        $(".btn-footer").hide();
    }else{
        $(".btn-footer").show();
    }
    
    if(length > 3){
        $(element).prop('checked', false);
        list_checked.splice(list_checked.indexOf(item), 1);
        $(".checked_list").val(list_checked);
        
        var msgerror = 'Pilih Maksimal 3 Talenta';
        swal.fire({
            title: "Gagal",
            html: msgerror,
            type: 'error',

            buttonsStyling: false,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
            confirmButtonClass: "btn btn-default"
        }); 
    }
}

function getinputvalue() {
    submenu = $("select[name='submenu[]']").map(function(){return $(this).val();}).get();
    submenu_text = $("select[name='submenu[]'] option:selected").map(function(){return $(this).text();}).get();
    operator = $("select[name='operator[]']").map(function(){return $(this).val();}).get();
    opsi = $("select[name='opsi[]']").map(function(){return $(this).val();}).get();
    opsi_text = $("select[name='opsi[]'] option:selected").map(function(){return $(this).text();}).get();
    tipe = $("input[name='tipe[]']").map(function(){return $(this).val();}).get();
    input_nilai = $("input[name='nilai[]']").map(function(){return $(this).val();}).get();
    alias = $("input[name='alias[]']").map(function(){return $(this).val();}).get();
    select_nilai = $("select[name='select_nilai[]']").map(function(){return $(this).val();}).get();

    var i_select_nilai = 0;
    var i_input_nilai = 0;
    for(var i = 0, len = submenu.length; i < len; ++i) {
        if(tipe[i] == 'text' || tipe[i] == 'number'){
            nilai[i] = input_nilai[i_input_nilai];
            nilai_text[i] = input_nilai[i_input_nilai];
            i_input_nilai++;
        }else if (tipe[i] == 'multi select'){
            nilai[i] = multi_nilai[i];
            nilai_text[i] = multi_text[i];
        }else{
            nilai[i] = select_nilai[i_select_nilai];
            nilai_text[i] = select_nilai[i_select_nilai];
            i_select_nilai++;
        }
    }
}

function addcolumn() {
    var trow = $('#datatable_th');
    var th = '<th ><div align="center"></div></th><th width="5%">No.</th><th >Nama Lengkap</th><th >BUMN</th><th >Jabatan</th>';
    for(var i = 0, len = submenu.length; i < len; ++i) {
        if(submenu_text[i] != 'Nama' && submenu_text[i] != 'Jabatan'){
            th += '<th>'+submenu_text[i]+'</th>';
        }
    }
    trow.html(th);
}

function validateform() {
    var fields = $(".div-required");
    var status = true;
    $.each(fields, function(i, field) {
      
      field=$(field).find('input, select, textarea')[0];
      if (!field.value || (field.type=='checkbox' && !field.checked)){
        var fieldname = field.name.substr(0, (field.name.length-2));
        if(fieldname == 'submenu') fieldname = 'Select Filter';
        var msgerror = fieldname + ' is required';
        swal.fire({
            title: "Error System",
            html: msgerror,
            type: 'error',

            buttonsStyling: false,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
            confirmButtonClass: "btn btn-default"
        }); 
        field.focus();
        status = false;
        return false;
      }
    }); 
    return status;
  }

function show_query(){
    var query = '<b>Search :</b><br>';
    for(var i = 0, len = submenu.length; i < len; ++i) {
        if(i == len-1){
            query += submenu_text[i] + ' ' + opsi_text[i] + ' ' + nilai_text[i];
        }else{
            query += submenu_text[i] + ' ' + opsi_text[i] + ' ' + nilai_text[i] + ' ' + operator[i] + '<br>';
        }
    }
    $(".div-query").show();
    $(".query").html(query);
}

function buttondelete(jml){
    var contentData = '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete btn-sm delete-search"><i class="flaticon-delete"></i></button>';
    $('.delete-ke-' + jml).html(contentData); 

    $('.delete-search').click(function(a){
        $(this).closest('.search-dynamic').remove();
    });    
} 

function select2nilai(jml){
    var contentData = '<input type="text" name="nilai[]" class="form-control">';
    $('.nilai-ke-' + jml).html(contentData);     
} 

function select2submenuold(jml){
    var contentData = '<select class="form-control kt-select2" name="submenu[]" onchange="return onChangeSubMenu(this.value, '+jml+')" required><option></option></select>';
    $('.submenu-ke-' + jml).html(contentData);
    $('.submenu-ke-' + jml).find('.kt-select2').select2({
        placeholder: "Pilih"
    });                  
} 

function select2opsiequal(jml){
    var contentData = '<select class="form-control kt-select2" name="opsi[]"><option value="=">is equal to</option></select>';   
    $('.opsi-ke-' + jml).html(contentData);
    $('.opsi-ke-' + jml).find('.kt-select2').select2({
        placeholder: "Pilih"
    });                  
} 

function select2operator(jml){
    var contentData = '<select class="form-control kt-select2" name="operator[]"><option value="and">and</option><option value="or">or</option></select>';   
    $('.operator-ke-' + jml).html(contentData);
    $('.operator-ke-' + jml).find('.kt-select2').select2({
        placeholder: "Pilih"
    });                  
} 

function select2sorting(jml){
    var contentData = '<select class="form-control kt-select2 select-sorting" name="sorting[]" data-id="'+jml+'"><option></option><option value="asc">Ascending</option><option value="desc">Descending</option></select>';   
    $('.sorting-ke-' + jml).html(contentData);
    $('.sorting-ke-' + jml).find('.kt-select2').select2({
        placeholder: "Pilih"
    });      

    sorting[jml-1] = null;
    $('.select-sorting').change(function() {
        var id = $(this).attr("data-id");
        sorting[id-1] = $(this).val();
    });             
} 

function select2menu(jml){
    $.ajax({
        url: "/fetch/referensi/getdynamicsearchmenu",
        type: "POST",
        dataType: "json", 
        beforeSend: function () {
            KTApp.block('.kt-form', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Sedang proses, silahkan tunggu ...'
            });
        },
        success: function(data){
                KTApp.unblock('.kt-form');
                var contentData = '<select class="form-control kt-select2" name="menu[]" onchange="return onChangeMenu(this.value, '+jml+')"><option></option>';
                for(var i = 0, len = data.length; i < len; ++i) {
                    contentData += "<option value='"+data[i].nama+"'>"+data[i].nama+"</option>";
                }
                contentData += "</select>";
                $('.menu-ke-' + jml).html(contentData);
                $('.menu-ke-' + jml).find('.kt-select2').select2({
                    placeholder: "Pilih"
                });                  
        }
    });
}  

function select2opsi(jml, is_number = false){
    $.ajax({
        url: "/fetch/referensi/getdynamicsearchoperator?is_number="+is_number,
        type: "POST",
        dataType: "json", 
        beforeSend: function () {
            KTApp.block('.kt-form', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Sedang proses, silahkan tunggu ...'
            });
        },
        success: function(data){
                KTApp.unblock('.kt-form');
                var contentData = '<select class="form-control kt-select2" name="opsi[]"><option></option>';
                for(var i = 0, len = data.length; i < len; ++i) {
                    contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                }
                contentData += "</select>";
                $('.opsi-ke-' + jml).html(contentData);
                $('.opsi-ke-' + jml).find('.kt-select2').select2({
                    placeholder: "Pilih"
                });                  
        }
    });
}  

function onlyNumberKey(e) {
    var ASCIICode = (e.which) ? e.which : e.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}

function allowAlphaNumericSpace(e) {
    var code = ('charCode' in e) ? e.charCode : e.keyCode;
    if (!(code == 32) && // space
        !(code > 47 && code < 58) && // numeric (0-9)
        !(code > 64 && code < 91) && // upper alpha (A-Z)
        !(code > 96 && code < 123)) { // lower alpha (a-z)
        e.preventDefault();
    }
}

function select2submenu(jml){
    $.ajax({
        url: "/fetch/referensi/getdynamicsearchsubmenu",
        type: "POST",
        dataType: "json", 
        beforeSend: function () {
            KTApp.block('.kt-form', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Sedang proses, silahkan tunggu ...'
            });
        },
        success: function(data){
            KTApp.unblock('.kt-form');
            var contentData = '<select class="form-control kt-select2" name="submenu[]" onchange="return onChangeSubMenu(this.value, '+jml+')" required><option></option>';
            for(var i = 0, len = data.length; i < len; ++i) {
                contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
            }
            contentData += "</select>";
            $('.submenu-ke-' + jml).html(contentData);
            $('.submenu-ke-' + jml).find('.kt-select2').select2({
                placeholder: "Pilih"
            });                  
                                    
        }
    });
}

function onChangeSubMenu(id, jml){
    if(id){
        $.ajax({
            url: "/fetch/referensi/getdynamicsearchbyid?id="+id,
            type: "POST",
            dataType: "json", 
            beforeSend: function () {
                KTApp.block('.kt-form', {
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'primary',
                    message: 'Sedang proses, silahkan tunggu ...'
                });
            },
            success: function(data){
                KTApp.unblock('.kt-form');
                var standar_value = data.standar_value.split("|");
                var contentData = '<input type="hidden" name="tipe[]" value="'+ data.tipe+'">';
                contentData += '<input type="hidden" name="alias[]" value="'+ data.alias+'">';
                
                if(data.tipe == 'text'){
                    contentData += '<input type="text" name="nilai[]" class="form-control" onkeypress="return allowAlphaNumericSpace(event)">';
                }
                else if(data.tipe == 'number'){
                    contentData += '<input type="text" name="nilai[]" class="form-control"  onkeypress="return onlyNumberKey(event)">';
                }
                else if(data.tipe == 'multi select'){
                    contentData += '<select class="form-control kt-select2 multi-select" multiple="" name="multi_nilai[]" data-id="'+jml+'"><option></option>';
                    for(var i = 0, len = data.select_nilai.length; i < len; ++i) {
                        contentData += "<option value='"+data.select_nilai[i].id+"'>"+data.select_nilai[i].nama+"</option>";
                    }
                    contentData += "</select>";
                }
                else if(data.tipe == 'select'){
                    if(standar_value.length>1){
                        contentData += '<select class="form-control kt-select2" name="select_nilai[]"><option></option>';
                        standar_value.forEach(function(val) {
                            contentData += "<option value='"+val+"'>"+val+"</option>";
                        });
                        contentData += "</select>";
                    }else{
                        contentData += '<select class="form-control kt-select2" name="select_nilai[]"><option></option>';
                        for(var i = 0, len = data.select_nilai.length; i < len; ++i) {
                            contentData += "<option value='"+data.select_nilai[i].nama+"'>"+data.select_nilai[i].nama+"</option>";
                        }
                        contentData += "</select>";
                    }
                }

                if(data.tipe == 'multi select' || data.tipe == 'select'){
                    select2opsiequal(jml, data);
                }else{
                    select2opsi(jml, data.is_number);
                }
                

                $('.nilai-ke-' + jml).html(contentData);
                $('.nilai-ke-' + jml).find('.kt-select2').select2({
                    placeholder: "Pilih"
                }); 
                $('.multi-select').change(function() {
                    var id = $(this).attr("data-id");
                    multi_nilai[id-1] = $(this).val();
                    multi_text[id-1] = $("option:selected", this).text();
                }); 
            }
        });
    }
}

function onChangeBumn(id){
    id_kelas = $('#kelas').val();
    id_cluster = $('#cluster').val();
    if(id_kelas == 'All') id_kelas = '';
    if(id_cluster == 'All') id_cluster = '';

    $.ajax({
        url: "/fetch/referensi/getdynamicsearchbumn?id_kelas="+id_kelas+"&id_cluster="+id_cluster,
        type: "POST",
        dataType: "json", 
        beforeSend: function () {
            KTApp.block('.kt-form', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Sedang proses, silahkan tunggu ...'
            });
        },
        success: function(data){
            KTApp.unblock('.kt-form');
            
            contentData = '<option></option>';
            for(var i = 0, len = data.length; i < len; ++i) {
                contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
            }

            $('#perusahaan').html(contentData);
        }
    });
}

function setDatatable(){
  var tablecolumns = [
        { data: 'action', className: "text-center", orderable: false, searchable: false},
        { data: null, orderable: false, searchable: false},
        { data: 'nama_lengkap', name: 'nama_lengkap', orderable: false},
        { data: 'stalenta', name: 'stalenta', orderable: false},
        { data: 'jabatan', name: 'jabatan', orderable: false}
      ];
      
    for(var i = 0, len = submenu.length; i < len; ++i) {  
        if(submenu_text[i] != 'Nama' && submenu_text[i] != 'Jabatan'){
            var newcolumn = { data: alias[i], name: alias[i], orderable: false};
            if( tipe[i] == 'number' ) newcolumn = { data: alias[i], name: alias[i], orderable: false, className: "text-right"};
            tablecolumns.push(newcolumn);
        }
    }
      
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
      destroy: true,
      ajax: {
          url: urldatatable,
          type: 'POST',
          data: function (d) {
              d.dynamic_filter_id = submenu,
              d.operator = operator,
              d.sorting = sorting,
              d.opsi = opsi,
              d.input_nilai = input_nilai,
              d.select_nilai = select_nilai, 
              d.nilai = nilai, 
              d.id_perusahaan = $("select[name='perusahaan']").val(),
              d.kelas = $("select[name='kelas']").val(),
              d.cluster = $("select[name='cluster']").val(),
              d.status_talenta = $("select[name='status_talenta']").val()
          }
      },
      columns: tablecolumns,
      drawCallback: function( settings ) {
        var info = datatable.page.info();
          $('[data-toggle="tooltip"]').tooltip();
          datatable.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = info.start + i + 1;
          } );
      }
  });
}
  
function exportExcel()
{
    $.ajax({
        type: 'post',
        data: {
            'dynamic_filter_id' : submenu,
            'operator' : operator,
            'sorting' : sorting,
            'opsi' : opsi,
            'input_nilai' : input_nilai,
            'select_nilai' : select_nilai, 
            'nilai' : nilai, 
            'id_perusahaan' : $("select[name='perusahaan']").val(),
            'kelas' : $("select[name='kelas']").val(),
            'status_talenta' : $("select[name='status_talenta']").val(),
            'cluster' : $("select[name='cluster']").val()
        },
        beforeSend: function () {
            KTApp.block('.kt-form', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Sedang proses, silahkan tunggu ...'
            });
        },
        url: urlexport,
        xhrFields: {
            responseType: 'blob',
        },
        success: function(data){
            KTApp.unblock('.kt-form');

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            
            today = dd + '-' + mm + '-' + yyyy;
            var filename = 'Data Talenta '+today+'.xlsx';

            var blob = new Blob([data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;

            document.body.appendChild(link);

            link.click();
            document.body.removeChild(link);
        },
        error: function(jqXHR, exception){
            KTApp.unblock('.kt-form');
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
    return false;
}
