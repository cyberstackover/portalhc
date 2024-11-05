<form class="kt-form kt-form--label-right" method="POST" id="form-user_guides">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="insert" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>File</label>
				<input type="file" class="form-control" name="filename" id="filename"/>
			</div>
		</div>	
	</div>
	<div class="kt-portlet__foot">
		<div class="kt-form__actions">
			<div class="row">
				<div class="col-lg-6">
					<button type="submit" class="btn btn-primary">Upload</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript" src="{{asset('js/pengelolaan/user_guides/form.js')}}"></script>