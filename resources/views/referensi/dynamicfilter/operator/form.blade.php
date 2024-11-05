<form class="kt-form kt-form--label-right" method="POST" id="form-operator">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly"
        value="{{ $actionform == 'update' ? (int) $operator->id : null }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />
    <div class="kt-portlet__body">
        <div class="form-group row">
            <div class="col-lg-6">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" id="nama"
                    value="{{ !empty(old('nama')) ? old('nama') : ($actionform == 'update' && $operator->nama != '' ? $operator->nama : old('nama')) }}" />
            </div>
            <div class="col-lg-6">
                <label>Operator</label>
                <input type="text" class="form-control" name="operator" id="operator"
                    value="{{ !empty(old('operator')) ? old('operator') : ($actionform == 'update' && $operator->operator != '' ? $operator->operator : old('operator')) }}" />
            </div>
            <div class="col-lg-2 mt-2">
                <div class="form-group">
                    <label>Number</label>
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" name="is_number" class="is_number" @if ($actionform === 'update' && (bool) $operator->is_number) checked="checked" @endif>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <div class="form-group">
                    <label>Sorting</label>
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" name="is_sorting" class="is_sorting" @if ($actionform === 'update' && (bool) $operator->is_sorting) checked="checked" @endif>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <div class="form-group">
                    <label>Aktif</label>
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" name="aktif" class="aktif" @if ($actionform === 'update' && (bool) $operator->aktif) checked="checked" @endif>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-2">
                <label>Keterangan</label>
                <input type="text" class="form-control" name="keterangan" id="keterangan"
                    value="{{ !empty(old('nama')) ? old('keterangan') : ($actionform == 'update' && $operator->keterangan != '' ? $operator->keterangan : old('keterangan')) }}" />
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

<script type="text/javascript" src="{{ asset('js/referensi/dynamicfilter/operator/form.js') }}"></script>
