@extends('layouts.master')

@section('content')

{{-- @dd($id_) --}}

<section class="content">
	<div class="row justify-content-center mt-3">
		<div class="col-md-10">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Rekap Penjualan</h3>
				</div>

				<!-- /.card-header -->
				<div class="card-body">
					<div class="mb-3">
						<div class="row input-daterange">
							<div class="col-md-4 mb-3">
								<input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
							</div>
							<div class="col-md-4 mb-3">
								<input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
							</div>
							<div class="col-md-4 mb-3">
								<button type="filter" name="filter" id="filter" class="btn btn-primary">Filter</button>
								<button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
							</div>
							<div class="ml-auto mr-2">
								<button type="button" data-target="#printModal" data-toggle="modal" class="btn btn-info">Cetak Laporan</button>
								{{-- <a href="{{url('rekapExcel')}}">Excel</a> --}}
							</div>
						</div>
						{{-- <button class="btn btn-primary" data-toggle="modal" data-target="#createModal" id="create">Rekap Penjualan</button> --}}
					</div>
					<table id="dataTable" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>No</th>
								<th>Toko</th>
								<th>Produk</th>
								<th>Qty</th>
								<th>Total</th>
								<th>Tanggal</th>
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

<!-- Modal -->
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
				{{-- {{method_field('PUT')}} --}}
				<input type="hidden" name="shop_id" id="shop_id" value="{{$id}}" />
				<div class="modal-body row input-daterange">
					{{-- <div class="row "> --}}
						<div class="col-md-6 mb-3">
							<input type="text" name="from_date" id="from_date" class="form-control" placeholder="Dari tanggal" readonly />
						</div>
						<div class="col-md-6 mb-3">
							<input type="text" name="to_date" id="to_date" class="form-control" placeholder="Ke tanggal" readonly />
						</div>
						{{-- </div> --}}
					{{-- </div> --}}


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

@section('footer')

<script>
	$(document).ready(function(){
		$('.input-daterange').datepicker({
			todayBtn:'linked',
			format:'yyyy-mm-dd',
			autoclose:true,
		});
		load_data();
		function load_data(from_date = '', to_date = '')
		{
			$('#dataTable').DataTable({
			"pageLength":5,
			processing:true,
			searching:true,
			order:[[0,'asc']],
			info:false,
			lengthMenu: [[2,3,4,5,-1],[2,3,4,5,"All"]],
			serverside:true,
			ordering:false,
			ajax:
				{
					url:"{{url('getrekaptoko/'.$id)}}",
					data:{from_date:from_date, to_date:to_date}
				},
			columns: [
			{data: 'rownum', name: 'rownum'},
			{data: 'shop_id', name: 'shop_id'},
			{data: 'product_id', name: 'product_id'},
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
			$('#dataTable').DataTable().destroy();
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
			$('#dataTable').DataTable().destroy();
			load_data();
		});

		// console.log(data);


	});
</script>

@stop
