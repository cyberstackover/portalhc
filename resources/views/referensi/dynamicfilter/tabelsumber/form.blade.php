<form class="kt-form kt-form--label-right" method="POST" id="form-tabelsumber">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly"
        value="{{ $actionform == 'update' ? (int) $tabelsumber->id : null }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />
    <div class="kt-portlet__body">
        <div class="form-group row">
            <div class="col-lg-6">
                <label>Tabel</label>
                <input type="text" class="form-control" name="tabel" id="tabel"
                    value="{{ !empty(old('tabel')) ? old('tabel') : ($actionform == 'update' && $tabelsumber->tabel != '' ? $tabelsumber->tabel : old('tabel')) }}" />
            </div>
            <div class="col-lg-6">
                <label>Field</label>
                <input type="text" class="form-control" name="field" id="field"
                    value="{{ !empty(old('field')) ? old('field') : ($actionform == 'update' && $tabelsumber->field != '' ? $tabelsumber->field : old('field')) }}" />
            </div>
            <div class="col-lg-6 mt-2">
                <label>Alias</label>
                <input type="text" class="form-control" name="alias" id="alias"
                    value="{{ !empty(old('alias')) ? old('alias') : ($actionform == 'update' && $tabelsumber->alias != '' ? $tabelsumber->alias : old('alias')) }}" />
            </div>
            <div class="col-lg-6 mt-2">
                <label>Query</label>
                <input type="text" class="form-control" name="query" id="query"
                    value="{{ !empty(old('query')) ? old('query') : ($actionform == 'update' && $tabelsumber->query != '' ? $tabelsumber->query : old('query')) }}" />
            </div>
            <div class="col-lg-6 mt-2">
                <label>Keterangan</label>
                <input type="text" class="form-control" name="keterangan" id="keterangan"
                    value="{{ !empty(old('tabel')) ? old('keterangan') : ($actionform == 'update' && $tabelsumber->keterangan != '' ? $tabelsumber->keterangan : old('keterangan')) }}" />
            </div>
        </div>
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

<script type="text/javascript" src="{{ asset('js/referensi/dynamicfilter/tabelsumber/form.js') }}"></script>
