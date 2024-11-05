<div class="kt-portlet__body">
	<div class="table-responsive">
		<table class="table table-sm table-head-bg-brand">
			<thead>
				<th>No</th>
				<th>Perusahaan</th>
			</thead>
			<tbody>
				<?php $i=1 ?>
				@foreach($detailbumns as $row)
				<tr>
					<td>{{$i}}</td>
					<td>{{$row->nama_lengkap}}</td>
				</tr>
				<?php $i++ ?>
				@endforeach
			</tbody>
		</table>
	</div>
	
</div>