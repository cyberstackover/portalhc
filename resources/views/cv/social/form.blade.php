<form action="{{route('cv.social.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Sosial Media</label>
					<select class="form-control kt-select2" name="id_social_media">
	                    <option value=""></option>
	                    @foreach($jenissocials as $sosials)       
	                    	@php
		            	      $select = ($actionform == 'update' && ($sosials->id == $data->socialMedia->id) ? 'selected="selected"' : '')
		            	    @endphp
		                	<option value="{{ $sosials->id }}" {!! $select !!}>{{ $sosials->nama }}</option>
	                    @endforeach
	                </select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>AKun/Nama Sosial Media</label>
					<input type="text" name="name_social_media" class="form-control" id="name_social_media" required="required">
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
<script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('js/cv/penghargaan/form-penghargaan.js')}}"></script>
<script type="text/javascript">
</script>