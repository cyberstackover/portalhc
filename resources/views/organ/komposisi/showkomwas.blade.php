<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="icon-settings font-dark"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Komisaris/Pengawas
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a data-perusahaanid="{{$perusahaan_id}}" type="button" class="btn btn-brand btn-elevate btn-icon-sm tambahHeading" style="height:34px" title="Tambah Heading">
                    <i class="fa fa-btn fa-plus"></i>
                    Tambah Heading Komwas
                    </a>
                    <a data-perusahaanid="{{$perusahaan_id}}" type="button" class="btn btn-danger deleteAllData" style="height:34px" title="Delete all">
                        <i class="fa fa-btn fa-trash"></i>
                        Delete all
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">

        {{-- start looping --}}
        @if (count($komwastabel) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover tree">
                <thead>
                    <tr>
                        <th style="width: 35px;">Jenis Jabatan</th>
                        <th style="width: 35px;">Nomenklatur</th>
                        <th style="width: 35px;">Bidang Jabatan</th>
                        <th style="width: 35px;">Prosentase Gaji</th>
                        <th style="width: 35px;">Aktif</th>
                        <th style="width: 20px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                 @foreach ($komwastabel as $akun)

                    @if($akun['parent_id'] == '')
                        <tr class="treegrid-{{$akun['id']}}" data-count="2">
                            <td class="nama{{$akun['id']}}">{{$akun['jabatan_nama']}}</td>
                            <td>{{$akun['nomenklatur_nama']}}</td>
                            <td>{{$akun['bidang_jabatan_nama']}}</td>
                            <td>{{$akun['prosentase_gaji']}}</td>
                            <td><input type="checkbox" name="aktif" id="aktif" class="make-switch" data-on-text="Aktif" data-off-text="Tidak" data-size="mini" {{$akun['aktif']}}></td>
                            <td class="action{{$akun['id']}}">
                                <a data-id="{{$akun["id"]}}" type="button" class="btn btn-success btn-xs updateData" title="Edit"><i class="fa fa-edit"></i></a>
                                <a data-id="{{$akun["id"]}}" type="button" class="btn btn-danger btn-xs deleteDataKomwas" title="Delete"><i class="fa fa-btn fa-trash"></i></a>
                                <a data-id="{{$akun["id"]}}" data-parentid="{{$akun["id"]}}" type="button" class="btn btn-warning btn-xs addAnak" title="Tambah Anak"><i class="fa fa-btn fa-plus"></i></a>
                            </td>
                        </tr>
                    @else
                        <tr class="treegrid-{{$akun['id']}} treegrid-parent-{{$akun['parent_id']}} item{{$akun['id']}}">
                            <td class="nama{{$akun['id']}}">{{$akun['jabatan_nama']}}</td>
                            <td>{{$akun['nomenklatur_nama']}}</td>
                            <td>{{$akun['bidang_jabatan_nama']}}</td>
                            <td>{{$akun['prosentase_gaji']}}</td>
                            <td><input type="checkbox" name="aktif" id="aktif" class="make-switch" data-on-text="Aktif" data-off-text="Tidak" data-size="mini" {{$akun['aktif']}}></td>
                            <td class="action{{$akun['id']}}">
                                <a data-id="{{$akun["id"]}}" data-parentid="{{$akun["parent_id"]}}" type="button" class="btn btn-success btn-xs updateData" title="Edit"><i class="fa fa-edit"></i></a>
                                <a data-id="{{$akun["id"]}}" type="button" class="btn btn-danger btn-xs deleteDataKomwas" title="Delete"><i class="fa fa-btn fa-trash"></i></a>
                                <a data-id="{{$akun["id"]}}" data-parentid="{{$akun["parent_id"]}}" type="button" class="btn btn-warning btn-xs addAnak" title="Tambah Anak"><i class="fa fa-btn fa-plus"></i></a>
                            </td>
                        </tr>
                    @endif

                 @endforeach
                </tbody>
            </table>
        </div>
        @endif
        {{-- end looping --}}
    </div>
</div>