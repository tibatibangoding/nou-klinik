@extends('layouts.dashboard')

@section('title')
dokter
@endsection

@section('content')

<head>
  <style>
  .btn-custom {
    color: #ffffff;
    background-color: #a979a8;
  }

  .btn-custom:hover,
  .btn-custom:focus,
  .btn-custom:active,
  .btn-custom.active,
  .open .dropdown-toggle.btn-custom {
    color: #ffffff;
    background-color: #6c5576;
  }
  </style>
</head>
<!-- Page Heading -->
<div class="" style="width: 100%;">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dokter</h1>
    <a href="{{ route('dokter.create') }}" class="btn text-white" style="background-color: #a979a8;"
      onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';">Tambah
      Data</a>
  </div>

  <!-- Content Row -->
  <!-- <div class="container"> -->
  <div class="table-responsive">
    <table class="table table-bordered  p-2 rounded" id="dokter"
      style="width: 100%; background-color: #fff; border: none;">
      <thead>
        <tr>
          <th>Nama Dokter</th>
          <th>Spesialis</th>
          <th>Poli</th>
          <th>Biaya Dokter</th>
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
    url: "{{ route('dataTablesDokter') }}",
    type: "GET",
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#dokter').DataTable({
        "data": data.dokter,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "nama",
          },
          {
            "data": "spesialis",
          },
          {
            "data": "poli.nama_poli",
          },
          {
            "data": "biaya_dokter",
            "render": function(data, type, row, meta) {
              const num = row.biaya_dokter
              return 'Rp. ' + Number(num).toLocaleString(3);
            }
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="dokter/' + row.id + '/edit" class="btn btn-custom text-center">Edit</a>' +
                '<form action="dokter/' + row.id +
                '" method="POST" class="d-inline">@csrf @method("DELETE") <button class="btn btn-outline-secondary"> Hapus </button></form>'
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