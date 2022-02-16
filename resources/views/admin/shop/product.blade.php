@extends('admin._layouts.master')

@section('content')

<section class="content">
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Produk Toko</h6>
						<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
							<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
								<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="{{url('shop', $id_)}}">{{$shop}}</i></a></li>
								<li class="breadcrumb-item"><a href="#">Produk</a></li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="container-fluid mt--6">
		<!-- Table -->
		<div class="row">
			<div class="col">
				<div class="card">
					<!-- Card header -->
					<div class="card-header">
						<div class="row">
							<div class="col-4">
								<h3 class="mb-0">List Data Produk</h3>
							</div>
							<div class="col-8 text-right">
								<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createModal" id="create">Tambah Produk</button>
								<a href="{{url('rekapStockToko', $id_)}}" class="btn btn-sm btn-primary">Rekap Stok</a>
							</div>
						</div>
					</div>
					<div class="table-responsive py-4">
						<table class="table table-flush" id="dataTable">
							<thead class="thead-light">
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Stok</th>
									<th>Harga</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</section>

@endsection

@section('modal')

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title">Tambah Produk</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="addForm" action="{{url('post-product-toko')}}" method="POST" enctype="multipart/form-data">
				@csrf
				
				<div class="modal-body">
					<input type="hidden" name="shop_id" id="shop_id" value="{{$id_}}">
					<div class="form-group">
						<label for="status" class="col-form-label">Nama Kategori:</label>
						<select name="kategori" id="kategori" class="custom-select">
							<option selected disabled>Pilih Kategori</option>
							@php
							$category = \DB::table('categories')->get();
							@endphp
							@foreach ($category as $item)
							<option value="{{$item->id}}">{{$item->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label">Nama Produk:</label>
						<select name="product_id" id="product_id" class="custom-select">
							<option selected disabled >Pilih Produk</option>
						</select>
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label">Stock tersedia:</label>
						<input type="number" name="stock" id="stock" value="" class="form-control" disabled>
						<input type="hidden" name="stock_" id="stock_" value="" class="form-control">
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label">Stock Produk:</label>
						<input type="number" min="0" name="qty" id="qty" class="form-control">
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
				<h5 class="modal-title" id="modal-title">Edit Stock</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm" name="edit_product" action="{{url('stock_toko')}}" method="POST" enctype="multipart/form-data">
				@csrf
				{{-- {{method_field('PUT')}} --}}
				<div class="modal-body">
					<input type="hidden" name="id" id="id" value="">
					<input type="hidden" name="id_product" id="id_product" value="">
					<div class="form-group">
						<label for="status" class="col-form-label">Nama Produk:</label>
						<input disabled type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror">
						
						@error('nama') <div class="invalid-feedback">{{$message}}</div> @enderror
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label">Warna Produk:</label>
						<input disabled type="text" name="warna" id="warna" class="form-control @error('nama') is-invalid @enderror">
						
						@error('nama') <div class="invalid-feedback">{{$message}}</div> @enderror
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label">Harga Produk:</label>
						<input disabled type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror">
						@error('harga') <div class="invalid-feedback">{{$message}}</div> @enderror
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label">Stock Produk Tersedia:</label>
						<input disabled type="number" name="temp_stock" id="temp_stock" class="form-control @error('stock') is-invalid @enderror">
						<input type="hidden" name="temp_stock_" id="temp_stock_" class="form-control">
						@error('stock') <div class="invalid-feedback">{{$message}}</div> @enderror
					</div>
					<div class="form-check">
						<div class="col-md-3">
							<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="tambah" checked>
							<label class="form-check-label" for="exampleRadios1">
								Tambah Stock
							</label>
						</div>
						<div class="col-md-3">
							<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="kurang">
							<label class="form-check-label" for="exampleRadios2">
								Tarik Stock
							</label>
						</div>
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label">Stock Produk:</label>
						<input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror">
						@error('stock') <div class="invalid-feedback">{{$message}}</div> @enderror
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

@endsection

@section('script')
<script>
	$('#addForm').validate({
		rules: {
			product_id: {
				required: true
			},
			qty: {
				required: true
			}
		}
	});
	
	$('#editForm').validate({
		rules: {
			stock: {
				required: true,
				digits: true
			},
		}
	});
	
	
</script>

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
			language: { 
				paginate: { previous: "<i class='fas fa-angle-left'>", next: "<i class='fas fa-angle-right'>"
				}
			},
			ordering:false,
			ajax:"{{url('getproduktoko/'.$id_)}}",
			columns: [
			{
				data: null,
				searchable: false,
				orderable: false,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}  
			},
			{data: 'product_id', name: 'product_id'},
			{data: 'temp_stock', name: 'temp_stock'},
			{data: 'harga', name: 'harga'},
			{data: 'action', name: 'action'},
			]
			
		});
		
		// console.log(data);
		
		
		
		$('#editModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var id = button.data('id')
			var product_id = button.data('product_id')
			var name = button.data('name')
			var warna = button.data('warna')
			var harga = button.data('harga')
			var stock = button.data('stock')
			
			console.log(product_id);
			
			jQuery.ajax({
				url: "{{url('getstock')}}" + '/' + product_id,
				type: "GET",
				dataType: "json",
				success: function(data) {
					// $('select[name="city_destination"]').empty();
					$("input[name='temp_stock']").empty();
					$("input[name='temp_stock_']").empty();
					$("input[name='temp_stock']").val(data.stock);
					$("input[name='temp_stock_']").val(data.stock);
				},
			});
			
			var modal = $(this)
			modal.find('.modal-body #id').val(id)
			modal.find('.modal-body #id_product').val(product_id)
			modal.find('.modal-body #nama').val(name)
			modal.find('.modal-body #warna').val(warna)
			modal.find('.modal-body #harga').val(harga)
			modal.find('.modal-body #stock').val(stock)
		});
		
		$('#hapusModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var id = button.data('id')
			
			var modal = $(this)
			modal.find('.modal-body #id').val(id)
			
		});
		
		
		
		
		
	});
</script>
<script>
	$(document).ready(function() {
		$('select[name="product_id"]').on('change', function() {
			let product_id = $(this).val();
			console.log(product_id);
			
			jQuery.ajax({
				url: "{{url('getstock')}}" + '/' + product_id,
				type: "GET",
				dataType: "json",
				success: function(data) {
					// $('select[name="city_destination"]').empty();
					$("input[name='stock']").empty();
					$("input[name='stock_']").empty();
					$("input[name='stock']").val(data.stock);
					$("input[name='stock_']").val(data.stock);
				},
			});
		});
	});
</script>


<script>
	$(document).ready(function() {
		$('select[name="kategori"]').on('change', function() {
			let kategori = $(this).val();
			console.log(kategori);
			if (kategori) {
				jQuery.ajax({
					url: "{{url('productcat')}}" + '/' + kategori,
					type: "GET",
					dataType: "json",
					success: function(data) {
						// console.log(data[]);
						let x = 0;
						$('select[name="product_id"]').empty();
						$.each(data[0], function(key, value) {
							$('select[name="product_id"]').append('<option value="' + key + '">'+ value + '-'+data[1][x]+'</option> ');
							x++;
						});
						
					},
				});
				
			} else {
				$("input[name='product_id]").empty();
				
			}
		});
	});
</script>


@stop
