@extends('layouts.dashboard')

@section('title')
    jenis
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">jenis</h1>
    <a href="{{ route('jenis.create') }}" class="btn btn-primary">Tambah Data</a>
    

    {{-- <a href="{{ route('jenis.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>

<!-- Content Row -->
<div class="container">
    <div class="table-responsive">
        <table class="table table-bordered" id="jenis" style="width: 100%">
            <thead>
                <tr>
                    <th>Nama jenis obat</th>
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
            url: "{{ route('dataTablesJenis') }}",
            type: "GET",
            dataType: "json",
            success: function(data) {
                // Datatables
                var i = 1;
                $('#jenis').DataTable({
                    "data": data.jenis,
                    // responsive
                    "responsive": true,
                    "columns": [
                        {
                            "data": "nama_jenis",
                        },
                        {
                            "data": 'button',
                            "render": function (data, type, row, meta) {
                                return '<a href="jenis/'+row.id+'/edit" class="btn btn-primary text-center">Edit</a>' + '<form action="jenis/'+row.id+'" method="POST" class="d-inline">@csrf @method("DELETE") <button class="btn btn-danger"> Hapus </button></form>'
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
