@extends('layouts.dashboard')

@section('title')
pembayaran
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">pembayaran <b class="text-success" style="font-size: 25px">(Bagi Yang Telah
      Melakukan Pembayaran)</b></h1>


  {{-- <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">Tambah Data</a>s --}}
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
      <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly style="height: 100%;" />
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
<div class="table-responsive mt-3">
  <table class="table table-bordered" id="pembayaran" style="width: 100%">
    <thead>
      <tr>
        <th>ID Pendaftaran</th>
        <th>Nama Pembayaran</th>
        <th>Biaya</th>
        <th>Waktu Pembayaran</th>
        <th>Nomor Antrian</th>
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

function fetch(start_date, end_date) {
  $.ajax({
    url: "{{ route('dataTablesPembayaran') }}",
    type: "GET",
    data: {
      start_date: start_date,
      end_date: end_date
    },
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#pembayaran').DataTable({
        "data": data.pembayaran,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "id_pendaftaran",
          },
          {
            "data": "id_pembayaran",
          },
          {
            "data": "total_biaya",
            "render": function(data, type, row, meta) {
              const num = row.total_biaya
              return 'Rp. ' + Number(num).toLocaleString(3);
            }
          },
          {
            "data": "tanggal_daftar",
            "render": function(data, type, row, meta) {
              return moment(row.tanggal_daftar).format('DD-MM-YYYY');
            }
          },
          {
            "data": "no_antrian",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              var url = "{{route('pembayaran.show', '')}}" + "/" + row.id_pendaftaran;
              return '<a href="' + url +
                '" class="btn btn-primary text-center">Detail pembayaran dan pemeriksaan</a>';
            }
          }
        ]
      });
    }
  });
}
fetch();
$(document).on("click", "#filter", function(e) {
  e.preventDefault();
  var start_date = $("#start_date").val();
  var end_date = $("#end_date").val();
  if (start_date == "" || end_date == "") {
    alert("Both date required");
  } else {
    $('#pembayaran').DataTable().destroy();
    fetch(start_date, end_date);
  }
});
// Reset
$(document).on("click", "#reset", function(e) {
  e.preventDefault();
  $("#start_date").val(''); // empty value
  $("#end_date").val('');
  $('#pembayaran').DataTable().destroy();
  fetch();
});
</script>
@endpush