@extends('layouts.master')

@section('content')

<section class="content">
    <div class="row justify-content-center mt-3">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Produk</h3>
                </div>


                <!-- /.card-header -->
                <div class="card-body">
                    <div class="mb-3">
                    <div class="row">
							<div class="col-md-4 mb-3">
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
							<div class="col-md-4 mb-3">
								<button type="filter" name="filter" id="filter" class="btn btn-primary">Filter</button>
								<button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
							</div>

						</div>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal" id="create">Tambah Produk</button>
                        <a class="btn btn-info ml-auto" href="{{url('rekapStock')}}">Rekap Stock</a>
                    </div>
                                        
                    <table id="dataTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Diskon</th>
                                <th>Harga total</th>
                                <th>Stock</th>
                                <th>Stock Tersedia</th>
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
                <h5 class="modal-title" id="modal-title">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="tambahForm" name="tambahForm" action="{{route('product.store')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    {{-- <input type="hidden" name="user_id" id="user_id" value=""> --}}
                    <div class="form-group">
                        <label for="status" class="col-form-label">Kategori:</label>
                        <div class="input-group mb-3">
                            <select class="custom-select @error('category') is-invalid @enderror" name="category" id="category">
                                {{-- <option selected>Choose...</option> --}}
                                @foreach ($category as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('category') <div class="invalid-feedback">{{$message}}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Kode Produk:</label>
                        <input type="text" name="kode" id="kode" class="form-control @error('kode')
                        is-invalid @enderror">
                        @error('kode') <div class="invalid-feedback">{{$message}}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Nama Produk:</label>
                        <input type="text" name="nama" id="nama" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Warna Produk:</label>
                        <input type="text" name="warna" id="warna" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Harga Jual:</label>
                        <input type="number" name="harga" id="harga" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Harga Beli:</label>
                        <input type="number" name="harga_beli" id="harga_beli" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Stock Produk:</label>
                        <input type="number" name="stock" id="stock" class="form-control">
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
                <h5 class="modal-title" id="modal-title">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="{{route('product.update', 'id')}}" method="POST" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="form-group">
                        <label for="status" class="col-form-label">Nama Produk:</label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror">

                        @error('nama') <div class="invalid-feedback">{{$message}}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Kode Produk:</label>
                        <input type="text" name="kode" id="kode" class="form-control @error('harga') is-invalid @enderror">
                        @error('harga') <div class="invalid-feedback">{{$message}}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Harga Jual:</label>
                        <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror">
                        @error('harga') <div class="invalid-feedback">{{$message}}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Harga Beli:</label>
                        <input type="number" name="harga_beli" id="harga_beli" class="form-control @error('harga') is-invalid @enderror">
                        @error('harga') <div class="invalid-feedback">{{$message}}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Diskon (dalam %):</label>
                        <input type="number" name="diskon" id="diskon" class="form-control @error('diskon') is-invalid @enderror">
                        @error('diskon') <div class="invalid-feedback">{{$message}}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Stock Produk:</label>
                        <input id="stock" disabled class="form-control">
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

<!-- Edit Modal -->
<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Hapus Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('product.destroy', 'id')}}" method="POST" enctype="multipart/form-data">
                @csrf
                {{method_field('DELETE')}}
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    Apakah anda yakin akan menghapus produk ini ?

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="save-btn">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    $('#tambahForm').validate({
        rules: {
            category: {
                required: true
            },
            kode: {
                required: true
            },
            nama: {
                required: true
            },
            warna: {
                required: true
            },
            harga: {
                required: true,
                digits: true
            },
            harga_beli: {
                required: true,
                digits: true,
            },
            stock: {
                required: true,
                digits: true
            }
        }
    });
</script>

<script>
    $('#editForm').validate({
        rules: {
            category: {
                required: true
            },
            kode: {
                required: true
            },
            nama: {
                required: true
            },
            warna: {
                required: true
            },
            harga: {
                required: true,
                digits: true
            },
            harga_beli: {
                required: true,
                digits: true,
            },
            stock: {
                required: true,
                digits: true
            },
            diskon: {
                required: true,
                digits: true
            }

        }
    });
</script>

<script>
    $(document).ready(function(){
        load_data();
        function load_data(kategori = ''){
        $('#dataTable').DataTable({
            "pageLength":5,
            processing:true,
            searching:true,
            order:[[0,'asc']],
            info:false,
            lengthMenu: [[5,10,15,20,-1],[5,10,15,20,"All"]],
            serverside:true,
            ordering:true,

            ajax:
				{
					url:"{{route('ajax.get.produk')}}",
					data:{kategori:kategori}
				},
            columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'product_name', name: 'product_name'},
            {data: 'harga_beli', name: 'harga_beli'},
            {data: 'price', name: 'price'},
            {data: 'diskon', name: 'diskon'},
            {data: 'final_price', name: 'final_price'},
            {data: 'stock', name: 'stock'},
            {data: 'temp_stock', name: 'temp_stock'},
            {data: 'action', name: 'action'}
            ]

        });
    }
    $('#filter').click(function(){
			var kategori = $('#kategori').val();
			if(kategori != '')
			{
			$('#dataTable').DataTable().destroy();
			load_data(kategori);
			}
			else
			{
			alert('Tanggal wajib diisi');
			}
		});
		$('#refresh').click(function(){
			$('#kategori').val('');
			$('#dataTable').DataTable().destroy();
			load_data();
		});
        // jQuery.validator.setDefaults({
        //     debug: true,
        //     success: "valid"
        // });


        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var nama = button.data('nama')
            var harga = button.data('harga')
            var harga_beli = button.data('harga_beli')
            var diskon = button.data('diskon')
            var stock = button.data('stock')
            var kode = button.data('kode')

            var modal = $(this)
            modal.find('.modal-body #id').val(id)
            modal.find('.modal-body #nama').val(nama)
            modal.find('.modal-body #harga').val(harga)
            modal.find('.modal-body #harga_beli').val(harga_beli)
            modal.find('.modal-body #diskon').val(diskon)
            modal.find('.modal-body #stock').val(stock)
            modal.find('.modal-body #kode').val(kode)
        });

        $('#hapusModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')

            var modal = $(this)
            modal.find('.modal-body #id').val(id)

        });



    });
</script>


@stop
