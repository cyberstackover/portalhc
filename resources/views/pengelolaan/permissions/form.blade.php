<form class="kt-form kt-form--label-right" method="POST" id="form-permission">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$permission->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Permission</label>
				<input type="text" class="form-control" name="name" id="name" value="{{!empty(old('name'))? old('name') : ($actionform == 'update' && $permission->name != ''? $permission->name : old('nama'))}}" />
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

<script type="text/javascript" src="{{asset('js/pengelolaan/permissions/form.js')}}"></script>