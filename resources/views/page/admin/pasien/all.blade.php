@extends('layouts.dashboard')

@section('title')
pasien
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">pasien</h1>


  {{-- <a href="{{ route('pasien.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>

<!-- Content Row -->
<!-- <div class="container"> -->
<div class="table-responsive">
  <table class="table table-bordered" id="records" style="width:100%;">
    <thead>
      <tr>
        <th>Nama Pasien</th>
        <th>Alamat</th>
        <th>Tanggal Lahir</th>
        <th>Nomor Telepon</th>
        <th>Jenis Kelamin</th>
        <th>Pekerjaan</th>
        <th>Agama</th>
        <th>Alergi</th>
        <th>Kewarganegaraan</th>
        <th class="text-center">Aksi</th>
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
    url: "{{ route('dataTablesPasien') }}",
    type: "GET",
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#records').DataTable({
        "data": data.pasien,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "nama_pasien",
          },
          {
            "data": "alamat",
          },
          {
            "data": "tgl_lahir",
          },
          {
            "data": "no_telp",
          },
          {
            "data": "jenis_kelamin",
            "render": function(data, type, row, meta) {
              if (row.jenis_kelamin == 'P') {
                return 'Perempuan';
              } else {
                return 'Laki Laki';
              }
            }
          },
          {
            "data": "pekerjaan",
          },
          {
            "data": "agama",
          },
          {
            "data": "alergi",
          },
          {
            "data": "kewarganegaraan",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="pasien/rekamMedis/' + row.id +
                '" class="btn btn-primary text-center">Rekam Medis</a>'
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