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
          <table class="table table-striped- table-bordered table-hover table-checkable" id="tablekontribusi">
                 <thead>
                   <tr>
                     <th>No.</th>
                     <th>Instansi</th>
                     <th>Nama</th>
                     <th>Jabatan</th>
                     <th>Perusahaan</th>
                     <th>Awal Menjabat</th>
                     <th>Akhir Menjabat</th>
                     <th>Nomor SK</th>
                   </tr>
                 </thead>
          </table>
        </div>
        <!--end: Datatable -->
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var tablekontribusi = $('#tablekontribusi').on( 'preXhr.dt', function ( e, settings, processing ) {
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
              url : 'datatablekontribusi',
              data: {
                  "idx": {!! $idx !!}
              }
            },
            columns: [
              { data: null, orderable: false, searchable: false},
              { data: 'instansi', orderable: false, searchable: true, visible: false},
              { data: 'pejabat', name: 'pejabat', searchable: true},
              { data: 'nama_jabatan', name: 'nama_jabatan', searchable: true},
              { data: 'perusahaan', name: 'perusahaan', searchable: true},
              { data: 'tanggal_awal', orderable: false, searchable: true},
              { data: 'tanggal_akhir', orderable: false, searchable: true},
              { data: 'nomor', name: 'nomor', searchable: true},
            ],
            drawCallback: function( settings ) {
              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;

              api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                  if ( last !== group ) {
                      $(rows).eq( i ).before(
                          '<tr><td colspan="9" style="BACKGROUND-COLOR:rgb(180, 218, 252);font-weight:700;">'+group+'</td></tr>'
                      );
                      last = group;
                  }
              } );

              var info = tablekontribusi.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                tablekontribusi.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                } );
            }
        });
    });
</script>