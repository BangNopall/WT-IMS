@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')

<div class="p-5">
    <h1>{{ $editteks }}</h1>
    
    <form id="form-item" method="POST" action="{{ route('barang.store') }}" class="form-horizontal" data-toggle="validator"
        enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <input type="hidden" id="id" name="id" >
            <div class="box-body">
                <div class="form-group">
                    <label>Barang</label>
                    <input type="text" class="form-control" id="nama" name="nama" autofocus required>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="form-group">
                    <label>Foto</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    {!! Form::select('category_id', $category, null, [
                        'class' => 'form-control select',
                        'placeholder' => '-- Pilih kategori --',
                        'id' => 'category_id',
                        'required',
                    ]) !!}
                    <span class="help-block with-errors"></span>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <div class="modal-footer">
            <a href="/barang" class="btn btn-danger pull-left" data-dismiss="modal">Batal</a>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
</div>

@endsection