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
				{{$namaperusahaan->nama_lengkap}} ({{$nomor_sk}}) <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		<div class="container py-3 my-3 border rounded shadow-sm">
		    <div class="row">
		        <div class="col-12">
		            <ul class="nav nav-tabs flex-nowrap" role="tablist">
		            	@foreach($tabjenisks as $key => $value)

		            	<li role="presentation" class="nav-item">
		                    <a href="#kt_portlet_base_demo_1_{{$value->id}}_tab_content_{{$value->id}}" class="nav-link {{$key === 0 ? 'active' : 'disabled'}}" data-toggle="tab" aria-controls="step{{$value->id}}" role="tab" title="{{$value->nama}}"> {{$value->nama}} </a>
		                </li>
		            	
		                @endforeach
		                <li role="presentation" class="nav-item">
		                    <a href="#summary" class="nav-link disabled" data-toggle="tab" aria-controls="summary" role="tab" title="Summary"> Summary </a>
		                </li>
		            </ul>
		            <form role="form">
		            	<input type="hidden" name="id_surat_keputusan" id="id_surat_keputusan" readonly="readonly" value="{{$id_surat_keputusan}}" />
						<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$id_perusahaan}}" />
						<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$grup_jabatan_id}}" />
		                <div class="tab-content py-2">
		                	@foreach($jenis_sk_id as $key => $value)
		                	@if($value == 1)
		                    <div class="tab-pane {{$key === 0 ? 'active' : ''}}" role="tabpanel" id="kt_portlet_base_demo_1_{{$value}}_tab_content_{{$value}}">

		                    	@if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											{{$namaperusahaan->nama_lengkap}} ({{$nomor_sk}}) <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
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
														<a href="#" class="kt-nav__link cls-add-angkat-anak">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Pengangkatan</span>
														</a>
													</li>
													<li class="kt-nav__item">
														<a href="#" class="kt-nav__link cls-add-angkatlagi-anak">
															<i class="kt-nav__link-icon flaticon2-send" ></i>
															<span class="kt-nav__link-text">Perpanjangan</span>
														</a>
													</li>
													<li class="kt-nav__item">
														<a href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="kt-nav__link ">
															<i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
															<span class="kt-nav__link-text">Data Pokok</span>
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
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-angkat-anak">
								            <thead>
								                <tr>
								                    <th width="5%"><div align="center">No.</div></th>
								                    <th width="30%"><div align="center">Jabatan</div></th>
								                    <th width="15%"><div align="center">Nama Pejabat</div></th>
								                    <th width="5%"><div align="center">Periode</div></th>
								                    <th width="10%"><div align="center">Tanggal Awal Menjabat</div></th>
								                    <th width="10%"><div align="center">Tanggal Akhir Menjabat</div></th>
								                    <th width="15%"><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        

							        <!--end: Datatable -->
								</div>
		                        @if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    </div>
		                    @elseif($value == 2)
		                    <div class="tab-pane {{$key === 0 ? 'active' : ''}}" role="tabpanel" id="kt_portlet_base_demo_1_{{$value}}_tab_content_{{$value}}">

		                    	@if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											{{$namaperusahaan->nama_lengkap}} ({{$nomor_sk}}) <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
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
														<a href="#" class="kt-nav__link cls-add-berhenti-anak">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Pemberhentian</span>
														</a>
													</li>
													<li class="kt-nav__item">
														<a href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="kt-nav__link ">
															<i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
															<span class="kt-nav__link-text">Data Pokok</span>
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
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-berhenti-anak">
								            <thead>
								                <tr>
								                    <th width="5%"><div align="center">No.</div></th>
								                    <th width="10%"><div align="center">Jabatan</div></th>
								                    <th width="25%"><div align="center">Nama Pejabat</div></th>
								                    <th width="25%"><div align="center">Keterangan</div></th>
								                    <th width="10%"><div align="center">Tanggal Akhir Menjabat</div></th>
								                    <th width="5%"><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        

							        <!--end: Datatable -->
								</div>
		                        @if($key === 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    </div>
		                    @elseif($value == 3)
		                    <div class="tab-pane {{$key === 0 ? 'active' : ''}}" role="tabpanel" id="kt_portlet_base_demo_1_{{$value}}_tab_content_{{$value}}">

		                    	@if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											{{$namaperusahaan->nama_lengkap}} ({{$nomor_sk}}) <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
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
														<a href="#" class="kt-nav__link cls-add-plt-anak">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Penetapan PLT</span>
														</a>
													</li>
													<li class="kt-nav__item">
														<a href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="kt-nav__link ">
															<i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
															<span class="kt-nav__link-text">Data Pokok</span>
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
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-plt-anak">
								            <thead>
								                <tr>
								                    <th><div align="center">No.</div></th>
								                    <th><div align="center">Jabatan PLT</div></th>
								                    <th><div align="center">Nama Pejabat</div></th>
								                    <th><div align="center">Tanggal Awal Menjabat</div></th>
								                    <th><div align="center">Tanggal Akhir Menjabat</div></th>
								                    <th><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        

							        <!--end: Datatable -->
								</div>
		                        @if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    </div>
		                    @elseif($value == 4)
		                    <div class="tab-pane {{$key === 0 ? 'active' : ''}}" role="tabpanel" id="kt_portlet_base_demo_1_{{$value}}_tab_content_{{$value}}">

		                    	@if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											{{$namaperusahaan->nama_lengkap}} ({{$nomor_sk}}) <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
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
														<a href="#" class="kt-nav__link cls-add-klatur-anak">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Nomenklatur</span>
														</a>
													</li>
													<li class="kt-nav__item">
														<a href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="kt-nav__link ">
															<i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
															<span class="kt-nav__link-text">Data Pokok</span>
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
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-klatur-anak">
								            <thead>
								                <tr>
								                    <th><div align="center">No.</div></th>
								                    <th><div align="center">Jabatan</div></th>
								                    <th><div align="center">Nomenklatur</div></th>
								                    <th><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        

							        <!--end: Datatable -->
								</div>
		                        @if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    </div>
		                    @elseif($value == 5)
		                    <div class="tab-pane {{$key === 0 ? 'active' : ''}}" role="tabpanel" id="kt_portlet_base_demo_1_{{$value}}_tab_content_{{$value}}">


		                    	@if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											{{$namaperusahaan->nama_lengkap}} ({{$nomor_sk}}) <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
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
														<a href="#" class="kt-nav__link cls-add-alt-anak">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Alih Tugas</span>
														</a>
													</li>
													<li class="kt-nav__item">
														<a href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="kt-nav__link ">
															<i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
															<span class="kt-nav__link-text">Data Pokok</span>
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
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-alt-anak">
								            <thead>
								                <tr>
								                    <th><div align="center">No.</div></th>
								                    <th><div align="center">Nama Pejabat</div></th>
								                    <th><div align="center">Jabatan Alih Tugas</div></th>
								                    <th><div align="center">Tanggal Awal Alih Tugas</div></th>
								                    <th><div align="center">Tanggal Akhir Alih Tugas</div></th>
								                    <th><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        

							        <!--end: Datatable -->
								</div>
		                        @if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    </div>
		                    @elseif($value == 7)
		                    <div class="tab-pane {{$key === 0 ? 'active' : ''}}" role="tabpanel" id="kt_portlet_base_demo_1_{{$value}}_tab_content_{{$value}}">


		                    	@if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    	<br>
		                    	<br>
		                    	<br>

		                        <div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											{{$namaperusahaan->nama_lengkap}} ({{$nomor_sk}}) <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
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
														<a href="#" class="kt-nav__link cls-add-independen-anak">
															<i class="kt-nav__link-icon flaticon2-line-chart "></i>
															<span class="kt-nav__link-text">Komisaris Independen</span>
														</a>
													</li>
													<li class="kt-nav__item">
														<a href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="kt-nav__link ">
															<i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
															<span class="kt-nav__link-text">Data Pokok</span>
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
										<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-independen-anak">
								            <thead>
								                <tr>
								                    <th><div align="center">No.</div></th>
								                    <th><div align="center">Nama Pejabat</div></th>
								                    <th><div align="center">Jabatan</div></th>
								                    <th><div align="center">Tanggal Awal Jabatan</div></th>
								                    <th><div align="center">Tanggal Akhir Jabatan</div></th>
								                    <th><div align="center">Aksi</div></th>
								                </tr>
								            </thead>
								            <tbody></tbody>
								        </table>
									</div>
							        

							        <!--end: Datatable -->
								</div>
		                        @if($key == 0)
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @else
		                        <ul class="float-right">
		                            <li class="list-inline-item">
		                                <a type="button" href="/administrasi/anak/{{$id_surat_keputusan}}/edittambah" class="btn btn-primary">
											<span><<</span> Data Pokok
										</a>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
		                            </li>
		                            <li class="list-inline-item">
		                                <button type="button" class="btn btn-warning next-step">Next <span>>></span></button>
		                            </li>
		                        </ul>
		                        @endif
		                    </div>
		                    @endif
		                    @endforeach
		                    <div class="tab-pane" role="tabpanel" id="summary">

		                    	<ul class="float-right">
				                    <li class="list-inline-item">
				                        <button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
				                    </li>
				                    <li class="list-inline-item">
				                        <button type="button" class="btn btn-brand save-tambah2">Submit</button>
				                    </li>
				                    <li class="list-inline-item">
				                        <span class="kt-margin-left-10">or <a href="/administrasi/anak/index" class="kt-link kt-font-bold">Cancel</a></span>
				                    </li>


				                </ul>
				            	<br>
				            	<br>
				            	<br>

		                        <div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Summary Input Data {{$namaperusahaan->nama_lengkap}} ({{$nomor_sk}}) <span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">{{$namagrupjabat->nama}}</span>
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					@foreach($jenis_sk_id as $jenis_sk)
						@if($jenis_sk == 1)

						<h5>Pengangkatan</h5>
						<div class="table-responsive">
							<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-angkat-anak">
					            <thead>
					                <tr>
					                    <th><div align="center">No.</div></th>
					                    <th><div align="center">Jabatan</div></th>
					                    <th><div align="center">Nama Pejabat</div></th>
					                    <th><div align="center">Periode</div></th>
					                    <th><div align="center">Tanggal Awal Menjabat</div></th>
					                    <th><div align="center">Tanggal Akhir Menjabat</div></th>
					                </tr>
					            </thead>
					            <tbody></tbody>
					        </table>
						</div>
				        
				        <!--end: Datatable -->
				        <br>
				        @elseif($jenis_sk == 2)
				        <!--begin: Datatable -->
					
				        <h5>Pemberhentian</h5>
				        <div class="table-responsive">
				        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-berhenti-anak">
					            <thead>
					                <tr>
					                    <th><div align="center">No.</div></th>
					                    <th><div align="center">Jabatan</div></th>
					                    <th><div align="center">Nama Pejabat</div></th>
					                    <th><div align="center">Keterangan</div></th>
					                    <th><div align="center">Tanggal Akhir Menjabat</div></th>
					                </tr>
					            </thead>
					            <tbody></tbody>
					        </table>
				        </div>
				        
				        <!--end: Datatable -->
				        <br>
				        @elseif($jenis_sk == 3)
				        <h5>Pelaksana Tugas</h5>
				        <div class="table-responsive">
				        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-plt-anak">
					            <thead>
					                <tr>
					                    <th><div align="center">No.</div></th>
					                    <th><div align="center">Jabatan PLT</div></th>
					                    <th><div align="center">Nama Pejabat</div></th>
					                    <th><div align="center">Tanggal Awal Menjabat</div></th>
					                    <th><div align="center">Tanggal Akhir Menjabat</div></th>
					                </tr>
					            </thead>
					            <tbody></tbody>
					        </table>
				        </div>
				        
				        <!--end: Datatable -->
				        <br>
				        @elseif($jenis_sk == 4)
				        <h5>Perubahan Nomenklatur</h5>
				        <div class="table-responsive">
				        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-klatur-anak">
					            <thead>
					                <tr>
					                    <th><div align="center">No.</div></th>
					                    <th><div align="center">Jabatan</div></th>
					                    <th><div align="center">Nomenklatur</div></th>
					                </tr>
					            </thead>
					            <tbody></tbody>
					        </table>
				        </div>
				        
				        <!--end: Datatable -->
				        <br>
				        @elseif($jenis_sk == 5)
				        <h5>Alih Tugas</h5>
				        <div class="table-responsive">
				        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-alt-anak">
					            <thead>
					                <tr>
					                    <th><div align="center">No.</div></th>
					                    <th><div align="center">Nama Pejabat</div></th>
					                    <th><div align="center">Jabatan Alih Tugas</div></th>
					                    <th><div align="center">Tanggal Awal Alih Tugas</div></th>
					                    <th><div align="center">Tanggal Akhir Alih Tugas</div></th>
					                </tr>
					            </thead>
					            <tbody></tbody>
					        </table>
				        </div>
				        
				        @elseif($jenis_sk == 7)
				        <h5>Komisaris Independen</h5>
				        <div class="table-responsive">
				        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-independen-anak">
					            <thead>
					                <tr>
					                    <th><div align="center">No.</div></th>
					                    <th><div align="center">Nama Pejabat</div></th>
					                    <th><div align="center">Jabatan</div></th>
					                    <th><div align="center">Tanggal Awal Jabatan</div></th>
					                    <th><div align="center">Tanggal Akhir Jabatan</div></th>
					                </tr>
					            </thead>
					            <tbody></tbody>
					        </table>
				        </div>
				        
				        @endif
					@endforeach
			    </div>
			    <div class="kt-portlet__foot">
					<div class="row align-items-center">
						<div class="col-lg-6 m--valign-middle">
							Pesan Konfirmasi: Tahap Input Sudah Selesai, Apakah anda yakin untuk submit data tersebut?
						</div>
						<div class="col-lg-6 kt-align-right">
							<button type="button" class="btn btn-warning prev-step"><span><<</span> Previous</button>
							<button type="button" class="btn btn-brand save-tambah2">Submit</button>
							<span class="kt-margin-left-10">or <a href="/administrasi/anak/index" class="kt-link kt-font-bold">Cancel</a></span>
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
	//pengangkatan
    var urlcreateangkat = "{{route('administrasi.anak.createangkat')}}";
    var urlcreateangkatlagi = "{{route('administrasi.anak.createangkatlagi')}}";
    var urleditangkat = "{{route('administrasi.anak.editangkat')}}";
    var urleditangkatlagi = "{{route('administrasi.anak.editangkatlagi')}}";
    var urlstoreangkat = "{{route('administrasi.anak.storeangkat')}}";
    var urlstoreangkatlagi = "{{route('administrasi.anak.storeangkatlagi')}}";
    var urldeleteangkat = "{{route('administrasi.anak.deleteangkat')}}";
    var urldatatableangkat = "{{route('administrasi.anak.datatableangkat')}}";
    var urldatatablesumangkat = "{{route('administrasi.anak.datatablesumangkat')}}";
    //pemberhentian
    var urlcreatehenti = "{{route('administrasi.anak.createhenti')}}";
    var urledithenti = "{{route('administrasi.anak.edithenti')}}";
    var urlstorehenti = "{{route('administrasi.anak.storehenti')}}";
    var urldeletehenti = "{{route('administrasi.anak.deletehenti')}}";
    var urldatatablehenti = "{{route('administrasi.anak.datatablehenti')}}";
    var urldatatablesumhenti = "{{route('administrasi.anak.datatablesumhenti')}}";
    //nomenklatur
    var urlcreateklatur = "{{route('administrasi.anak.createklatur')}}";
    var urleditklatur = "{{route('administrasi.anak.editklatur')}}";
    var urlstoreklatur = "{{route('administrasi.anak.storeklatur')}}";
    var urldeleteklatur = "{{route('administrasi.anak.deleteklatur')}}";
    var urldatatableklatur = "{{route('administrasi.anak.datatableklatur')}}";
    var urldatatablesumklatur = "{{route('administrasi.anak.datatablesumklatur')}}";
    //plt
    var urlcreateplt = "{{route('administrasi.anak.createplt')}}";
    var urleditplt = "{{route('administrasi.anak.editplt')}}";
    var urlstoreplt = "{{route('administrasi.anak.storeplt')}}";
    var urldeleteplt = "{{route('administrasi.anak.deleteplt')}}";
    var urldatatableplt = "{{route('administrasi.anak.datatableplt')}}";
    var urldatatablesumplt = "{{route('administrasi.anak.datatablesumplt')}}";
    //alt
    var urlcreatealt = "{{route('administrasi.anak.createalt')}}";
    var urleditalt = "{{route('administrasi.anak.editalt')}}";
    var urlstorealt = "{{route('administrasi.anak.storealt')}}";
    var urldeletealt = "{{route('administrasi.anak.deletealt')}}";
    var urldatatablealt = "{{route('administrasi.anak.datatablealt')}}";
    var urldatatablesumalt = "{{route('administrasi.anak.datatablesumalt')}}";
    //independen
    var urlcreateindependen = "{{route('administrasi.anak.createindependen')}}";
    var urleditindependen = "{{route('administrasi.anak.editindependen')}}";
    var urlstoreindependen = "{{route('administrasi.anak.storeindependen')}}";
    var urldeleteindependen = "{{route('administrasi.anak.deleteindependen')}}";
    var urldatatableindependen = "{{route('administrasi.anak.datatableindependen')}}";
    var urldatatablesumindependen = "{{route('administrasi.anak.datatablesumindependen')}}";

    var urlsavetambah2 = "{{route('administrasi.anak.savetambah2')}}";
</script>

<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/administrasi/anak/tambah3.js')}}"></script>
<script type="text/javascript" src="{{asset('js/administrasi/anak/henti.js')}}"></script>
<script type="text/javascript" src="{{asset('js/administrasi/anak/klatur.js')}}"></script>
<script type="text/javascript" src="{{asset('js/administrasi/anak/plt.js')}}"></script>
<script type="text/javascript" src="{{asset('js/administrasi/anak/alt.js')}}"></script>
<script type="text/javascript" src="{{asset('js/administrasi/anak/independen.js')}}"></script>
@endsection