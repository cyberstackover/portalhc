$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {
		setFormValidate();
    onReadEvent();
	});
  
});

function setFormValidate(){
    $('#form-role').validate({
        rules: {
               name:{
                       required: true
               },
               permission:{
                       required: true
               }               		               		                              		               		               
        },
        messages: {
                   name: {
                       required: "Nama Role wajib diinput"
                   },
                   permission: {
                       required: "Permission wajib dipilih minimal Satu"
                   }                                      		                   		                   
        },
        // ignore: ':hidden:not(.summernote)',	        
        highlight: function(element) {
            $(element).closest('.form-control').addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).closest('.form-control').removeClass('is-invalid');
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        errorPlacement: function(error, element) {
            if(element.parent('.validated').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
       submitHandler: function(form){
               var typesubmit = $("input[type=submit][clicked=true]").val();

               $(form).ajaxSubmit({
                   type: 'post',
                   url: urlstore,
                   data: {source : typesubmit},
                   dataType : 'json',
                   beforeSend: function(){
			            KTApp.block('.kt-form', {
			                overlayColor: '#000000',
			                type: 'v2',
			                state: 'primary',
			                message: 'Sedang proses, silahkan tunggu ...'
			            });
                   },
                   success: function(data){
	                   KTApp.unblock('.kt-form');

			           swal.fire({
			                title: data.title,
			                html: data.msg,
			                type: data.flag,

			                buttonsStyling: false,

			                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
			                confirmButtonClass: "btn btn-default"
			           });	                   

	                   if(data.flag == 'success') {
	                     $('#winform').modal('hide');
	                     datatable.ajax.reload( null, false );
	                   }
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
    });		
}

function onReadEvent(){

  onLoadTreeMenu();
  onLoadMultiSelect();
}

function onLoadTreeMenu(){
  $('#checkTree').jstree({
      'core' : {
        'themes' : {
          'responsive': true
        },
              'data': {        
                  type: "GET",
                  dataType: 'json',
                  url: urlgettreemenubyrole+'/'+$('#id').val()
              }     
      },
      'types' : {
          'default' : {
              'icon' : 'fa fa-folder'
          },
          'file' : {
              'icon' : 'fa fa-file'
          }
      },
      'plugins' : ['types', 'checkbox']
  }).on('changed.jstree', function (e, data) {
  var i, j, r = [];
  $('#menu').val('');
  for(i = 0, j = data.selected.length; i < j; i++) {
    r.push(data.instance.get_node(data.selected[i]).id);    
  }
  $('#menu').val(r);
  });  
}

function onLoadMultiSelect(){
  $('.multi-select').multiSelect({
    selectableOptgroup: false, 
      selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Cari ...'>",
      selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Cari ...'>",
      afterInit: function (ms) {
          var that = this,
              $selectableSearch = that.$selectableUl.prev(),
              $selectionSearch = that.$selectionUl.prev(),
              selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
              selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

          that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
              .on('keydown', function (e) {
                  if (e.which === 40) {
                      that.$selectableUl.focus();
                      return false;
                  }
              });

          that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
              .on('keydown', function (e) {
                  if (e.which == 40) {
                      that.$selectionUl.focus();
                      return false;
                  }
              });
      },
      afterSelect: function () {
          this.qs1.cache();
          this.qs2.cache();
      },
      afterDeselect: function () {
          this.qs1.cache();
          this.qs2.cache();
      }
  });  
}