@extends('layouts.dashboard')

@section('title')
    pendaftaran
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    @if (Auth::user()->roles->pluck('name')[0] == 'dokter')
    <h1 class="h3 mb-0 text-gray-800">daftar antrian</h1>
    @else
    <h1 class="h3 mb-0 text-gray-800">pendaftaran</h1>
    @endif
    @hasrole('admin')
    <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary">Daftarkan Pasien</a>
    @endhasrole

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
    <div>
        <button id="filter" class="btn btn-primary">Filter</button>
        <button id="reset" class="btn btn-warning">Reset</button>
    </div>
    <div class="table-responsive mt-3">
    <table class="table table-bordered" id="records" style="width:100%">
        <thead>
            <tr>
                <th>Nama Pasien</th>
                <th>Waktu Pendaftaran</th>
                <th>Nomor Antrian</th>
                <th>Nama Dokter</th>
                <th>Nama Poli</th>
                <th>Status Periksa</th>
                <th>Aksi</th>
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
    // Fetch records
    function fetch(start_date, end_date) {
        $.ajax({
            url: "{{ route('dataTablesPendaftaranAll') }}",
            type: "GET",
            data: {
                start_date: start_date,
                end_date: end_date
            },
            dataType: "json",
            success: function(data) {
                // Datatables
                var i = 1;
                var role = `{{ Auth::user()->roles->pluck('name')[0] }}`;
                $('#records').DataTable({
                    "data": data.pendaftaran,
                    // responsive
                    "responsive": true,
                    "columns": [
                        {
                            "data": "pasien.nama_pasien",
                        },
                        {
                            "data": "created_at",
                            "render": function(data, type, row, meta) {
                                return moment(row.created_at).format('DD-MM-YYYY');
                            }
                        },
                        {
                            "data": "no_antrian",
                        },
                        {
                            "data": "dokter.nama",
                        },
                        {
                            "data": "poli.nama_poli",
                        },
                        {
                            "data": "status_periksa",
                            "render": function(data, type, row, meta) {
                                switch (row.status_periksa) {
                                    case '1':
                                        return 'Selesai di periksa'
                                        break;
                                    case '2':
                                        return 'Sedang di periksa'
                                        break;
                                    case '3':
                                        return 'Menunggu antrian';
                                        break;
                                    default:
                                        break;
                                }
                            }
                        },
                        {
                            "data": 'button',
                            "render": function (data, type, row, meta) {
                                if (role == 'admin') {
                                    switch (row.status_periksa) {
                                        case '1':
                                            return '<a href="pasien/'+row.id+'" class="btn btn-primary text-center">Detail</a>'
                                            break;
                                        case '2':
                                            return 'Pasien sedang periksa'
                                            break;
                                        case '3':
                                            return 'Pasien dalam antrian'
                                            break;
                                        default:
                                            break;
                                    }
                                } else if(role == 'dokter') {
                                    switch (row.status_periksa) {
                                        case '1':
                                            return '<a href="pasien/'+row.id+'" class="btn btn-primary text-center">Detail</a>'
                                            break;
                                        case '2':
                                            return '<a href="periksa/create/'+row.id+'" class="btn btn-success">Lanjutkan pemeriksaan pasien</a>'
                                            break;
                                        case '3':
                                            return '<a href="periksa/create/'+row.id+'" class="btn btn-success">Periksa Pasien</a>';
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            }
                        }
                    ]
                });
            }
        });
    }
    fetch();
    // Filter
    $(document).on("click", "#filter", function(e) {
        e.preventDefault();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        if (start_date == "" || end_date == "") {
            alert("Both date required");
        } else {
            $('#records').DataTable().destroy();
            fetch(start_date, end_date);
        }
    });
    // Reset
    $(document).on("click", "#reset", function(e) {
        e.preventDefault();
        $("#start_date").val(''); // empty value
        $("#end_date").val('');
        $('#records').DataTable().destroy();
        fetch();
    });
</script>
@endpush