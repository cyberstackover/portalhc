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
            <div class="alert-text"><b>Petunjuk :</b><br>
                Klik button <font style="color:red;"> Input </font>untuk input hasil assessment talent
            </div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
        </div>

        <div class="kt-portlet kt-shape-bg-color-2">
            <form class="kt-form kt-form--label-right" >
                <div class="kt-portlet__body">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label><span class="kt-font-dark">Search Talent:</span></label>
                            
                            <select class="form-control kt-select2" id="nama_lengkap" name="nama_lengkap">
                                <option></option>
                                @foreach($talenta as $data)
                                <option value="{{ $data->nama_lengkap }}">{{ $data->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label><span class="kt-font-dark">Assessor:</span></label>
                            <select class="form-control kt-select2" id="lembaga_assessment" name="lembaga_assessment">
                                <option></option>
                                @foreach($lembaga_assessment as $data)
                                <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 pull-right">
                            <div class="">
                                <a id="cari" type="button" class="btn btn-danger" href="javascript:;" >cari</a>
                                <a id="reset" type="button" class="btn btn-warning" href="javascript:;" >reset</a>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </form>
        </div>

        <!--begin: Datatable -->
        <div class="table-responsive">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th >Nama Lengkap</th>
                        <th width="350px">Jabatan</th>
                        <th >Assessor</th>
                        <th width="70px">Hasil</th>
                        <th width="10%"><div align="center">Aksi</div></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <!--end: Datatable -->
    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
      var urlminicv = "{{route('talenta.assessment_assessor.cv')}}";
      var urllogstatus = "{{route('talenta.register.log_status')}}";
      var urldatatable = "{{route('talenta.assessment_assessor.datatable')}}";
      var urlapprove = "{{route('talenta.assessment_assessor.approve')}}";
      var urlreject = "{{route('talenta.assessment_assessor.reject')}}";
      var urlupdate_talenta = "{{route('talenta.assessment_assessor.update_talenta')}}";
  </script>
  
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/talenta/assessment_assessor/index.js')}}"></script>
@endsection
