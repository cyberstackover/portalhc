
<div class="kt-portlet__body">
    <input type="hidden" id="bumn" value="{{ $bumn }}">
    <input type="hidden" id="id_status_talenta" value="{{ $id_status_talenta }}">
    <!--begin: Datatable -->
    <div class="table-responsive">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="tabletalentarekap">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Kategori Jabatan</th>
                </tr>
                </thead>
        </table>
    </div>
    <!--end: Datatable -->
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var tabletalenta = $('#tabletalentarekap').on( 'preXhr.dt', function ( e, settings, processing ) {
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
              url : 'datatabletalentarekap',
              data: {
                  "bumn": $('#bumn').val(),
                  "id_status_talenta":  $('#id_status_talenta').val()
              }
            },
            columns: [
              { data: null, orderable: false, searchable: false},
              { data: 'nama_lengkap', name: 'nama_lengkap', searchable: true},
              { data: 'jabatan', name: 'jabatan', searchable: true},
              { data: 'kategori_jabatan', name: 'kategori_jabatan', searchable: true},
            ],
            drawCallback: function( settings ) {
              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;

              var info = tabletalenta.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                tabletalenta.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                } );
            }
        });
    });
</script>