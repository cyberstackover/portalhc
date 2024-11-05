
<div class="kt-portlet__body">
    <div class="form-group row">
        <div class="col-lg-12">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablelog">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th >Status</th>  
                        <th >Waktu</th> 
                        <th >User</th>   
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var urldatatable = "{{route('talenta.register.table_log', ['id_talenta' => $talenta->id])}}";
    var datatable;

    $(document).ready(function(){
        setDatatable();
    });
    
    function setDatatable(){
        var datatable = $('#datatablelog').on( 'preXhr.dt', function ( e, settings, processing ) {
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
            search: {
                caseInsensitive: true
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
            searchHighlight: true,
            ajax: {
                url: urldatatable,
                type: 'GET'
            },
            columns: [
                { data: null, orderable: false, searchable: false},
                { data: 'status_talenta', name: 'status_talenta', searchable: true},
                { data: 'waktu', name: 'waktu', searchable: true},
                { data: 'user', name: 'user', searchable: true}
            ],
            drawCallback: function( settings ) {
                var info = datatable.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                } );
            }
        });
    }
</script>