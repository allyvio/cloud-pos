{{-- @extends('layouts.master') --}}


@if (Auth::user()->hasAnyRole('kasir'))

@section('content')


<!-- DataTales Example -->
@php
$total = 0;
@endphp
<div class="row justify-content-center">
    <div class="col-12 col-md-5 card shadow m-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th style="width: 250px">Nama</th>
                            <th>Harga</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5 md-4 card shadow m-3">
        <div class="card-body">
            <h2 class="text-center mb-5 mt-3"><b><i class="total"></i></b></h2>
            
            <form action="{{url('submit/'.$code)}}" method="POST">
                
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h5>Nama</h5>
                                
                                <input type="text" name="nama_produk" class="form-control" id="nama_produk" aria-describedby="emailHelp" readonly>
                            </div>
                            
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h5>Harga</h5>
                                
                                <input type="number" name="harga_produk" class="form-control" id="harga_produk" aria-describedby="emailHelp" readonly>
                            </div>
                            
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h5>Jumlah</h5>
                                
                                {{-- <input type="number" min="1" name="qty" class="form-control" id="qty" aria-describedby="emailHelp"> --}}
                                <input type="number" name="qty" id="qty" class="form-control @error('qty') is-invalid @enderror">
                                @error('qty') <div class="invalid-feedback">{{$message}}</div> @enderror
                            </div>
                            
                            
                        </div>
                    </div>
                    <input type="hidden" name="id" value="">
                    <!-- ./col -->
                    
                </div>
                {{-- <div class="order row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" id="nama_produk" aria-describedby="emailHelp" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Harga Produk</label>
                            <input type="number" name="harga_produk" class="form-control" id="harga_produk" aria-describedby="emailHelp" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Jumlah Produk</label>
                            <input type="jumlah_produk" name="qty" class="form-control" id="qty-input" aria-describedby="emailHelp">
                        </div>
                        <input type="hidden" name="id" value="">
                    </div>
                </div> --}}
                <button type="submit" class="submit btn btn-primary">submit</button>
                
            </form>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-5 card shadow m-3 mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="kasir" width="100%" cellspacing="0">
                    <thead class="thead">
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Sub Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="produk-item" data-target="thead" data-spy="scroll" data-offset="0">
                        
                        @php
                        $data = \DB::table('orders')->where('user_id' , Auth::user()->id)->get();
                        @endphp
                        
                        @foreach ($data as $index=>$item)
                        <tr>
                            <td>{{ $index+1}}</td>
                            <td>{{ \DB::table('products')->where('id', $item->product_id)->value('product_name') . '-' . \DB::table('products')->where('id', $item->product_id)->value('warna')}}</td>
                            <td>{{ $item->qty}}</td>
                            @php
                            $hrg = \DB::table('products')->where('id', $item->product_id)->value('final_price');
                            $qty = $item->qty;
                            $sub = $hrg * $qty;
                            $total += $sub;
                            @endphp
                            <td>{{ number_format($sub,0)}}</td>
                            <td><a href="{{ url('hapus-item/'.$item->id.'/'.$code) }}" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5 md-4 card mt-3 m-3 shadow mb-4">
        <div class="card-body">
            
            <form id="form" method="POST">
                @csrf
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Total Harga</label>
                        <input type="text" name="total" class="form-control" value="{{ number_format($total,0)}}" aria-describedby="emailHelp" readonly>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Bayar (Rp)</label>
                        <input type="number" name="bayar" class="form-control" id="nama_produk" aria-describedby="emailHelp" placeholder="Bayar">
                    </div>
                </div>
                <button class="selesai btn btn-primary" onclick="submitForm('{{ url('selesai/'.$code.'/'.$total)}}')">selesai</button>
            </form>
            
        </div>
    </div>
</div>


<div class="modal modal-danger fade" id="modal-kembalian">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h1><b><i class="kembalian"></i></b></h1>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- End of Main Content -->


<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<script src="{{asset('js/recta.js')}}"></script>
<script>
    var printer = new Recta('1128899913', '1811');
    {!! Session::get('script') !!}
</script>
@if (session()->has('script1'))
@foreach (session()->get('script1') as $item)
<script>
    {!! $item !!}
</script>
@endforeach
@endif
<script>
    {!! Session::get('script2') !!}
</script>


@stop
@section('footer')
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script>
    
    $(document).ready(function() {
        var flash = "{{ Session::has('pesan')}}";
        if (flash) {
            var pesan = "{{Session::get('pesan')}}";
            $('.kembalian').text(pesan);
            $('#modal-kembalian').modal();
        }
        
        $('.dataTable').DataTable({
            // console.log(.dataTable);
            
            "pageLength": 5,
            processing: true,
            searching: true,
            order: [
            [0, 'asc']
            ],
            info: false,
            lengthMenu: [
            [2, 3, 4, 5, -1],
            [2, 3, 4, 5, "All"]
            ],
            serverside: true,
            ordering: false,
            ajax: "{{route('ajax.get.produk.kasir')}}",
            columns: [{
                data: 'rownum',
                name: 'rownum'
            },
            {
                data: 'product_id',
                name: 'product_id'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'temp_stock',
                name: 'temp_stock'
            },
            ]
            // console.log(x);
            
        });
        
        $('body').on('click', '.btn-barang', function(e) {
            e.preventDefault();
            $(this).closest('tr').find('.loading').show();
            var id = $(this).attr('id');
            var url = "{{ url('get') }}" + '/' + id;
            var _this = $(this);
            
            $.ajax({
                type: 'get',
                url: url,
                success: function(data) {
                    console.log(data);
                    
                    $("input[name='nama_produk']").val(data.product_name);
                    $("input[name='harga_produk']").val(data.price);
                    $("input[name='id']").val(data.id);
                    
                    _this.closest('tr').find('.loading').hide();
                }
            });
        });
        
        $('.submit').click(function(e) {
            e.preventDefault();
            var nama = $("input[name='nama_produk']").val();
            if (nama == '') {
                alert('Barang harus dipilih dahulu');
            } else {
                $(this).addClass('disbled');
                $(this).closest('form').submit();
            }
        });
        
        var total = "{{ 'Rp. ' .number_format($total, 0)}}";
        $('.total').text(total);
        
        
        
        
    });
</script>
<script type="text/javascript">
    function submitForm(action) {
        form = document.getElementById('form');
        form.action = action;
        form.submit();  
    }
</script>

@stop

@elseif(Auth::user()->hasAnyRole('admin'))

@include('admin.index')

@endif
