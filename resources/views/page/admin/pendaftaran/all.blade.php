@extends('layouts.dashboard')

@section('title')
pendaftaran
@endsection

@section('content')

<head>
  <style>
  .btn-custom {
    color: #ffffff;
    background-color: #a979a8;
  }

  .btn-outline-custom {
    border: 2px solid black;
    background-color: transparent;
    color: black;
    font-size: 16px;
    cursor: pointer;
    border-color: #a979a8;
    color: #a979a8;
    border-radius: 4px;
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
  @if (Auth::user()->roles->pluck('name')[0] == 'dokter')
  <h1 class="h3 mb-0 text-gray-800">daftar antrian</h1>
  @else
  <h1 class="h3 mb-0 text-gray-800">Pendaftaran</h1>
  @endif
  @hasrole('admin')
  <a href="{{ route('pendaftaran.create') }}" class="btn text-white" style="background-color: #a979a8;"
    onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';">Daftarkan
    Pasien</a>
  @endhasrole

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

<div class="table-responsive mt-3">
  <table class="table table-bordered" id="records" style="width:100%">
    <thead>
      <tr>
        <th>Nama Pasien</th>
        <th>Waktu Pendaftaran</th>
        <th>Nomor Antrian</th>
        <th>Nama Dokter</th>
        <th>Nama Poli</th>
        <th>Status Periksa</th>
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
    url: "{{ route('dataTablesPendaftaranAll') }}",
    type: "GET",
    data: {
      start_date: start_date,
      end_date: end_date
    },
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      var role = `{{ Auth::user()->roles->pluck('name')[0] }}`;
      $('#records').DataTable({
        "data": data.pendaftaran,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "pasien.nama_pasien",
          },
          {
            "data": "created_at",
            "render": function(data, type, row, meta) {
              return moment(row.created_at).format('DD-MM-YYYY');
            }
          },
          {
            "data": "no_antrian",
          },
          {
            "data": "dokter.nama",
          },
          {
            "data": "poli.nama_poli",
          },
          {
            "data": "status_periksa",
            "render": function(data, type, row, meta) {
              switch (row.status_periksa) {
                case '1':
                  return '<div class="btn btn-outline-custom">Selesai di periksa</div>'
                  break;
                case '2':
                  return '<div class="btn btn-outline-custom">Sedang di periksa</div>'
                  break;
                case '3':
                  return '<div class="btn btn-outline-custom">Menunggu antrian</div>';
                  break;
                default:
                  break;
              }
            }
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              if (role == 'admin') {
                switch (row.status_periksa) {
                  case '1':
                    return '<a href="pasien/' + row.id +
                      '" class="btn btn-outline-custom text-center">Detail</a>'
                    break;
                  case '2':
                    return '<div class="btn btn-outline-custom">Pasien sedang periksa</div>'
                    break;
                  case '3':
                    return '<div class="btn btn-outline-custom">Pasien dalam antrian</div>'
                    break;
                  default:
                    break;
                }
              } else if (role == 'dokter') {
                switch (row.status_periksa) {
                  case '1':
                    return '<a href="pasien/' + row.id +
                      '" class="btn btn-custom text-center">Detail</a>'
                    break;
                  case '2':
                    return '<a href="periksa/create/' + row.id +
                      '" class="btn btn-custom">Lanjutkan pemeriksaan pasien</a>'
                    break;
                  case '3':
                    return '<a href="periksa/create/' + row.id +
                      '" class="btn btn-custom">Periksa Pasien</a>';
                    break;
                  default:
                    break;
                }
              }
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
    $('#records').DataTable().destroy();
    fetch(start_date, end_date);
  }
});
// Reset
$(document).on("click", "#reset", function(e) {
  e.preventDefault();
  $("#start_date").val(''); // empty value
  $("#end_date").val('');
  $('#records').DataTable().destroy();
  fetch();
});
</script>
@endpush