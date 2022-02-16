@extends('admin._layouts.master')

@section('content')

<section class="content">
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Rekap Penjualan</h6>
						<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
							<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
								<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="{{url('shop', $id)}}">{{$shop}}</i></a></li>
								<li class="breadcrumb-item"><a href="#">Rekap Penjualan</a></li>
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
								<h3 class="mb-0">List Data Penjualan</h3>
							</div>
							<div class="col-8 text-right">
								<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#printModal" id="create">Cetak Laporan</button>
							</div>
							<div class="row mt-3 px-3">
								<div class="col-4">
									<input type="text" name="from_date" id="from_date" class="form-control datepicker" placeholder="From Date" readonly />
								</div>
								<div class="col-4">
									<input type="text" name="to_date" id="to_date" class="form-control datepicker" placeholder="To Date" readonly />
								</div>
								<div class="col-4">
									<button type="filter" name="filter" id="filter" class="btn btn-primary">Filter</button>
									<button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive py-4">
						<table class="table table-flush" id="dataTable">
							<thead class="thead-light">
								<tr>
									<th>No</th>
									<th>Toko</th>
									<th>Pegawai</th>
									<th>Produk</th>
									<th>Qty</th>
									<th>Total</th>
									<th>Tanggal</th>
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

<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title">Pilih tanggal rekap</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form name="edit_product" action="{{url('rekapTokoExcel')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="shop_id" id="shop_id" value="{{$id}}" />
				<div class="modal-body row">
					
					<div class="col-md-6 mb-3">
						<input type="text" name="from_date" id="from_date" class="form-control datepicker" placeholder="Dari tanggal" readonly />
					</div>
					<div class="col-md-6 mb-3">
						<input type="text" name="to_date" id="to_date" class="form-control datepicker" placeholder="Ke tanggal" readonly />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="save-btn">Print</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@section('script')

<script>
	$(document).ready(function(){
		load_data();
		function load_data(from_date = '', to_date = '')
		{
			$('#dataTable').DataTable({
				"pageLength":5,
				processing:true,
				searching:true,
				bDestroy: true,
				order:[[0,'asc']],
				info:false,
				lengthMenu: [[2,3,4,5,-1],[2,3,4,5,"All"]],
				serverside:true,
				language: { 
					paginate: { previous: "<i class='fas fa-angle-left'>", next: "<i class='fas fa-angle-right'>"
					}
				},
				ajax:{
					url:"{{url('getrekaptoko/'.$id)}}",
					data:{from_date:from_date, to_date:to_date}
				},
				columns: [
				{
					data: null,
					searchable: false,
					orderable: false,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}  
				},
				{data: 'name', name: 'name'},
				{data: 'nama_pegawai', name: 'nama_pegawai'},
				{data: 'product_name', name: 'product_name'},
				{data: 'qty', name: 'qty'},
				{data: 'total', name: 'total'},
				{data: 'tanggal', name: 'tanggal'},
				]
				
			});
		}
		
		$('#filter').click(function(){
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();
			if(from_date != '' &&  to_date != '')
			{
				load_data(from_date, to_date);
			}
			else
			{
				alert('Tanggal wajib diisi');
			}
		});
		$('#refresh').click(function(){
			$('#from_date').val('');
			$('#to_date').val('');
			load_data();
		});
		
		// console.log(data);
		
		
	});
</script>


@stop
