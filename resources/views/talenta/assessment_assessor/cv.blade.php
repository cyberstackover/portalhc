<style>
    .foto-talenta {
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 5px;
      height: 265px;
    }
  </style>
<div class="kt-portlet__body">
    <div class="form-group row">
        <div class="col-lg-5">
            <label>
                <img class="foto-talenta"
                    src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'L' ? 'img/male.png': 'img/female.png')) }}"
                    alt="">
            </label>
        </div>
        <div class="col-lg-7">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nama_lengkap}}"
                    disabled />
            </div>
            <div class="form-group">
                <label>Tempat, Tanggal Lahir</label>
                <input type="text" class="form-control" name="nama_lengkap" id="nama"
                    value="{{$talenta->tempat_lahir}}, {{ ($talenta->tanggal_lahir != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $talenta->tanggal_lahir)->format('d/m/Y') : '') }}"
                    disabled />
            </div>
            <div class="form-group">
                <label>Pendidikan Terakhir</label>
                <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{ $talenta->pendidikan }}"
                    disabled />
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <div class="form-group">
                <label>Jabatan Saat ini</label>
                <input type="text" class="form-control" name="nama_lengkap" id="nama"
                    value="{{$talenta->jabatan }} - {{$talenta->nama_perusahaan}}" disabled />
            </div>
        </div>
        <div class="col-lg-12">
            <hr>
        </div>
    </div>
</div>