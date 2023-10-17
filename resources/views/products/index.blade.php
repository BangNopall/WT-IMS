@extends('layouts.master')


@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box box-success">

        <div class="box-header">
            <h3 class="box-title">List Semua Barang</h3>

            <a href="/barang/create" class="btn btn-success pull-right" style="margin-top: -8px;"><i class="fa fa-plus"></i>
                Tambah Barang</a>
        </div>


        <!-- /.box-header -->
        <div class="box-body">
            <table id="products-table" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Qty.</th>
                        <th>Foto</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('products.form2')
@endsection

@section('bot')
    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{-- <script> --}}
    {{-- $(function () { --}}
    {{-- $('#items-table').DataTable() --}}
    {{-- $('#example2').DataTable({ --}}
    {{-- 'paging'      : true, --}}
    {{-- 'lengthChange': false, --}}
    {{-- 'searching'   : false, --}}
    {{-- 'ordering'    : true, --}}
    {{-- 'info'        : true, --}}
    {{-- 'autoWidth'   : false --}}
    {{-- }) --}}
    {{-- }) --}}
    {{-- </script> --}}

    <script type="text/javascript">
        var table = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.barang') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'qty',
                    name: 'qty'
                },
                {
                    data: 'show_photo',
                    name: 'show_photo'
                },
                {
                    data: 'category_name',
                    name: 'category_name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Add Barang');
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('barang') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Barang');

                    $('#id').val(data.id);
                    $('#nama').val(data.nama);
                    $('#category_id').val(data.category_id);
                },
                error: function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function() {
                $.ajax({
                    url: "{{ url('barang') }}" + '/' + id,
                    type: "POST",
                    data: {
                        '_method': 'DELETE',
                        '_token': csrf_token
                    },
                    success: function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error: function() {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        $(function() {
            $('#modal-form form').validator().on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    var id = $('#id').val();
                    if (save_method == 'add') {
                        url = "{{ url('barang') }}";

                        $.ajax({
                            url: url,
                            type: "POST",
                            data: new FormData($("#modal-form form")[0]),
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                $('#modal-form').modal('hide');
                                table.ajax.reload();
                                swal({
                                    title: 'Success!',
                                    text: data.message,
                                    type: 'success',
                                    timer: 1500
                                });
                            },
                            error: function (xhr, status, error) {
                                $('#modal-form').modal('hide');
                                table.ajax.reload();
                                var errorMessage = xhr.status + ': ' + xhr.statusText;
                                
                                // Cek jika ada pesan kesalahan lebih rinci dalam respons JSON
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage += '<br>' + xhr.responseJSON.message;
                                }
                            
                                swal({
                                    title: 'Oops...',
                                    html: errorMessage, // Menggunakan 'html' untuk menginterpretasikan tag HTML dalam pesan
                                    type: 'error',
                                });
                            }
                        });
                    } else {
                        url = "{{ url('barang') . '/' }}" + id;

                        $.ajax({
                            url: url,
                            type: "POST",
                            data: new FormData($("#modal-form form")[0]),
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                $('#modal-form').modal('hide');
                                table.ajax.reload();
                                swal({
                                    title: 'Success!',
                                    text: data.message,
                                    type: 'success',
                                    timer: 1500
                                });
                            },
                            error: function (xhr, status, error) {
                                $('#modal-form').modal('hide');
                                table.ajax.reload();
                                var errorMessage = xhr.status + ': ' + xhr.statusText;
                                
                                // Cek jika ada pesan kesalahan lebih rinci dalam respons JSON
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage += '<br>' + xhr.responseJSON.message;
                                }
                            
                                swal({
                                    title: 'Oops...',
                                    html: errorMessage, // Menggunakan 'html' untuk menginterpretasikan tag HTML dalam pesan
                                    type: 'error',
                                });
                            }
                        });
                    }
                    return false;
                }
            });
        });
    </script>
@endsection
