@extends('layouts.master')


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
    var printer = new Recta('1128899913', '1811')
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

@section('content')

<!-- Main content -->
<section class="content mt-5">
    <div class="container">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{count(\DB::table('products')->get()->all())}}</h3>
                        
                        <p>Total Produk</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{route('product.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{count($shop)}}</h3>
                        
                        <p>Total Toko</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{route('shop.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{count($pegawai)}}</h3>
                        
                        <p>Total Pegawai</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="{{route('users.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{count($rekap)}}</h3>
                        
                        <p>Transaksi</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{url('rekap')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        {{-- <section class="col-lg-12 connectedSortable">
            
            <!-- solid sales graph -->
            <div class="card bg-gradient-info">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-th mr-1"></i>
                        Sales Graph
                    </h3>
                    
                </div>
                <div class="card-body">
                    <canvas class="chart" id="line-chart" style="height: 250px;"></canvas>
                </div>
            </div>
        </section> --}}
        
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-0">
                        <div class="sticky-top mb-3">
                            {{-- <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Draggable Events</h4>
                                </div>
                                <div class="card-body">
                                    <!-- the events -->
                                    <div id="external-events">
                                        <div class="external-event bg-success">Lunch</div>
                                        <div class="external-event bg-warning">Go home</div>
                                        <div class="external-event bg-info">Do homework</div>
                                        <div class="external-event bg-primary">Work on UI design</div>
                                        <div class="external-event bg-danger">Sleep tight</div>
                                        <div class="checkbox">
                                            <label for="drop-remove">
                                                <input type="checkbox" id="drop-remove">
                                                remove after drop
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div> --}}
                            <!-- /.card -->
                            {{-- <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Create Event</h3>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                        <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                                        <ul class="fc-color-picker" id="color-chooser">
                                            <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                                            <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                                            <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                                            <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                                            <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="input-group">
                                        <input id="new-event" type="text" class="form-control" placeholder="Event Title">
                                        
                                        <div class="input-group-append">
                                            <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                                        </div>
                                        <!-- /btn-group -->
                                    </div>
                                    <!-- /input-group -->
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-body p-0">
                                <!-- THE CALENDAR -->
                                <div id="calendar"></div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        
        
    </div>
</section>
{{-- </div> --}}
@endsection
@section('footer')
<script>
    $(function () {
        /* initialize the external events
        -----------------------------------------------------------------*/
        function ini_events(ele) {
            ele.each(function () {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                }
                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject)
                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex        : 1070,
                    revert        : true, // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                })
            })
        }
        ini_events($('#external-events div.external-event'))
        /* initialize the calendar
        -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendarInteraction.Draggable;
        var containerEl = document.getElementById('external-events');
        var checkbox = document.getElementById('drop-remove');
        var calendarEl = document.getElementById('calendar');
        
        var calendar = new Calendar(calendarEl, {
            plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
            
        });
        calendar.render();
        // $('#calendar').fullCalendar()
        /* ADDING EVENTS */
        var currColor = '#3c8dbc' //Red by default
        //Color chooser button
        var colorChooser = $('#color-chooser-btn')
        $('#color-chooser > li > a').click(function (e) {
            e.preventDefault()
            //Save color
            currColor = $(this).css('color')
            //Add color effect to button
            $('#add-new-event').css({
                'background-color': currColor,
                'border-color'    : currColor
            })
        })
        $('#add-new-event').click(function (e) {
            e.preventDefault()
            //Get value and make sure it is not null
            var val = $('#new-event').val()
            if (val.length == 0) {
                return
            }
            //Create events
            var event = $('<div />')
            event.css({
                'background-color': currColor,
                'border-color'    : currColor,
                'color'           : '#fff'
            }).addClass('external-event')
            event.html(val)
            $('#external-events').prepend(event)
            //Add draggable funtionality
            ini_events(event)
            //Remove event from text input
            $('#new-event').val('')
        })
    })
</script>
@endsection

@endif
