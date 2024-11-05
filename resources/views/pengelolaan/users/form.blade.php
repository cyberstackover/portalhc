<form class="kt-form kt-form--label-right" method="POST" id="form-user">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$user->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-4">
				<label>Kategori User</label>
				<select class="form-control cls-kategori-user" name="kategori_user_id" id="kategori_user_id">
					<option value="">[ - Pilih Kategori User - ]</option>
					@foreach($kategoriuser as $row)
					      <option value="{{$row->id}}" data-ad="{{$row->is_ad}}" data-inputan="{{$row->pilihan_inputan}}">{{$row->kategori}}</option>
					@endforeach
				</select>
				<input type="hidden" id="kategori" readonly="readonly" value="{{!empty(old('kategori'))? old('kategori') : ($actionform == 'update' && $kategori != ''? $kategori : old('kategori'))}}" />
			</div>
			<div class="col-lg-4">
				<label>Username</label>
				<input type="text" class="form-control cls-username" name="username" id="username" value="{{!empty(old('username'))? old('username') : ($actionform == 'update' && $user->username != ''? $user->username : old('username'))}}" />
			</div>
			<div class="col-lg-3">
				<label>Check User</label>
				<button type="button" class="btn btn-outline-info cls-check-user"><i class="flaticon-user-ok"></i> Check User</button>
			</div>
			<div class="col-lg-1">
				<label>Status</label>
				<div class="kt-checkbox-list">
					<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
						<input type="checkbox" name="activated" id="activated" @if($actionform === 'update' && (bool)$user->activated) checked="checked" @endif>
						<span></span>
					</label>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama</label>
				<input type="text" class="form-control" name="name" id="name" value="{{!empty(old('name'))? old('name') : ($actionform == 'update' && $user->name != ''? $user->name : old('nama'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Email</label>
				<input type="text" class="form-control" name="email" id="email" value="{{!empty(old('email'))? old('email') : ($actionform == 'update' && $user->email != ''? $user->email : old('email'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6 divasalinstansi" style="display: none;">
				<label>Asal Instansi</label>
				<div class="kt-input-icon">
					<input type="text" class="form-control" autocomplete="off" name="asal_instansi" id="asal_instansi" value="{{!empty(old('asal_instansi'))? old('asal_instansi') : ($actionform == 'update' && $user->asal_instansi != ''? $user->asal_instansi : old('asal_instansi'))}}" />
					<span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="flaticon-buildings"></i></span></span>
				</div>
			</div>				
			<div class="col-lg-6 divbumn" style="display: none;">
				<label>BUMN</label>
				<select class="form-control cls-bumn" name="id_bumn" id="id_bumn"></select>
				<input type="hidden" readonly="readonly" id="bumnhidden" value="{{!empty(old('bumnhidden'))? old('bumnhidden') : ($actionform == 'update' && $bumnhidden != ''? $bumnhidden : old('bumnhidden'))}}" />
			</div>
			<div class="col-lg-6 divassessment" style="display: none;">
				<label>Lembaga Assessment</label>
				<select class="form-control cls-asses" name="id_assessment" id="id_assessment"></select>
				<input type="hidden" readonly="readonly" id="asseshidden" value="{{!empty(old('asseshidden'))? old('asseshidden') : ($actionform == 'update' && $asseshidden != ''? $asseshidden : old('asseshidden'))}}" />
			</div>				
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label for="exampleSelect2">Roles</label>
				<select class="form-control kt-select2" multiple="" name="roles[]" class="form-control" id="roles">
					@foreach($roles as $value)
					    <option value="{{$value->id}}" {{$actionform == 'update' && in_array($value->name, $userRole) ? 'selected' : ''}}>{{$value->name}}</option>
		            @endforeach
				</select>
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

<script type="text/javascript" src="{{asset('js/pengelolaan/users/form.js')}}"></script>