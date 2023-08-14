@extends('layouts.dashboard')

@section('title')
tindakan
@endsection

@section('content')

<head>
  <style>
  .btn-custom {
    color: #ffffff;
    background-color: #a979a8;
    /* border-top-left-radius: 16px;
    border-bottom-left-radius: 16px; */
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
  <h1 class="h3 mb-0 text-gray-800">Tindakan</h1>
  <a href="{{ route('tindakan.create') }}" class="btn mr-3 text-white" style="background-color: #a979a8;"
    onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';">Tambah
    Data</a>


  {{-- <a href="{{ route('tindakan.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>

<!-- Content Row -->
<!-- <div class="container"> -->
<!-- <div class="mb-4 form-group">
  <form action="{{ route('tindakan.import') }}" enctype="multipart/form-data" class="d-flex" method="POST">
    @csrf
    <input type="file" style="width: 70%" name="excel_file" class="form-control">
    <button class="btn btn-primary d-inline" type="submit">Submit</button>
  </form>
</div> -->

<div class="">
  <form action="{{ route('tindakan.import') }}" enctype="multipart/form-data" class="d-flex" method="POST">
    <div class="input-group mb-4 ">
      @csrf
      <div class="input-group-prepend">
        <button class="btn text-white"
          style="background-color: #a979a8; border-top-left-radius: 4px; border-bottom-left-radius: 4px;"
          onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
          id="inputGroupFileAddon01">Submit</button>
      </div>
      <div class="custom-file">
        <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01"
          name="excel_file">
        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
      </div>
    </div>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-bordered" id="tindakan" style="width: 100%">
    <thead>
      <tr>
        <th class="col-1">No</th>
        <th>Nama tindakan</th>
        <th>Biaya tindakan</th>
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
        "columns": [{
            "data": null,
            "className": "text-center",
            "render": function(data, type, row, meta) {
              return meta.row + 1;
            }
          },
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
            "render": function(data, type, row, meta) {
              return '<a href="tindakan/' + row.id +
                '/edit" class="btn btn-custom text-center">Edit</a>' + '<form action="tindakan/' + row
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