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
          <table class="table table-striped- table-bordered table-hover table-checkable" id="tabledetail">
                 <thead>
                   <tr>
                     <th rowspan="2">No.</th>
                     <th rowspan="2">Perusahaan</th>
                     <th rowspan="2">Nama</th>
                     <th rowspan="2">Jabatan</th>
                     <th colspan="6"><div align="center">Detail SK</div></th>
                   </tr>
                   <tr>
                     <th>SK</th>
                     <th>Mulai</th>
                     <th>Berakhir</th>
                     <th>Periode</th>
                     <th>Asal Instansi</th>
                     <th>Keterangan</th>
                   </tr>
                 </thead>
          </table>
        </div>
        <!--end: Datatable -->
    </div>
</div>