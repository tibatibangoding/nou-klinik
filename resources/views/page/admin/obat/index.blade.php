@extends('layouts.dashboard')

@section('title')
    obat
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">obat</h1>
    <a href="{{ route('obat.create') }}" class="btn btn-primary">Tambah Data</a>


    {{-- <a href="{{ route('obat.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>

<!-- Content Row -->
<div class="container">
    <div class="col-md-6 mt-3">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
            </div>
            <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly>
        </div>
    </div>
    <div class="col-md-6">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
            </div>
            <input type="text" class="form-control" id="end_date" placeholder="End Date" readonly>
        </div>
    </div>
<div class="mb-4">
    <button id="filter" class="btn btn-primary">Filter</button>
    <button id="reset" class="btn btn-warning">Reset</button>
</div>
    <div class="mb-4 form-group">
        <form action="{{ route('obat.import') }}" enctype="multipart/form-data" class="d-flex" method="POST">
            @csrf
            <input type="file" style="width: 70%" name="excel_file" class="form-control">
            <button class="btn btn-primary d-inline" type="submit">Submit</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="obat" style="width: 100%">
            <thead>
                <tr>
                    <th>Nama obat</th>
                    <th>Jenis obat</th>
                    <th>Harga obat</th>
                    <th>Kategori obat</th>
                    <th>Tanggal obat masuk</th>
                    <th>Stok obat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $("#start_date").datepicker({
                "dateFormat": "yy-mm-dd"
            });
            $("#end_date").datepicker({
                "dateFormat": "yy-mm-dd"
            });
        });
        function fetch(start_date, end_date) {
        $.ajax({
            url: "{{ route('dataTablesObat') }}",
            type: "GET",
            data: {
                start_date: start_date,
                end_date: end_date
            },
            dataType: "json",
            success: function(data) {
                // Datatables
                var i = 1;
                $('#obat').DataTable({
                    "data": data.obat,
                    // responsive
                    "responsive": true,
                    "columns": [
                        {
                            "data": "nama_obat",
                        },
                        {
                            "data": "jenis.nama_jenis",
                            "render": function(data, type, row, meta) {
                                if(row.id_jenis_obat == null) {
                                    return 'tidak ada nama jenis'
                                } else {
                                    return row.jenis.nama_jenis
                                }
                            }
                        },
                        {
                            "data": "harga_obat",
                            "render": function(data, type, row, meta) {
                                const num = row.harga_jual
                                return 'Rp. ' + Number(num).toLocaleString(3);
                            }
                        },
                        {
                            "data": "kategori.nama_kategori",
                            "render": function(data, type, row, meta) {
                                if(row.id_kategori != null) {
                                    return row.kategori.nama_kategori
                                } else {
                                    return 'tidak ada nama kategori'
                                }
                            }
                        },
                        {
                            "data": "updated_at",
                            "render": function(data, type, row, meta) {
                                return moment(row.updated_at).format('DD-MM-YYYY');
                            }
                        },
                        {
                            "data": "stok",
                        },
                        {
                            "data": 'button',
                            "render": function (data, type, row, meta) {
                                return '<a href="obat/'+row.id+'/edit" class="btn btn-primary text-center">Edit</a>' + '<form action="obat/'+row.id+'" method="POST" class="d-inline">@csrf @method("DELETE") <button class="btn btn-danger"> Hapus </button></form>'
                            }
                        }
                    ]
                });
            }
        });
    }
    fetch();
    $(document).on("click", "#filter", function(e) {
        e.preventDefault();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        if (start_date == "" || end_date == "") {
            alert("Both date required");
        } else {
            $('#obat').DataTable().destroy();
            fetch(start_date, end_date);
        }
    });
    // Reset
    $(document).on("click", "#reset", function(e) {
        e.preventDefault();
        $("#start_date").val(''); // empty value
        $("#end_date").val('');
        $('#obat').DataTable().destroy();
        fetch();
    });
    </script>
@endpush
