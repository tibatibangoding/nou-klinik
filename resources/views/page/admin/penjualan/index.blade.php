@extends('layouts.dashboard')

@section('title')
Penjualan
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Penjualan <b class="" style="font-size: 25px; color:#a979a8">(Bagi Yang Telah
      Melakukan
      Pembayaran)</b></h1>

  <a href="{{ route('penjualan.create') }}" class="btn text-white" style="background-color: #a979a8;"
    onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';">Tambah
    Data</a>
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
  <table class="table table-bordered" id="penjualan" style="width:100%">
    <thead>
      <tr>
        <th>ID Transaksi</th>
        <th>Tanggal Penjualan</th>
        <th>Total Harga</th>
        <th>Aksi</th>
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
    url: "{{ route('dataTablesTransaksi') }}",
    type: "GET",
    data: {
      start_date: start_date,
      end_date: end_date
    },
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#penjualan').DataTable({
        "data": data.penjualan,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "id_transaksi",
          },
          {
            "data": "created_at",
            "render": function(data, type, row, meta) {
              return moment(row.created_at).format('DD-MM-YYYY');
            }
          },
          {
            "data": "total_harga",
            "render": function(data, type, row, meta) {
              const num = row.total_harga
              return 'Rp. ' + Number(num).toLocaleString(3);
            }
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<th class="text-center">' +
                '<a href="penjualan/' + row.id + '" class="btn btn-primary text-center">Detail</a>' +
                '</th>'
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
    $('#penjualan').DataTable().destroy();
    fetch(start_date, end_date);
  }
});
// Reset
$(document).on("click", "#reset", function(e) {
  e.preventDefault();
  $("#start_date").val(''); // empty value
  $("#end_date").val('');
  $('#penjualan').DataTable().destroy();
  fetch();
});
</script>
@endpush