@extends('layouts.dashboard')

@section('title')
dokter
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">dokter</h1>
  <a href="{{ route('dokter.create') }}" class="btn btn-primary">Tambah Data</a>
</div>

<!-- Content Row -->
<div class="container">
  <div class="table-responsive">
    <table class="table table-bordered" id="dokter" style="width:100%">
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
              return '<a href="dokter/' + row.id + '/edit" class="btn btn-primary text-center">Edit</a>' +
                '<form action="dokter/' + row.id +
                '" method="POST" class="d-inline">@csrf @method("DELETE") <button class="btn btn-danger"> Hapus </button></form>'
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