{{-- @dd($data); --}}
<div class="row">
    <div class="col-lg-7 col-md-7 col-sm-12">
        <div class="card">
            <div class="form-group mb-0">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="border: 0px !important"><i class="fas fa-search"></i></span>
                    </div>
                    <input class="form-control" wire:model="search" placeholder="Masukkan nama produk" type="text" style="border: 0px !important">
                </div>
            </div>
        </div>
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                {{-- <h3>Data Produk</h3> --}}
            </div>
            <!-- Light table -->
            <div class="table-responsive" style="height: 840px; overflow-y: auto;">
                <table class="table align-items-center table-flush table-hover" >
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($data as $item)
                        <tr style="cursor: pointer;" wire:click="selectThis('{{$item->id}}', '{{$id_shop}}', '{{$code}}')">
                            <th scope="row">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm">{{$item->product_name}} - {{$item->warna}}</span>
                                    </div>
                                </div>
                            </th>
                            <td class="budget">
                                {{number_format($item->price)}}
                            </td>
                            <td class="budget">
                                {{number_format($item->temp_stock)}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5 col-md-5 col-sm-12">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Pembelian</h3>
            </div>
            <div class="text-center" wire:loading wire:target="selectThis">
                <img src="{{asset('assets/img/loading_inline.gif')}}" alt="loading">
            </div>
            <!-- Light table -->
            <div class="table-responsive" wire:loading.remove wire:target="selectThis">
                <table class="table table-flush" style="height: 500px; overflow-y: auto; display: block">
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($temp_order as $value)
                        <tr>
                            <td>
                                <strong class="name mb-0 text-sm">{{$value->product_name}} - {{$value->warna}}</strong>
                                <p>Rp{{number_format($value->price)}}</p>
                                <div>
                                    <span wire:click="plusQty('{{$value->id}}')" style="cursor: pointer" class="btn btn-sm btn-primary m-0">+</span> <strong class="m-1">{{$value->qty}}</strong> <span class="btn btn-sm btn-primary m-0" style="cursor: pointer" wire:click="minusQty('{{$value->id}}')">-</span>
                                </div>
                            </td>
                        </tr>
                        @php
                            $total += $value->price * $value->qty;
                        @endphp
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Pembayaran</h3>
            </div>
            
            <div class="card-body">
                <form action="">
                    <div class="form-group">
                        <label for="">Total Belanja</label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</i></span>
                            </div>
                            <input class="form-control" type="text" value="{{number_format($total)}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Bayar</label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</i></span>
                            </div>
                            <input class="form-control" type="text">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <button class="btn btn-primary" type="submit">Bayar</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>