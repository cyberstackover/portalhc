<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                {!! $title !!}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <div class="table-responsive">
          <table class="table table-striped- table-bordered table-hover table-checkable" id="tableperusahaan">
                 <thead>
                   <tr>
                        <th width="5%">No.</th>
                        <th width="10%">Perusahaan</th>
                        <th width="15%">Grup Jabat</th>
                        <th width="25%">Pejabat</th>
                        <th width="20%">Nomor SK</th>
                        <th width="10%">Awal Menjabat</th>
                        <th width="10%">Akhir Menjabat</th>
                        <th width="15%">Asal Instansi</th>
                   </tr>
                 </thead>
          </table>
        </div>
        <!--end: Datatable -->
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var tableperusahaan = $('#tableperusahaan').on( 'preXhr.dt', function ( e, settings, processing ) {
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
              url : 'datatableperusahaan',
              data: {
                  "id": {!! $id !!}
              }
            },
            columns: [
                { data: null, orderable: false, searchable: false},
                { data: 'bumns', name: 'bumns', searchable: false},
                { data: 'grup_jabat_nama', name: 'grup_jabat_nama', searchable: false},
                { data: 'pejabat', name: 'pejabat', searchable: true},
                { data: 'nomor', name: 'nomor', searchable: false},
                { data: 'tanggal_awal', name: 'tanggal_awal', searchable: false},
                { data: 'tanggal_akhir', name: 'tanggal_akhir', searchable: false},
                { data: 'instansi', name: 'instansi', searchable: false}
            ],
            drawCallback: function( settings ) {
              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;

              api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                  if ( last !== group ) {
                      $(rows).eq( i ).before(
                '<tr class="group"><td colspan="7" style="BACKGROUND-COLOR:rgb(180, 218, 252);font-weight:700;"><b>' + group + '</b></td></tr>',
                      );
                      last = group;
                  }
              } );

              var info = tableperusahaan.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                tableperusahaan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                } );
            },
            columnDefs: [
                {
                // hide columns by index number
                targets: [0,1,2],
                visible: false,
                },
                
            ],
        });
    });
</script>