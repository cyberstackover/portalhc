<form class="kt-form kt-form--label-right" method="POST" enctype="multipart/form-data" id="form-import" action="/keterangan/import_excel" >
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="insert" />
	<div class="kt-portlet__body">
	    <div class="form-group row">
	        <div class="col-lg-12">
				<div class="form-group">
					<label>File Import</label>
					<input type="file" name="file_name" class="form-control" id="file_name">
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
<script type="text/javascript">
</script>
{{-- <script type="text/javascript" src="{{asset('js/cv/board/import.js')}}"></script> --}}
