<form class="kt-form kt-form--label-right" method="POST" id="form-menu">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama Menu</label>
				<div class="kt-input-icon">
					<input type="text" class="form-control" name="label" id="label" value="{{!empty(old('label'))? old('label') : ($actionform == 'update' && $data->label != ''? $data->label : old('label'))}}" />
					<span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="flaticon2-notepad"></i></span></span>
				</div>
			</div>
			<div class="col-lg-4">
				<label>Parent Menu</label>
				<select class="form-control cls-select2" name="parent_id" id="parent_id"></select>
				<input type="hidden" id="parent_id_hidden" readonly="readonly" value="{{!empty(old('parent'))? old('parent') : ($actionform == 'update' && $parent != ''? $parent : old('parent'))}}" />
			</div>				
			<div class="col-lg-8">
				<label>Status</label>
				<div class="kt-checkbox-list">
					<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
						<input type="checkbox" name="status" id="status" @if($actionform === 'update' && (bool)$data->status) checked="checked" @endif>
						<span></span>
					</label>
				</div>
			</div>			
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Routing Menu</label>
				<div class="kt-input-icon">
					<input type="text" class="form-control cls-routing" name="route_name" id="route_name" value="{{!empty(old('route_name'))? old('route_name') : ($actionform == 'update' && $data->route_name != ''? $data->route_name : old('route_name'))}}" />
					<span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="flaticon2-browser-2"></i></span></span>
				</div>
				<span class="form-text text-muted">Contoh : modules.master.wilayah.index (*sudah ada di web.php) / isikan '#' jika tidak ada URL</span>
			</div>
			<div class="col-lg-3">
				<label>Icon</label>
				<div class="kt-input-icon">
					<input type="text" class="form-control" name="icon" id="icon" value="{{!empty(old('icon'))? old('icon') : ($actionform == 'update' && $data->icon != ''? $data->icon : old('icon'))}}" />
					<span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="flaticon-menu-2"></i></span></span>
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

<script type="text/javascript" src="{{asset('js/pengelolaan/menus/form.js')}}"></script>