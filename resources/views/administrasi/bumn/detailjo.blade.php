<div class="kt-portlet__head">
    <div class="kt-portlet__head-label">
        <h3 class="kt-portlet__head-title">
            {{$namaperusahaan->nama_lengkap}}
        </h3>
        <input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{(int)$id_perusahaan}}" />
        <input type="hidden" name="id_surat_keputusan" id="id_surat_keputusan" readonly="readonly" value="{{(int)$id_surat_keputusan}}" />
    </div>
</div>
<div class="kt-portlet__body">
    <div class="table-responsive">
        <table class="table table-striped- table-bordered table-hover table-checkable">
            <thead>
            <tr>
                <th><div align="center">Pejabat</div></th>
                <th><div align="center">Perusahaan</div></th>
                <th><div align="center">Jabatan</div></th>
                <th><div align="center">Nomor</div></th>
                <th><div align="center">Tanggal SK</div></th>
                <th><div align="center">Tanggal Awal</div></th>
                <th><div align="center">Tanggal Akhir</div></th>
            </tr>
                @foreach($collections as $data)
                <tr>
                    <td>{{$data->pejabat}}</td>
                    <td>{{$data->bumn}}</td>
                    <td>{{$data->nomenklatur_jabatan}}</td>
                    <td>{{$data->nomor}}</td>
                    <td>{{$data->tanggal_sk}}</td>
                    <td>{{$data->tanggal_awal}}</td>
                    <td>{{$data->tanggal_akhir}}</td>
                </tr>
                @endforeach
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
