<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="icon-settings font-dark"></i>
            </span>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a data-perusahaanid="{{$perusahaan_id}}" href="javascript:;" type="button" class="btn btn-brand btn-elevate btn-icon-sm tambahHeading" style="height:34px" title="Tambah Heading">
                        <i class="fa fa-btn fa-plus"></i>
                        Tambah Grup Jabatan
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
        @if (count($komposisitabel) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover tree">
                <thead>
                    <tr>
                        <th style="width: 60px;">Nomenklatur</th>
                        <th style="width: 40px;">Jenis Jabatan</th>
                        <th style="width: 40px;">Bidang Jabatan</th>
                        <th style="width: 5px;">Aktif</th>
                        <th style="width: 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                 @foreach ($komposisitabel as $akun)

                    @if($akun['parent_id'] == 0)
                        <tr class="treegrid-{{$akun['id']}}" data-count="2">
                            <td>{{$akun['nomenklatur_nama']}}</td>
                            <td class="nama{{$akun['id']}}">{{$akun['jabatan_nama']}}</td>
                            <td>
                            @if($akun['bidang_jabatan_nama'] === '<ul><li>&nbsp;</li></ul>')
                                &nbsp;
                            @else
                                {!! $akun['bidang_jabatan_nama'] !!}
                            @endif
                            
                            </td>
                            <td><input type="checkbox" name="aktif" id="aktif" class="make-switch" data-on-text="Aktif" data-off-text="Tidak" data-size="mini" onchange="submitAktif('{{$akun["id"]}}', this.checked);" {{$akun['aktif']}}></td>
                            <td class="action{{$akun['id']}}">
                                <a data-perusahaanid="{{$perusahaan_id}}" data-id="{{$akun["id"]}}" data-parentid="{{$akun["id"]}}" type="button" class="btn btn-xs deleteDataDireksi" title="Delete"><i class="fa fa-btn fa-trash"></i></a>
                                <a data-perusahaanid="{{$perusahaan_id}}" data-id="{{$akun["id"]}}" data-parentid="{{$akun["id"]}}" type="button" class="btn btn-outline-hover-danger btn-sm btn-icon btn-circle addAnak" title="Tambah Anak"><i class="fa fa-btn fa-plus"></i></a>
                            </td>
                        </tr>
                    @else
                        <tr class="treegrid-{{$akun['id']}} treegrid-parent-{{$akun['parent_id']}} item{{$akun['id']}}">
                            <td>{{$akun['nomenklatur_nama']}}</td>
                            <td class="nama{{$akun['id']}}">{{$akun['jabatan_nama']}}</td>
                            <td>
                            @if($akun['bidang_jabatan_nama'] === '<ul><li>&nbsp;</li></ul>')
                                &nbsp;
                            @else
                                {!! $akun['bidang_jabatan_nama'] !!}
                            @endif
                            
                            </td>
                            <td><input type="checkbox" name="aktif" id="aktif" class="make-switch" data-on-text="Aktif" data-off-text="Tidak" data-size="mini" onchange="submitAktif('{{$akun["id"]}}', this.checked);" {{$akun['aktif']}}></td>
                            <td class="action{{$akun['id']}}">
                                <a data-perusahaanid="{{$perusahaan_id}}" data-id="{{$akun["id"]}}" data-parentid="{{$akun["parent_id"]}}" type="button" class="btn btn-outline-hover-danger btn-sm btn-icon btn-circle updateDataAnak" title="Edit"><i class="fa fa-edit"></i></a>
                                <a data-perusahaanid="{{$perusahaan_id}}" data-id="{{$akun["id"]}}" type="button" class="btn btn-outline-hover-danger btn-sm btn-icon btn-circle deleteDataDireksi" title="Delete"><i class="fa fa-btn fa-trash"></i></a>
                                
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