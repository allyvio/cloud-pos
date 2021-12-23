@extends('layouts.master')

@section('content')

{{-- @dd($id_) --}}

<section class="content">
	<div class="row justify-content-center mt-3">
		<div class="col-md-10">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Daftar Pegawai</h3>
				</div>

				<!-- /.card-header -->
				<div class="card-body">
					<div class="mb-3">
						<button class="btn btn-primary" data-toggle="modal" data-target="#createModal" id="create">Tambah Pegawai</button>
					</div>
					<table id="dataTable" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th>Email</th>
								<th>Action</th>
							</tr>
						</thead>

					</table>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
			<!-- /.card -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title">Tambah Pegawai</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{url('post-user-toko')}}" method="POST" enctype="multipart/form-data">
				@csrf

				<div class="modal-body">
					<input type="hidden" name="shop_id" id="shop_id" value="{{$id_}}">
					<div class="form-group">
						<label for="status" class="col-form-label">Nama Pegawai:</label>
						<select name="user_id" id="user_id" class="custom-select">
							@foreach ($pegawai as $item)
							<option value="{{$item->id}}">{{$item->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="save-btn">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title">Edit Pegawai</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form name="edit_product" action="{{url('edit_user_toko')}}" method="POST" enctype="multipart/form-data">
				@csrf
				{{-- {{method_field('PUT')}} --}}
				<div class="modal-body">
					<input type="hidden" name="id" id="id" value="">
					<p>
						Apakah anda yakin menonaktifkan pegawai ini ?
					</p>
					{{-- <input type="hidden" name="user_id" id="user_id" value=""> --}}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="save-btn">Save Change</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@section('footer')

<script>
	$(document).ready(function(){
		var table = $('#dataTable').DataTable({
			"pageLength":5,
			processing:true,
			searching:true,
			order:[[0,'asc']],
			info:false,
			lengthMenu: [[2,3,4,5,-1],[2,3,4,5,"All"]],
			serverside:true,
			ordering:false,
			ajax:"{{url('getusertoko/'.$id_)}}",
			columns: [
			{data: 'rownum', name: 'rownum'},
			{data: 'user_id', name: 'user_id'},
			{data: 'email', name: 'email'},
			{data: 'action', name: 'action'},
			]

		});

		// console.log(data);



		$('#editModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var id = button.data('id')

			var modal = $(this)
			modal.find('.modal-body #id').val(id)
		});






	});
</script>

@stop
