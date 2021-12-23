@extends('layouts.master')

@section('content')

<section class="content">
    <div class="row justify-content-center mt-3">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark">Daftar Kategori</h4>
                </div>
                
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="ml-auto mb-3">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal" id="create">Tambah Kategori</button>
                    </div>
                    <div class="table-responsive">
                    <table id="example2" class="dataTable table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        
                    </table>
                    </div>
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
            <form id="addForm" action="{{route('categories.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="modal-body">
                    {{-- <input type="hidden" name="user_id" id="user_id" value=""> --}}
                    <div class="form-group">
                        <label for="status" class="col-form-label">Nama Kategori:</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        
                        @error('name') <div class="invalid-feedback">{{$message}}</div> @enderror
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
                <h5 class="modal-title" id="modal-title">Edit Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="{{route('categories.update', 'id')}}" method="POST" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="form-group">
                        <label for="status" class="col-form-label">Nama Produk:</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        
                        @error('name') <div class="invalid-feedback">{{$message}}</div> @enderror
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
                <h5 class="modal-title" id="modal-title">Hapus Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('categories.destroy', 'id')}}" method="POST" enctype="multipart/form-data">
                @csrf
                {{method_field('DELETE')}}
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    Apakah anda yakin akan menghapus kategori ini ?
                    
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
    $('#addForm').validate({
        rules: {
            name: {
                required: true
            }
        }
    });

    $('#editForm').validate({
        rules: {
            name: {
                required: true
            }
        }
    });
</script>

<script>
    $(document).ready(function(){
        var table = $('.dataTable').DataTable({
            "pageLength":5,
            processing:true,
            searching:true,
            order:[[0,'asc']],
            info:false,
            lengthMenu: [[2,3,4,5,-1],[2,3,4,5,"All"]],
            serverside:true,
            ordering:false,
            ajax:"{{route('ajax.get.categories')}}",
            columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action'}
            ]
            
        });
        
        
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) 
            var id = button.data('id')
            var nama = button.data('nama')
            
            var modal = $(this)
            modal.find('.modal-body #id').val(id)
            modal.find('.modal-body #name').val(nama)
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