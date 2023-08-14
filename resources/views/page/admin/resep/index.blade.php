@extends('layouts.dashboard')

@section('title')
resep
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">resep</h1>
  {{-- <a href="{{ route('resep.create', $pendaftaran->id) }}" class="btn btn-primary">Tambah Data</a> --}}

  {{-- <a href="{{ route('resep.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
</div>

<!-- Content Row -->
<!-- <div class="container"> -->
<div class="d-flex gap-3 mt-3 ">
  <div class=" flex-fill mr-3">
    <div class="input-group mb-3" style="height: 100%;">
      <div class="input-group-prepend">
        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
            class="fas fa-calendar-alt"></i></span>
      </div>
      <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly style="height: 100%;">
    </div>
  </div>
  <div class="flex-fill mr-3">
    <div class="input-group mb-3" style="height: 100%;">
      <div class="input-group-prepend">
        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
            class="fas fa-calendar-alt"></i></span>
      </div>
      <input type="text" class="form-control" id="end_date" placeholder="End Date" readonly style="height: 100%;">
    </div>
  </div>
  <button id="filter" class="btn mr-3 text-white" style="background-color: #a979a8;"
    onmouseover="this.style.backgroundColor='#6c5576';"
    onmouseout="this.style.backgroundColor='#a979a8';">Filter</button>
  <button id="reset" class="btn btn-outline-secondary">Reset</button>
</div>
<div class="table-responsive mt-4">
  <table class="table table-bordered" id="resep" style="width: 100%">
    <thead>
      <tr>
        <th>Nama Pasien</th>
        <th>Nama Resep</th>
        <th>Nomor Antrian</th>
        <th>Nama Poli</th>
        <th>Nama Dokter</th>
        <th>Waktu Resep Dibuat</th>
        <th>Status Bayar</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
  </table>
</div>
<!-- </div> -->
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
    url: "{{ route('dataTablesResep') }}",
    type: "GET",
    data: {
      start_date: start_date,
      end_date: end_date
    },
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#resep').DataTable({
        "data": data.resep,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "nama_pasien",
          },
          {
            "data": "nama_resep",
          },
          {
            "data": "no_antrian",
          },
          {
            "data": "nama_poli",
          },
          {
            "data": "nama_dokter",
          },
          {
            "data": "resep_dibuat",
            "render": function(data, type, row, meta) {
              return moment(row.resep_dibuat).format('DD-MM-YYYY');
            }
          },
          {
            "data": "status_bayar",
            "render": function(data, type, row, meta) {
              if (row.status_bayar == 1 && row.pembayaran == 1) {
                return 'Lunas';
              } else if (row.status_bayar == 1 && row.pembayaran == 2) {
                return 'Belum Lunas / DP';
              } else {
                return 'Belum Bayar'
              }
            }
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="resep/detail/' + row.id_resep +
                '" class="btn btn-primary text-center">Detail</a>'
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
    $('#resep').DataTable().destroy();
    fetch(start_date, end_date);
  }
});
// Reset
$(document).on("click", "#reset", function(e) {
  e.preventDefault();
  $("#start_date").val(''); // empty value
  $("#end_date").val('');
  $('#resep').DataTable().destroy();
  fetch();
});
</script>
@endpush