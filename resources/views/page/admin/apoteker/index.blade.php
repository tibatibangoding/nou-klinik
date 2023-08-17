@extends('layouts.dashboard')

@section('title')
apoteker
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
  <h1 class="h3 mb-0 text-gray-800">Apoteker</h1>
  <a href="{{ route('apoteker.create') }}" class="btn mr-3 text-white" style="background-color: #a979a8;"
    onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';">Tambah
    Data</a>
</div>

<!-- Content Row -->
<!-- <div class="container"> -->
<div class="table-responsive">
  <table class="table table-bordered" id="apoteker" style="width: 100%">
    <thead>
      <tr>
        <th class="col-1">No</th>
        <th>Nama apoteker</th>
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
    url: "{{ route('dataTablesApoteker') }}",
    type: "GET",
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#apoteker').DataTable({
        "data": data.apoteker,
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
            "data": "nama",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="apoteker/' + row.id +
                '/edit" class="btn btn-custom text-center">Edit</a>' + '<form action="apoteker/' + row
                .id +
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