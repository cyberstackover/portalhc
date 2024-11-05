
<!-- begin:: Page -->

<!--[html-partial:include:{"file":"partials/_header/base-mobile.html"}]/-->
@include('layouts.partials._header.base-mobile')
<div class="kt-grid kt-grid--hor kt-grid--root">
	<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

		<!--[html-partial:include:{"file":"partials/_aside/base.html"}]/-->
		@include('layouts.partials._aside.base')
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

			<!--[html-partial:include:{"file":"partials/_header/base.html"}]/-->
			@include('layouts.partials._header.base')
			@include('layouts.partials._subheader.subheader-v1')
			<!-- begin:: Content Head -->
			{{-- <div class="kt-subheader  kt-grid__item" id="kt_subheader">
				<div class="kt-container  kt-container--fluid ">
					<div class="kt-subheader__main">
						<div class="kt-subheader__breadcrumbs">
							<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon-home"></i></a>
							@isset($breadcrumb)
							<span class="kt-subheader__breadcrumbs-separator"></span>
							   @foreach($breadcrumb as $row)
							        <a href="{{$row['url']}}" class="kt-subheader__breadcrumbs-link"> {{$row['menu']}} </a>
							        <span class="kt-subheader__breadcrumbs-separator"></span>
							   @endforeach     
							@endisset	
							<!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
						</div>
					</div>
				</div>
			</div> --}}
			<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

				<!--[html-partial:include:{"file":"partials/_subheader/subheader-v1.html"}]/-->

				<!--[html-partial:include:{"file":"partials/_content/base.html"}]/-->
				@include('layouts.partials._content.base')
			</div>

			<!--[html-partial:include:{"file":"partials/_footer/base.html"}]/-->
			@include('layouts.partials._footer.base')
		</div>
	</div>
</div>

<!-- end:: Page -->

<!--[html-partial:include:{"file":"partials/_scrolltop.html"}]/-->
@include('layouts.partials._scrolltop')

<!--[html-partial:include:{"file":"partials/_toolbar.html"}]/-->
@include('layouts.partials._toolbar')

<!--[html-partial:include:{"file":"partials/_demo-panel.html"}]/-->
@include('layouts.partials._demo-panel')

<!--[html-partial:include:{"file":"partials/_chat.html"}]/-->
@include('layouts.partials._chat')