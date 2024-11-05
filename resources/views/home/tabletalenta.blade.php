<style>
  .foto-talenta {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    height: 235px;
  }
</style>
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
        <form class="kt-form kt-form--label-right" method="POST" enctype="multipart/form-data" id="form-import" action="/keterangan/import_excel" >
            @csrf
            <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="insert" />
            <div class="kt-portlet__body">
                <div class="form-group row">
                    <div class="col-lg-8">    
                        <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nama_lengkap}}" disabled/>
                        </div>
                        <div class="form-group">
                        <label>NIK</label>
                        <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nik}}" disabled/>
                        </div>                
                        <div class="form-group">
                        <label>Asal Instansi</label>
                        <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->jenis_asal_instansi }} {{ ($talenta->instansi ? ' - '.$talenta->instansi : '') }}" disabled/>
                        </div>
                    </div>
                    <div class="col-lg-4">    
                        <label>
                        <img class="foto-talenta"src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'L' ? 'img/male.png': 'img/female.png')) }}" alt=""> 
                        </label>
                    </div>	            
                </div>
            </div>

            <!--begin: Datatable -->
            <div class="table-responsive">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="tabletalenta">
                    <thead>
                    <tr>
                            <th width="5%">No.</th>
                            <th>Perusahaan</th>
                            <th>Jabatan</th>
                            <th class="text-center">Tanggal Awal</th>
                            <th class="text-center">Tanggal Akhir</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $no=1; @endphp
                    @foreach($jabatan as $val)
                    <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $val->nama_perusahaan }}</td>
                            <td>{{ $val->jabatan }}</td>
                            <td class="text-center">{{ @$val->tanggal_awal != '' ? \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_awal)->format('d/m/Y') : '' }}</td>
                            <td class="text-center">{{ @$val->tanggal_akhir != '' ? \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_akhir)->format('d/m/Y') : '' }}</td>
                    </tr>
                    @endforeach
                    </tbody>
            </table>
            </div>
            <!--end: Datatable -->
        </form>
    </div>
</div>