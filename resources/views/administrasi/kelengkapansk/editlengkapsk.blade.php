@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection

<!-- Konten -->
@section('content')
<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				{{$talenta->nama_lengkap}} <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		<div class="container py-3 my-3 border rounded shadow-sm">
			<div class="progress" style="height: 20px;">
			    <div class="progress-bar progress-bar-striped progress-bar-animated " role="progressbar" style="width: {{$talenta->prosentase}}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">{{$talenta->prosentase}}%</div>
			</div>
			<br>
		    <div class="row">
		        <div class="col-12">
		            <ul class="nav nav-tabs flex-nowrap" role="tablist">

		            	<li role="presentation" class="nav-item">
		                    <a href="#kt_portlet_base_demo_1_1_tab_content_1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab" title="Data Asal Instansi"> Data Asal Instansi </a>
		                </li>
		                <li role="presentation" class="nav-item">
		                    <a href="#kt_portlet_base_demo_1_2_tab_content_2" class="nav-link disabled" data-toggle="tab" aria-controls="step2" role="tab" title="Data Assesmen"> Data Assesmen </a>
		                </li>
		                <li role="presentation" class="nav-item">
		                    <a href="#kt_portlet_base_demo_1_3_tab_content_3" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab" title="Data Penghasilan"> Data Penghasilan </a>
		                </li>
		                <li role="presentation" class="nav-item">
		                    <a href="#kt_portlet_base_demo_1_4_tab_content_4" class="nav-link disabled" data-toggle="tab" aria-controls="step4" role="tab" title="Upload File/Berkas Kelengkapan"> Upload File/Berkas Kelengkapan </a>
		                </li>
		                <li role="presentation" class="nav-item">
		                    <a href="#summary" class="nav-link disabled" data-toggle="tab" aria-controls="summary" role="tab" title="Summary"> Summary Kelengkapan SK </a>
		                </li>
		            </ul>
		            <form role="form">
		            	<input type="hidden" name="id_talenta" id="id_talenta" readonly="readonly" value="{{$talenta->id}}" />
		            	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$id_perusahaan}}" />
		            	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$id_grup_jabatan}}" />
		            	<input type="hidden" name="base_url" id="base_url" readonly="readonly" value="{{$base_url}}" />
		            	<input type="hidden" name="id_struktur_organ" id="id_struktur_organ" readonly="readonly" value="{{$id_struktur_organ}}" />
		                <div class="tab-content py-2">
		                	
		                	<!-- Instansi -->
		                    <div class="tab-pane active" role="tabpanel" id="kt_portlet_base_demo_1_1_tab_content_1">


		                    	<ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/kelengkapansk/index" class="btn btn-primary">
											<span><<</span> Kembali
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                    	<br>
		                    	<br>
		                    	<br>
		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Asal Instansi</span>
										</h3>
									</div>
									<div class="kt-portlet__head-toolbar">
										<div class="dropdown dropdown-inline">
											<a href="#" class="btn btn-default btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="flaticon-add"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right" style="">
												<ul class="kt-nav">
													<li class="kt-nav__item">
														<a href="#" class="kt-nav__link cls-add-instansi">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Asal Instansi</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
							            
							        </div>
								</div>



								<div class="kt-portlet__body">
									<!--begin: Datatable -->
									<div class="table-responsive">
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-instansi">
								            <thead>
								                <tr>
								                    <th width="5%"><div align="center">No.</div></th>
								                    <th width="30%"><div align="center">Pejabat</div></th>
								                    <th width="20%"><div align="center">Instansi</div></th>
								                    <th width="20%"><div align="center">Asal Instansi</div></th>
								                    <th width="20%"><div align="center">Jabatan Asal Instansi</div></th>
								                    <th width="15%"><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        <!--end: Datatable -->
								</div>
		                        
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/kelengkapansk/index" class="btn btn-primary">
											<span><<</span> Kembali
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        
		                    </div>
		                    <!-- end instansi -->

		                    <!-- Assesmen -->
		                    <div class="tab-pane" role="tabpanel" id="kt_portlet_base_demo_1_2_tab_content_2">


		                    	<ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/kelengkapansk/index" class="btn btn-primary">
											<span><<</span> Kembali
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Assesmen</span>
										</h3>
									</div>
									<div class="kt-portlet__head-toolbar">
										<div class="dropdown dropdown-inline">
											<a href="#" class="btn btn-default btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="flaticon-add"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right" style="">
												<ul class="kt-nav">
													<li class="kt-nav__item">
														<a href="#" class="kt-nav__link cls-add-assesmen">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Assesmen</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
							            
							        </div>
								</div>
								<div class="kt-portlet__body">
									<!--begin: Datatable -->
									<div class="table-responsive">
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-assesmen">
								            <thead>
								                <tr>
								                    <th width="5%"><div align="center">No.</div></th>
								                    <th width="15%"><div align="center">Pejabat</div></th>
								                    <th width="5%"><div align="center">Nilai Domestik</div></th>
								                    <th width="10%"><div align="center">Nilai Global</div></th>
								                    <th width="10%"><div align="center">Penilaian (Khusus Dekom/Dewas)</div></th>
								                    <th width="15%"><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        <!--end: Datatable -->
								</div>
		                        
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/kelengkapansk/index" class="btn btn-primary">
											<span><<</span> Kembali
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        
		                    </div>
		                    <!-- end assesmen -->

		                    <!-- Penghasilan -->
		                    <div class="tab-pane" role="tabpanel" id="kt_portlet_base_demo_1_3_tab_content_3">

		                    	<ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/kelengkapansk/index" class="btn btn-primary">
											<span><<</span> Kembali
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Penghasilan</span>
										</h3>
									</div>
									<div class="kt-portlet__head-toolbar">
										<div class="dropdown dropdown-inline">
											<a href="#" class="btn btn-default btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="flaticon-add"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right" style="">
												<ul class="kt-nav">
													<li class="kt-nav__item">
														<a href="#" class="kt-nav__link cls-add-penghasilan">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Penghasilan</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
							            
							        </div>
								</div>
								<div class="kt-portlet__body">
									<!--begin: Datatable -->
									<div class="table-responsive">
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-penghasilan">
								            <thead>
								                <tr>
								                    <th width="5%"><div align="center">No.</div></th>
								                    <th width="15%"><div align="center">Pejabat</div></th>
								                    <th width="5%"><div align="center">Tahun</div></th>
								                    <th width="10%"><div align="center">Gaji Pokok</div></th>
								                    <th width="10%"><div align="center">Tantiem</div></th>
								                    <th width="10%"><div align="center">Tunjangan</div></th>
								                    <th width="10%"><div align="center">Take Home Pay</div></th>
								                    <th width="15%"><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        <!--end: Datatable -->
								</div>
		                        
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/kelengkapansk/index" class="btn btn-primary">
											<span><<</span> Kembali
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        
		                    </div>
		                    <!-- end Penghasilan -->

		                    <!-- Penghasilan -->
		                    <div class="tab-pane" role="tabpanel" id="kt_portlet_base_demo_1_4_tab_content_4">

		                    	<ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/kelengkapansk/index" class="btn btn-primary">
											<span><<</span> Kembali
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Kelengkapan FILE</span>
										</h3>
									</div>
									<div class="kt-portlet__head-toolbar">
										<div class="dropdown dropdown-inline">
											<a href="#" class="btn btn-default btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="flaticon-add"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right" style="">
												<ul class="kt-nav">
													<li class="kt-nav__item">
														<a href="#" class="kt-nav__link cls-add-kelengkapan">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Kelengkapan File</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
							            
							        </div>
								</div>
								<div class="kt-portlet__body">
									<!--begin: Datatable -->
									<div class="table-responsive">
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-kelengkapan">
								            <thead>
								                <tr>
								                    <th width="5%"><div align="center">No.</div></th>
								                    <th width="15%"><div align="center">Pejabat</div></th>
								                    <th width="5%"><div align="center">Jenis File</div></th>
								                    <th width="10%"><div align="center">Nama File</div></th>
								                    <th width="15%"><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        <!--end: Datatable -->
								</div>
		                        
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/kelengkapansk/index" class="btn btn-primary">
											<span><<</span> Kembali
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        
		                    </div>
		                    <!-- end Kelengkapan -->
		                    
		                    <div class="tab-pane" role="tabpanel" id="summary">


		                    	<ul class="float-right">
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-brand save-tambah2">Submit</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <span class="kt-margin-left-10">or <a href="/administrasi/kelengkapansk/index" class="kt-link kt-font-bold">Cancel</a></span>
		                            </li>


		                        </ul>
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Nama Talent</span>
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					
					<h5>Instansi</h5>
					<div class="table-responsive">
						<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-instansi">
				            <thead>
				                <tr>
				                    <th><div align="center">No.</div></th>
				                    <th><div align="center">Pejabat</div></th>
				                    <th><div align="center">Instansi</div></th>
				                    <th><div align="center">Asal Instansi</div></th>
				                    <th><div align="center">Jabatan Asal Instansi</div></th>
				                </tr>
				            </thead>
				            <tbody></tbody>
				        </table>
					</div>
			        
			        <!--end: Datatable -->
			        <br>

			        <h5>Assesmen</h5>
					<div class="table-responsive">
						<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-assesmen">
				            <thead>
				                <tr>
				                    <th><div align="center">No.</div></th>
				                    <th><div align="center">Pejabat</div></th>
				                    <th><div align="center">Nilai Domestik</div></th>
				                    <th><div align="center">Nilai Global</div></th>
				                    <th><div align="center">Penilaian (Khusus Dekom/Dewas)</div></th>
				                </tr>
				            </thead>
				            <tbody></tbody>
				        </table>
					</div>
			        
			        <!--end: Datatable -->

			        <h5>Penghasilan</h5>
					<div class="table-responsive">
						<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-penghasilan">
				            <thead>
				                <tr>
				                    <th ><div align="center">No.</div></th>
				                    <th ><div align="center">Pejabat</div></th>
				                    <th ><div align="center">Tahun</div></th>
				                    <th ><div align="center">Gaji Pokok</div></th>
				                    <th ><div align="center">Tantiem</div></th>
				                    <th ><div align="center">Tunjangan</div></th>
				                    <th ><div align="center">Take Home Pay</div></th>
				                </tr>
				            </thead>
				            <tbody></tbody>
				        </table>
					</div>
			        
			        <!--end: Datatable -->
			        <br>

			        <!--end: Datatable -->

			        <h5>Kelengkapan File</h5>
					<div class="table-responsive">
						<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-kelengkapan">
				            <thead>
				                <tr>
				                    <th><div align="center">No.</div></th>
				                    <th><div align="center">Jabatan</div></th>
				                    <th><div align="center">Jenis File</div></th>
				                    <th><div align="center">Nama File</div></th>
				                </tr>
				            </thead>
				            <tbody></tbody>
				        </table>
					</div>
			        
			        <!--end: Datatable -->
				        
			    </div>
			    <div class="kt-portlet__foot">
					<div class="row align-items-center">
						<div class="col-lg-6 m--valign-middle">
							Pesan Konfirmasi: Tahap Edit Kelengkapan Sudah Selesai, Apakah anda yakin untuk simpan perubahan data tersebut?
						</div>
						<div class="col-lg-6 kt-align-right">
							<button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
							<button type="button" class="btn btn-brand save-tambah2">Submit</button>
							<span class="kt-margin-left-10">or <a href="/administrasi/kelengkapansk/index" class="kt-link kt-font-bold">Cancel</a></span>
						</div>
					</div>
				</div>
		                    </div>
		                    <div class="clearfix"></div>
		                </div>
		            </form>
		        </div>
		    </div>
		</div>
	</div>
</div>


@endsection

@section('addafterjs')
<script type="text/javascript">
	//instansi
    var urlcreateinstansi = "{{route('administrasi.kelengkapansk.createinstansi')}}";
    var urleditinstansi = "{{route('administrasi.kelengkapansk.editinstansi')}}";
    var urlstoreinstansi = "{{route('administrasi.kelengkapansk.storeinstansi')}}";
    var urldeleteinstansi = "{{route('administrasi.kelengkapansk.deleteinstansi')}}";
    var urldatatableinstansi = "{{route('administrasi.kelengkapansk.datatableinstansi')}}";
    var urldatatablesuminstansi = "{{route('administrasi.kelengkapansk.datatablesuminstansi')}}";

    //assesmen
    var urlcreateassesmen = "{{route('administrasi.kelengkapansk.createassesmen')}}";
    var urleditassesmen = "{{route('administrasi.kelengkapansk.editassesmen')}}";
    var urlstoreassesmen = "{{route('administrasi.kelengkapansk.storeassesmen')}}";
    var urldeleteassesmen = "{{route('administrasi.kelengkapansk.deleteassesmen')}}";
    var urldatatableassesmen = "{{route('administrasi.kelengkapansk.datatableassesmen')}}";
    var urldatatablesumassesmen = "{{route('administrasi.kelengkapansk.datatablesumassesmen')}}";

    //assesmen
    var urlcreatepenghasilan = "{{route('administrasi.kelengkapansk.createpenghasilan')}}";
    var urleditpenghasilan = "{{route('administrasi.kelengkapansk.editpenghasilan')}}";
    var urlstorepenghasilan = "{{route('administrasi.kelengkapansk.storepenghasilan')}}";
    var urldeletepenghasilan = "{{route('administrasi.kelengkapansk.deletepenghasilan')}}";
    var urldatatablepenghasilan = "{{route('administrasi.kelengkapansk.datatablepenghasilan')}}";
    var urldatatablesumpenghasilan = "{{route('administrasi.kelengkapansk.datatablesumpenghasilan')}}";

    //kelengkapan
    var urlcreatekelengkapan = "{{route('administrasi.kelengkapansk.createkelengkapan')}}";
    var urleditkelengkapan = "{{route('administrasi.kelengkapansk.editkelengkapan')}}";
    var urlstorekelengkapan = "{{route('administrasi.kelengkapansk.storekelengkapan')}}";
    var urldeletekelengkapan = "{{route('administrasi.kelengkapansk.deletekelengkapan')}}";
    var urldatatablekelengkapan = "{{route('administrasi.kelengkapansk.datatablekelengkapan')}}";
    var urldatatablesumkelengkapan = "{{route('administrasi.kelengkapansk.datatablesumkelengkapan')}}";


    var urlsavetambah2 = "{{route('administrasi.kelengkapansk.savetambah2')}}";
</script>

<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/tambah3.js')}}"></script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/instansi.js')}}"></script>
<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/assesmen.js')}}"></script>
<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/penghasilan.js')}}"></script>
<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/kelengkapan.js')}}"></script>
@endsection