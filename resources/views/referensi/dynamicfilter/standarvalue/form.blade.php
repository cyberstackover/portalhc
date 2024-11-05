<form class="kt-form kt-form--label-right" method="POST" id="form-standarvalue">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly"
        value="{{ $actionform == 'update' ? (int) $standarvalue->id : null }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />
    <div class="kt-portlet__body">
        <div class="form-group row">
            <div class="col-lg-6">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" id="nama"
                    value="{{ !empty(old('nama')) ? old('nama') : ($actionform == 'update' && $standarvalue->nama != '' ? $standarvalue->nama : old('nama')) }}" />
            </div>
            <div class="col-lg-6">
                <label>Opsi</label>
                <input type="text" class="form-control" name="opsi" id="opsi"
                    value="{{ !empty(old('opsi')) ? old('opsi') : ($actionform == 'update' && $standarvalue->opsi != '' ? $standarvalue->opsi : old('opsi')) }}"
                    placeholder="value1|value2" />
                <span class="form-text text-muted">Untuk menambahkan opsi gunakan delimeter "|". Contoh:
                    value1|value2|value3|dst..</span>
            </div>
        </div>
        {{-- <div class="form-group row">
            <div class="col-lg-6">
                <label>Daftar Referensi Opsi</label>
            </div>
        </div> --}}
        
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript" src="{{ asset('js/referensi/dynamicfilter/standarvalue/form.js') }}"></script>
