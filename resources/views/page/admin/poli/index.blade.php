@extends('layouts.dashboard')

@section('title')
poli
@endsection

@section('content')
<!-- Page Heading -->


<!-- Content Row -->
<!-- <div class="container"> -->

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

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">poli</h1>
  <a href="{{ route('poli.create') }}" class="btn mr-3 text-white" style="background-color: #a979a8;"
    onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';">Tambah
    Data</a>

  {{-- <a href="{{ route('poli.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>
<div class="table-responsive">
  <table class="table table-bordered  p-2 rounded" id="poli" style="width: 100%; background-color: #fff; border: none;">
    <thead class="">
      <tr>
        <th class="col-1">No</th>
        <th>Nama Poli</th>
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
    url: "{{ route('dataTablesPoli') }}",
    type: "GET",
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#poli').DataTable({
        "data": data.poli,
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
            "data": "nama_poli",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="poli/' + row.id +
                '/edit" class="btn text-center btn-custom" >Edit</a>' +
                '<form action="poli/' + row.id +
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