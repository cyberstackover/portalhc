@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/nestablelist/jquery.nestable.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon-tabs"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				{{$pagetitle}}
			</h3>
		</div>
		<div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-wrapper">
				<div class="kt-portlet__head-actions">
					<a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
						<i class="la la-plus"></i>
						Tambah data
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="kt-portlet__body">

		<div class="dd" id="nestable_list_3"></div>

	</div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
  	var urlgettreemenu = "{{route('pengelolaan.menus.gettreemenu')}}";
  	var urlcreate = "{{route('pengelolaan.menus.create')}}";
  	var urledit = "{{route('pengelolaan.menus.edit')}}";
  	var urlstore = "{{route('pengelolaan.menus.store')}}";
  	var urldelete = "{{route('pengelolaan.menus.delete')}}";
  	var urlfetchparentmenu = "{{route('pengelolaan.general.fetchparentmenu')}}";
  	var urlsubmitchangestructure = "{{route('pengelolaan.menus.submitchangestructure')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/nestablelist/jquery.nestable.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/pengelolaan/menus/index.js')}}"></script>
@endsection