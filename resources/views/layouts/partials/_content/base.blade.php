
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	@include('flash-message')

	<div class="row cls-content-data">
		@yield('content')

		<div class="modal fade draggable-modal" id="winform" tabindex="-1" role="dialog" data-backdrop="static" data-animation="blur" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content kt-portlet">
					<div class="modal-header">
						<h5 class="modal-title"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						</button>
					</div>
					<div class="modal-body">

					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<!-- end:: Content -->