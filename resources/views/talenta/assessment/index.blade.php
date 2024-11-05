@extends('layouts.app')


@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                {{$pagetitle}}
            </h3>
        </div>
    </div>
    
    <div class="kt-portlet__body">

        <div class="alert alert-custom fade show mb-5" style="background-color:yellow;" role="alert">
            <div class="alert-text"><b>Keterangan :</b><br>
                <b>Approval Nominated -></b> Persetujuan atas usulan talent dari BUMN, status talent Nominated<br>
                <b>Assessment Assignment -></b> Assign talent ke lembaga assessment<br>
                <b>Verifikasi Assessment -></b> Memverifikasi hasil assessment talent<br>
                <b>View All -></b> Melihat semua data talent termasuk statusnya
            </div>
            <div class="alert-close">
                <button style="margin-top:-50px;" type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="flaticon2-delete" style="font-size:x-small;"></i></span>
                </button>
            </div>
        </div>

      <!-- end pencarian -->

        <ul class="nav nav-tabs nav-tabs mb-5">
            <li class="nav-item">
                <a href="/talenta/verifikasi_kbumn/index" class="nav-link btn-approval"> Approval Nominated <label id="jumlah_nominated" class="kt-badge kt-badge--danger kt-badge--pill kt-badge--rounded">{{$data['jumlah_nominated']}}</label></a>
            </li>
            <li class="nav-item">
                <a href="/talenta/assessment/index" class="nav-link active btn-assessment"> Assessment Assignment 
                    <label id="jumlah_eligible1" class="kt-badge kt-badge--danger kt-badge--pill kt-badge--rounded">{{$data['jumlah_eligible1']}}</label>
                </a>
            </li>
            <li class="nav-item">
                <a href="/talenta/verifikasi_assessment/index" class="nav-link btn-verifikasi"> Verifikasi Assessment
                    <label id="jumlah_eligible2" class="kt-badge kt-badge--danger kt-badge--pill kt-badge--rounded">{{$data['jumlah_eligible2']}}</label>
                </a>
            </li>
        </ul>

        <div class="form-group row">
            <div class="col-lg-4">
                <label><span class="kt-font-dark">BUMN:</span></label>
                
                <select class="form-control kt-select2" id="instansi" name="instansi">
                    <option></option>
                    @foreach($perusahaan as $data)
                    <option value="{{ $data->id }}">{{ $data->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
            </div>
        </div>

        <div class="tab-content">
            <!-- Data Talent -->
            <div class="tab-pane active" role="tabpanel" id="datatalent">
                <!--begin: Datatable -->
                <div class="table-responsive">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th >Nama Lengkap</th>
                                <th >Status Talent</th>
                                <th >Jabatan</th>
                                <th ><div align="center">Lembaga Assessment</div></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!--end: Datatable -->
            </div>
            <!-- end Data Talent -->

        </div>

    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
      var urlminicv = "{{route('talenta.register.minicv')}}";
      var urllogstatus = "{{route('talenta.register.log_status')}}";
      var urldatatable = "{{route('talenta.assessment.datatable')}}";
      var urlapprove = "{{route('talenta.assessment.approve')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/talenta/assessment/index.js')}}"></script>
@endsection
