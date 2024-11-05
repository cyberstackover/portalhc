<div class="kt-portlet__head">
	<div class="kt-portlet__head-label">
		<h3 class="kt-portlet__head-title">
			{{$namaperusahaan->nama_lengkap}}
		</h3>
		<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{(int)$id_perusahaan}}" />
		<input type="hidden" name="id_surat_keputusan" id="id_surat_keputusan" readonly="readonly" value="{{(int)$id_surat_keputusan}}" />
	</div>
</div>
<div class="kt-portlet__body">
	@foreach($jenis_sk_id as $jenis_sk)
		@if($jenis_sk['id_jenis_sk'] == 1)

		<h5>Pengangkatan</h5>
		<div class="table-responsive">
			<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-angkat">
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
        @elseif($jenis_sk['id_jenis_sk'] == 2)
        <!--begin: Datatable -->
	
        <h5>Pemberhentian</h5>
        <div class="table-responsive">
        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-berhenti">
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
        @elseif($jenis_sk['id_jenis_sk'] == 3)
        <h5>Pelaksana Tugas</h5>
        <div class="table-responsive">
        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-plt">
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
        @elseif($jenis_sk['id_jenis_sk'] == 4)
        <h5>Perubahan Nomenklatur</h5>
        <div class="table-responsive">
        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-klatur">
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
        @elseif($jenis_sk['id_jenis_sk'] == 5)
        <h5>Alih Tugas</h5>
        <div class="table-responsive">
        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-alt">
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
        
        @elseif($jenis_sk['id_jenis_sk'] == 7)
        <h5>Komisaris Independen</h5>
        <div class="table-responsive">
        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-sum-independen">
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

<script type="text/javascript">
	var urldatatablesumangkat = "{{route('administrasi.anak.datatablesumangkat')}}";
	var urldatatablesumhenti = "{{route('administrasi.anak.datatablesumhenti')}}";
	var urldatatablesumklatur = "{{route('administrasi.anak.datatablesumklatur')}}";
	var urldatatablesumplt = "{{route('administrasi.anak.datatablesumplt')}}";
	var urldatatablesumalt = "{{route('administrasi.anak.datatablesumalt')}}";
	var urldatatablesumindependen = "{{route('administrasi.anak.datatablesumindependen')}}";
</script>

<script type="text/javascript" src="{{asset('js/administrasi/anak/sumangkat.js')}}"></script>