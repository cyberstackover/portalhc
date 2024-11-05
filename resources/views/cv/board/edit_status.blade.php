<form class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<div class="kt-portlet__body">
	    <div class="form-group row">            
	        <div class="col-lg-6">        	
                <label>Nama Talenta</label>
                  <input type="text" class="form-control" value="{{$talenta->nama_lengkap}}" disabled/>
	        </div>        
	        <div class="col-lg-6">        	
                <label>Status Talenta</label>
                  <select class="form-control kt-select2" id="id_status_talenta" name="id_status_talenta">
	                  <option value=""></option>
	                  @foreach($status_talenta as $data)
	                  @php
	                  $select = !empty(old('id_status_talenta')) && in_array($data->id, old('id_status_talenta'))? 'selected="selected"' : (($data->id==$talenta->id_status_talenta)? 'selected="selected"' : '')
	               @endphp
	                <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
	                  @endforeach
	              </select>
                  <input type="hidden" name="id" value="{{$id}}"/>
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
<script type="text/javascript">
  $('#id_status_talenta').select2({
      placeholder: "Pilih",
      allowClear: true
  });
</script>
<script type="text/javascript" src="{{asset('js/cv/board/form-editstatus.js')}}"></script>
