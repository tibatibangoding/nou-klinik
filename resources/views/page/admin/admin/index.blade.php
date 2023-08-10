@extends('layouts.dashboard')

@section('title')
admin
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">admin</h1>
  <a href="{{ route('admin.create') }}" class="btn btn-primary">Tambah Data</a>
</div>

<!-- Content Row -->
<div class="container">
  <div class="table-responsive">
    <table class="table table-striped table-bordered" id="admin" style="width: 100%">
      <thead>
        <tr>
          <th>Nama Admin</th>
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
    url: "{{ route('dataTablesAdmin') }}",
    type: "GET",
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#admin').DataTable({
        "data": data.admin,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "nama",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="admin/' + row.id + '/edit" class="btn btn-primary text-center">Edit</a>' +
                '<form action="admin/' + row.id +
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