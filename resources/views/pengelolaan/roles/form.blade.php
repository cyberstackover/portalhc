<form class="kt-form kt-form--label-right" method="POST" id="form-role">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$role->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Role</label>
				<input type="text" class="form-control" name="name" id="name" value="{{!empty(old('name'))? old('name') : ($actionform == 'update' && $role->name != ''? $role->name : old('nama'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Permission</label>
                <select class="multi-select" id="permission" name="permission[]" multiple="multiple" >
                	@foreach($permission as $value)
                	        	
        	        	@php
                           $select = !empty(old('permission')) && in_array($value->id, old('permission'))? 'selected="selected"' : ($actionform == 'update' && in_array($value->id, $rolePermissions)? 'selected="selected"' : '')
                        @endphp	                            	        	
        	         <option value="{{ $value->id }}" {!! $select !!}>{{ $value->name }}</option>
                	        
                	@endforeach
                </select>  				
			</div>
			<div class="col-lg-6">
				<label>Akses Menu</label>
				<div id="checkTree"></div>
				<input type="hidden" name="menu" id="menu" readonly="readonly" />
			</div>
		</div>
		{{-- <div class="form-group row">
			<div class="col-lg-6">
				<label>Unit</label>
                <select class="multi-select" id="unit" name="unit[]" multiple="multiple" >
                	@foreach($units as $value)
                	        	
        	        	@php
                           $select = !empty(old('unit')) && in_array($value->id, old('unit'))? 'selected="selected"' : ($actionform == 'update' && in_array($value->id, $roleUnits)? 'selected="selected"' : '')
                        @endphp	                            	        	
        	         <option value="{{ $value->id }}" {!! $select !!}>{{ $value->nama }}</option>
                	        
                	@endforeach
                </select>  				
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

<script type="text/javascript" src="{{asset('js/pengelolaan/roles/form.js')}}"></script>