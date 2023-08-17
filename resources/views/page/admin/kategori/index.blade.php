@extends('layouts.dashboard')

@section('title')
kategori
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
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Kategori</h1>
  <a href="{{ route('kategori.create') }}" class="btn mr-3 text-white" style="background-color: #a979a8;"
    onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';">Tambah
    Data</a>


  {{-- <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>

<!-- Content Row -->
<!-- <div class="container"> -->
<div class="table-responsive">
  <table class="table table-bordered" id="kategori" style="width: 100%">
    <thead>
      <tr>
        <th class="col-1">No</th>
        <th>Nama kategori obat</th>
        <th class="text-center col-2">Aksi</th>
      </tr>
    </thead>
  </table>
</div>
<!-- </div> -->
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
        "columns": [{
            "data": null,
            "className": "text-center",
            "render": function(data, type, row, meta) {
              return meta.row + 1;
            }
          },
          {
            "data": "nama_kategori",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="kategori/' + row.id +
                '/edit" class="btn btn-custom text-center">Edit</a>' + '<form action="jenis/' + row.id +
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