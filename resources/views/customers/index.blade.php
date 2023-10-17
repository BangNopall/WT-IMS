@extends('layouts.master')


@section('top')
    <!-- DataTables --><!-- Log on to codeastro.com for more projects! -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    {{--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">--}}
    @include('sweet::alert')
@endsection

@section('content')
    <div class="box box-success">

        <div class="box-header">
            <h3 class="box-title">List Semua Anggota</h3>
        </div>

        <div class="box-header">
            <a onclick="addForm()" class="btn btn-success" ><i class="fa fa-plus"></i> Tambah Anggota</a>
            <a href="{{ route('exportPDF.anggotaAll') }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            <a href="{{ route('exportExcel.anggotaAll') }}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>


        <!-- /.box-header -->
        <div class="box-body">
            <table id="customer-table" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama IC</th>
                    <th>Posisi</th>
                    <th>Steam Hex</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('customers.form')

@endsection

@section('bot')

    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>--}}

    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('#items-table').DataTable()--}}
    {{--$('#example2').DataTable({--}}
    {{--'paging'      : true,--}}
    {{--'lengthChange': false,--}}
    {{--'searching'   : false,--}}
    {{--'ordering'    : true,--}}
    {{--'info'        : true,--}}
    {{--'autoWidth'   : false--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}

    <script type="text/javascript">
        var table = $('#customer-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.anggota') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nama', name: 'nama'},
                {data: 'posisi', name: 'posisi'},
                {data: 'steamhex', name: 'steamhex'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Tambah Anggota');
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('anggota') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Customers');

                    $('#id').val(data.id);
                    $('#nama').val(data.nama);
                    $('#posisi').val(data.posisi);
                    $('#steamhex').val(data.steamhex);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('anggota') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
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

        $(function () {
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()) {
                    var id = $('#id').val();
                    if (save_method == 'add'){
                        url = "{{ url('anggota') }}";
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: new FormData($("#modal-form form")[0]),
                            contentType: false,
                            processData: false,
                            success: function (data) {
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
                    else{
                        url = "{{ url('anggota') . '/' }}" + id;  

                        $.ajax({
                            url: url,
                            type: "POST",
                            data: new FormData($("#modal-form form")[0]),
                            contentType: false,
                            processData: false,
                            success: function (data) {
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
                                table.ajax.reload();
                                var errorMessage = xhr.status + ': ' + xhr.statusText;
                                swal({
                                    title: 'Oops...',
                                    text: errorMessage,
                                    type: 'error',
                                    timer: 1500
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
