<!DOCTYPE html>

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<!-- begin::Head -->
	<head>
		<base href="">
		<meta charset="utf-8" />
		<title>HC Kementerian Badan Usaha Milik Negara</title>
		<meta name="description" content="Updates and statistics">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->

		<!--end::Page Vendors Styles -->

		@yield('addbeforecss')
		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="{{ asset('/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="{{ asset('/assets/css/skins/header/base/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/css/skins/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/css/skins/brand/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/assets/css/skins/aside/light.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Layout Skins -->
		@yield('addaftercss')
		<link href="{{ asset('/assets/favicon.ico') }}" rel="shortcut icon" />
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-page--loading-enabled kt-page--loading kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!--[html-partial:include:{"file":"partials/_page-loader.html"}]/-->
		@include('layouts.partials._page-loader')

		<!--[html-partial:include:{"file":"layout.html"}]/-->
		@include('layouts.layout')
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

        <div class="modal fade draggable-modal" id="winformorang" tabindex="-1" role="dialog" data-backdrop="static" data-animation="blur" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": [
							"#c5cbe3",
							"#a1a8c3",
							"#3d4465",
							"#3e4466"
						],
						"shape": [
							"#f0f3ff",
							"#d9dffa",
							"#afb4d4",
							"#646c9a"
						]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="{{ asset('/assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('/assets/js/scripts.bundle.js') }}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
		{{-- <script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
		<script src="{{ asset('/assets/plugins/custom/gmaps/gmaps.js') }}" type="text/javascript"></script> --}}

		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<script src="{{ asset('/assets/js/pages/dashboard.js') }}" type="text/javascript"></script>

		<!--end::Page Scripts -->
		@yield('script')
        <script type="text/javascript" src="{{asset('js/generalfunction.js')}}"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });             
        </script>
        @yield('addafterjs')
	</body>

	<!-- end::Body -->
</html>