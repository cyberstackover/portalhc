<form class="kt-form kt-form--label-right" method="POST" id="form-user">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$user->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-10">
				<label>Pejabat</label>
				<select class="form-control kt-select2" name="id_talenta" id="id_talenta" onchange=" return onNamaPejabat(this.value)">
                    <option value=""></option>
                    @foreach($pejabats as $pejabat)
                    @php
            	      $select = !empty(old('id_talenta')) && in_array($pejabat->id, old('id_talenta'))? 'selected="selected"' : ($actionform == 'update' && ($pejabat->id==$skhenti->id_talenta)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $pejabat->id }}" {!! $select !!}>{{ $pejabat->nama }}</option>
                    @endforeach
                </select>
				<input type="hidden" id="kategori" readonly="readonly" value="{{!empty(old('kategori'))? old('kategori') : ($actionform == 'update' && $kategori != ''? $kategori : old('kategori'))}}" />
			</div>
			
		</div>
		<div class="form-group row">
			<div class="col-lg-7">
				<label>username (email)</label>
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
			@if($kategori_user == 1)			
			<div class="col-lg-6 divbumn" >
				<label>BUMN</label>
				<select class="form-control cls-bumn" name="id_bumn" id="id_bumn"></select>
				<input type="hidden" readonly="readonly" id="bumnhidden" value="{{!empty(old('bumnhidden'))? old('bumnhidden') : ($actionform == 'update' && $bumnhidden != ''? $bumnhidden : old('bumnhidden'))}}" />
			</div>
			@endif				
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

<script type="text/javascript" src="{{asset('js/pengelolaan/pejabats/form.js')}}"></script>