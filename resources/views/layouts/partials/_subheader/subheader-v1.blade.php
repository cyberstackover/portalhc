
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
	<div class="kt-container  kt-container--fluid ">
		<div class="kt-subheader__main">
			<h3 class="kt-subheader__title">
				{{$pagetitle}} </h3>
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
		<div class="kt-subheader__toolbar">
			<div class="kt-subheader__wrapper">
				<a href="#" class="btn kt-subheader__btn-daterange" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="" data-placement="left" data-original-title="Select dashboard daterange">
                    <span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Today:</span>&nbsp;
                    <span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date">Apr 17</span>
                    <i class="flaticon2-calendar-1"></i>
                </a>
			</div>
		</div>
	</div>
</div>

<!-- end:: Subheader -->