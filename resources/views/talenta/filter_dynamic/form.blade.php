<form class="kt-form kt-form--label-right" method="POST" id="form-parameter">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly"
        value="{{ $actionform == 'update' ? (int) $parameter->id : null }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />
    <div class="kt-portlet__body">
        <div class="form-group row">
            <div class="col-lg-6">
                <label>Nama Filter</label>
                <input type="text" class="form-control" name="submenu" id="submenu"
                    value="{{ !empty(old('submenu')) ? old('submenu') : ($actionform == 'update' && $parameter->submenu != '' ? $parameter->submenu : old('submenu')) }}" />
            </div>
            <div class="col-lg-5">
                <label>Tipe</label>
                <select class="form-control kt-select2" onchange="onChangetipe(this.value)" name="tipe">
                    <option value=""></option>
                    @foreach ($tipes as $tipe)
                        @php
                            $select = !empty(old('tipe')) && in_array($tipe, old('tipe')) ? 'selected="selected"' : ($actionform == 'update' && $tipe == $parameter->tipe ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $tipe }}" {!! $select !!}>{{ $tipe }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label>Number</label>
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" name="is_number" class="is_number" @if ($actionform === 'update' && (bool) $parameter->is_number) checked="checked" @endif>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-2">
                <label>Tabel Sumber</label>
                <select class="form-control kt-select2" name="dynamic_tabel_sumber_id">
                    <option value="">
                        pilih</option>
                    @foreach ($tabelsumbers as $value)

                        @php
                            $select = !empty(old('dynamic_tabel_sumber_id')) && in_array($value->id, old('dynamic_tabel_sumber_id')) ? 'selected="selected"' : ($actionform == 'update' && in_array($value['id'], [$parameter->dynamic_tabel_sumber_id]) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $value['id'] }}" {!! $select !!}>
                            {{ $value['tabel'] . ' - ' . $value['field'] }}</option>

                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 mt-2">
                <label>Standar Value</label>
                <select class="form-control kt-select2" name="dynamic_standar_value_id">
                    <option value="">
                        pilih</option>
                    @foreach ($standarvalues as $value)

                        @php
                            $select = !empty(old('dynamic_standar_value_id')) && in_array($value->id, old('dynamic_standar_value_id')) ? 'selected="selected"' : ($actionform == 'update' && in_array($value->id, [$parameter->dynamic_standar_value_id]) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $value->id }}" {!! $select !!}>
                            {{ $value->nama }} ({{ $value->opsi }})</option>

                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 mt-2">
                <label>Keterangan</label>
                <input type="text" class="form-control" name="keterangan" id="keterangan"
                    value="{{ !empty(old('nama')) ? old('keterangan') : ($actionform == 'update' && $parameter->keterangan != '' ? $parameter->keterangan : old('keterangan')) }}" />
            </div>
            <div class="col-lg-6 mt-2">
                <div class="form-group">
                    <label>Aktif</label>
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" name="aktif" class="aktif" @if ($actionform === 'update' && (bool) $parameter->aktif) checked="checked" @endif>
                            <span></span>
                        </label>
                    </div>
                </div>
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

<script type="text/javascript" src="{{ asset('js/talenta/filter_dynamic/form.js') }}"></script>
<script>
    function onChangetipe(tipe) {
        if (tipe == 'number') {
            $('.is_number').prop("checked", true);
            $('.is_number').attr("disabled", true);
        } else {
            $('.is_number').prop("checked", false);
            $('.is_number').attr("disabled", false);
        }
    }
</script>
