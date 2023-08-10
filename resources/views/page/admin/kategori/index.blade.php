@extends('layouts.dashboard')

@section('title')
    kategori
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">kategori</h1>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Data</a>
    

    {{-- <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>

<!-- Content Row -->
<div class="container">
    <div class="table-responsive">
        <table class="table table-bordered" id="kategori" style="width: 100%">
            <thead>
                <tr>
                    <th>Nama kategori obat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function fetch(start_date, end_date) {
        $.ajax({
            url: "{{ route('dataTablesKategori') }}",
            type: "GET",
            dataType: "json",
            success: function(data) {
                // Datatables
                var i = 1;
                $('#kategori').DataTable({
                    "data": data.kategori,
                    // responsive
                    "responsive": true,
                    "columns": [
                        {
                            "data": "nama_kategori",
                        },
                        {
                            "data": 'button',
                            "render": function (data, type, row, meta) {
                                return '<a href="kategori/'+row.id+'/edit" class="btn btn-primary text-center">Edit</a>' + '<form action="jenis/'+row.id+'" method="POST" class="d-inline">@csrf @method("DELETE") <button class="btn btn-danger"> Hapus </button></form>'
                            }
                        }
                    ]
                });
            }
        });
    }
    fetch();
    </script>
@endpush
