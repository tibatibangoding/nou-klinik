@extends('layouts.dashboard')

@section('title')
    tindakan
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">tindakan</h1>
    <a href="{{ route('tindakan.create') }}" class="btn btn-primary">Tambah Data</a>


    {{-- <a href="{{ route('tindakan.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>

<!-- Content Row -->
<div class="container">
    <div class="mb-4 form-group">
        <form action="{{ route('tindakan.import') }}" enctype="multipart/form-data" class="d-flex" method="POST">
            @csrf
            <input type="file" style="width: 70%" name="excel_file" class="form-control">
            <button class="btn btn-primary d-inline" type="submit">Submit</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="tindakan" style="width: 100%">
            <thead>
                <tr>
                    <th>Nama tindakan</th>
                    <th>Biaya tindakan</th>
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
            url: "{{ route('dataTablesTindakan') }}",
            type: "GET",
            dataType: "json",
            success: function(data) {
                // Datatables
                var i = 1;
                $('#tindakan').DataTable({
                    "data": data.tindakan,
                    // responsive
                    "responsive": true,
                    "columns": [
                        {
                            "data": "nama_tindakan",
                        },
                        {
                            "data": "biaya_tindakan",
                            "render": function(data, type, row, meta) {
                                const num = row.biaya_tindakan
                                return 'Rp. ' + Number(num).toLocaleString(3);
                            }
                        },
                        {
                            "data": 'button',
                            "render": function (data, type, row, meta) {
                                return '<a href="tindakan/'+row.id+'/edit" class="btn btn-primary text-center">Edit</a>' + '<form action="tindakan/'+row.id+'" method="POST" class="d-inline">@csrf @method("DELETE") <button class="btn btn-danger"> Hapus </button></form>'
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
