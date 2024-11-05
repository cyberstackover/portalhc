@php $no=1; @endphp
@if (count($remunerasis) > 0)
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover tree">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nama</th>
                <th style="width: 5%;">Prosentase</th>
                <th style="width: 15%;">Sumber Pengali</th>
                <th style="width: 15%;">Gaji Pokok</th>
                <th style="width: 15%;">Take Home Pay</th>
                <th style="width: 15%;">Tantiem</th>
                <!-- <th style="width: 10%;">Aksi</th> -->
            </tr>
        </thead>
        <tbody>
         @foreach ($remunerasis as $remunerasi)
            @if($remunerasi->parent_id == NULL)
            <tr class="treegrid-{{$remunerasi->id}}" data-count="2">
                <td>{{$no}}</td>
                <td>{{$remunerasi->nomenklatur_jabatan}}</td>
                <td class="text-right">{{$remunerasi->prosentase_gaji}}</td>
                <td>{{$remunerasi->pengali}}</td>
                <td class="text-right">
                    @if($remunerasi->mata_uang_gaji_pokok) {{$remunerasi->mata_uang_gaji_pokok}} @endif {{number_format ($remunerasi->gaji_pokok, 0, ",", "." )}}
                    @if($remunerasi->gaji_pokok_remun_id)
                    <button type="button" class="btn btn-link cls-button-edit" data-id="{{$remunerasi->gaji_pokok_remun_id}}" data-toggle="tooltip" data-original-title="Ubah data Gaji Pokok {{$remunerasi->nomenklatur_jabatan}}"><i class="la la-edit" style="padding: 0px;"></i></button>
                    @endif
                </td>
                <td class="text-right">
                    @if($remunerasi->mata_uang_thp) {{$remunerasi->mata_uang_thp}} @endif {{number_format ($remunerasi->thp, 0, ",", "." )}}
                    @if($remunerasi->thp_remun_id)
                    <button type="button" class="btn btn-link cls-button-edit" data-id="{{$remunerasi->thp_remun_id}}" data-toggle="tooltip" data-original-title="Ubah data Gaji Pokok {{$remunerasi->nomenklatur_jabatan}}"><i class="la la-edit" style="padding: 0px;"></i></button>
                    @endif
                </td>
                <td class="text-right">
                    @if($remunerasi->mata_uang_tantiem) {{$remunerasi->mata_uang_tantiem}} @endif {{number_format ($remunerasi->tantiem, 0, ",", "." )}}
                    @if($remunerasi->tantiem_remun_id)
                    <button type="button" class="btn btn-link cls-button-edit" data-id="{{$remunerasi->tantiem_remun_id}}" data-toggle="tooltip" data-original-title="Ubah data Gaji Pokok {{$remunerasi->nomenklatur_jabatan}}"><i class="la la-edit" style="padding: 0px;"></i></button>
                    @endif
                </td>
                <!-- <td>
                  <div align="center">
                    <button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="{{$remunerasi->id}}" data-toggle="tooltip" data-original-title="Ubah data Remunerasi Dirkomwas {{$remunerasi->nomenklatur_jabatan}}"><i class="flaticon-edit"></i></button>
                    &nbsp;
                    <button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="{{$remunerasi->id}}" data-dirkomwas="{{$remunerasi->nomenklatur_jabatan}}" data-toggle="tooltip" data-original-title="Hapus data Remunerasi Dirkomwas {{$remunerasi->nomenklatur_jabatan}}"><i class="flaticon-delete"></i></button>
                  </div>
                </td> -->
            </tr>
            @php $no++; @endphp
            @else
                <tr class="treegrid-{{$remunerasi->id}} treegrid-parent-{{$remunerasi->parent_id}} item{{$remunerasi->id}}">
                    <td></td>
                    <td>{{$remunerasi->nomenklatur_jabatan}}</td>
                    <td class="text-right">{{$remunerasi->prosentase_gaji}}</td>
                    <td>{{$remunerasi->pengali}}</td>
                    <td class="text-right">
                        @if($remunerasi->mata_uang_gaji_pokok) {{$remunerasi->mata_uang_gaji_pokok}} @endif {{number_format ($remunerasi->gaji_pokok, 0, ",", "." )}}
                        @if($remunerasi->gaji_pokok_remun_id)
                        <button type="button" class="btn btn-link cls-button-edit" data-id="{{$remunerasi->gaji_pokok_remun_id}}" data-toggle="tooltip" data-original-title="Ubah data Gaji Pokok {{$remunerasi->nomenklatur_jabatan}}"><i class="la la-edit" style="padding: 0px;"></i></button>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($remunerasi->mata_uang_thp) {{$remunerasi->mata_uang_thp}} @endif {{number_format ($remunerasi->thp, 0, ",", "." )}}
                        @if($remunerasi->thp_remun_id)
                        <button type="button" class="btn btn-link cls-button-edit" data-id="{{$remunerasi->thp_remun_id}}" data-toggle="tooltip" data-original-title="Ubah data Gaji Pokok {{$remunerasi->nomenklatur_jabatan}}"><i class="la la-edit" style="padding: 0px;"></i></button>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($remunerasi->mata_uang_tantiem) {{$remunerasi->mata_uang_tantiem}} @endif {{number_format ($remunerasi->tantiem, 0, ",", "." )}}
                        @if($remunerasi->tantiem_remun_id)
                        <button type="button" class="btn btn-link cls-button-edit" data-id="{{$remunerasi->tantiem_remun_id}}" data-toggle="tooltip" data-original-title="Ubah data Gaji Pokok {{$remunerasi->nomenklatur_jabatan}}"><i class="la la-edit" style="padding: 0px;"></i></button>
                        @endif
                    </td>
                    <!-- <td>
                      <div align="center">
                        <button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="{{$remunerasi->id}}" data-toggle="tooltip" data-original-title="Ubah data Remunerasi Dirkomwas {{$remunerasi->nomenklatur_jabatan}}"><i class="flaticon-edit"></i></button>
                        &nbsp;
                        <button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="{{$remunerasi->id}}" data-dirkomwas="{{$remunerasi->nomenklatur_jabatan}}" data-toggle="tooltip" data-original-title="Hapus data Remunerasi Dirkomwas {{$remunerasi->nomenklatur_jabatan}}"><i class="flaticon-delete"></i></button>
                      </div>
                    </td> -->
                </tr>
            @endif
         @endforeach
        </tbody>
    </table>
</div>
@else
<div>
    <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text">Data Belum tersedia. Silahkan mengisikan di menu <a href="{{url('remunerasi/board/index')}}">Remunerasi Board</a> </div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
</div>
@endif