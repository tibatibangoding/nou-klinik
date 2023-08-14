@extends('layouts.dashboard')

@section('title')
Dashboard
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Content Row -->
<div class="row">
  <div class="" style="width: 100%;">
    @switch(Auth::user()->roles->pluck('name')[0])
    @case('dokter')
    <div class="col-xl-3 col-md-4 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Jumlah Pasien telah di Periksa</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data_sudah }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-comments fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-4 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Jumlah Pasien menunggu di Periksa</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data_tunggu }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-comments fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 mt-3">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
              class="fas fa-calendar-alt"></i></span>
        </div>
        <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly>
      </div>
    </div>
    <div class="col-md-6">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
              class="fas fa-calendar-alt"></i></span>
        </div>
        <input type="text" class="form-control" id="end_date" placeholder="End Date" readonly>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <button id="filter" class="btn btn-primary">Filter</button>
    <button id="reset" class="btn btn-warning">Reset</button>
  </div>
</div>
<div class="table-responsive mt-3">
  <table class="table table-bordered" id="records" style="width:100%">
    <thead>
      <tr>
        <th>Nama Pasien</th>
        <th>Waktu Pendaftaran</th>
        <th>Nomor Pendaftaran</th>
        <th>Nama Dokter</th>
        <th>Aksi</th>
      </tr>
    </thead>
  </table>
</div>
@break

@case('apoteker')
<div class="col-xl-3 col-md-4 mb-4">
  <div class="card border-left-warning shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
            Obat paling banyak terjual</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data_banyak[0]->nama_obat }} |
            {{ $data_banyak[0]->total }}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-comments fa-2x text-gray-300"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-xl-3 col-md-4 mb-4">
  <div class="card border-left-warning shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
            Obat paling sedikit terjual</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data_sedikit[0]->nama_obat }} |
            {{ $data_sedikit[0]->total }}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-comments fa-2x text-gray-300"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-md-6 mt-3">
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
          class="fas fa-calendar-alt"></i></span>
    </div>
    <input type="text" class="form-control" id="start_date_penjualan" placeholder="Start Date" readonly>
  </div>
</div>
<div class="col-md-6">
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
          class="fas fa-calendar-alt"></i></span>
    </div>
    <input type="text" class="form-control" id="end_date_penjualan" placeholder="End Date" readonly>
  </div>
</div>
</div>
<div class="col-md-6">
  <button id="filter_penjualan" class="btn btn-primary">Filter</button>
  <button id="reset_penjualan" class="btn btn-warning">Reset</button>
</div>
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
<div class="mt-5" id="chart"></div>
@break

@case('kasir')
<div id="chart"></div>
<div class="table-responsive">
  <table class="table table-bordered">
    <tr>
      <th>ID Transaksi</th>
      <th>Tanggal Penjualan</th>
      <th>Total Harga</th>
      <th class="text-center">Aksi</th>
    </tr>
    @forelse ($data_penjualan as $row)
    <tr>
      <th>{{ $row->id_transaksi }}</th>
      <th>{{ $row->tgl_penjualan }}</th>
      <th>{{ $row->total_harga }}</th>
      <th class="text-center"><a href="{{ route('penjualan.show', $row->id) }}" class="btn btn-primary">Detail</a></th>
    </tr>
    @empty
    <td colspan="4" class="text-center">Data Masih Kosong!</td>
    @endforelse
  </table>
</div>
@break

@case('admin')

<head>
  <style>
  .menu-buttons .btn.active {
    background-color: #8b89c3;
    border-radius: 0%;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    /* Active color */
    color: #fff;
  }

  .menu-buttons {
    border-bottom: 1px solid #8b89c3;
    border-radius: 0%;
    outline: none;
  }
  </style>
</head>
<div class="menu-buttons mb-5">
  <div id="showPendaftaran" class="btn  active">Tabel Pendaftaran</div>
  <div id="showPenjualan" class="btn ">Tabel Penjualan</div>
</div>
<div id="daftar" class="">
  <!-- <h1 class="mt-4">Tabel Pendaftaran <b class="text-success" style="font-size: 35px">(Bagi Yang Telah Melakukan
      Pembayaran)</b></h1> -->
  <div class="d-flex gap-3 mt-3 ">
    <div class=" flex-fill mr-3">
      <div class="input-group mb-3" style="height: 100%;">
        <div class="input-group-prepend">
          <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
              class="fas fa-calendar-alt"></i></span>
        </div>
        <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly
          style="height: 100%;" />
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

  <div>
  </div>
  <div class="table-responsive mt-3">
    <table class="table table-bordered" id="records" style="width:100%">
      <thead>
        <tr>
          <th>Nama Pasien</th>
          <th>Waktu Pendaftaran</th>
          <th>Nomor Pendaftaran</th>
          <th>Nama Dokter</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div id="jual">
  <!-- <h1 class="mt-4">Tabel Penjualan <b class="text-success" style="font-size: 35px">(Bagi Yang Telah Melakukan
      Pembayaran)</b></h1> -->
  <div class="d-flex gap-3 mt-3 ">
    <div class=" flex-fill mr-3">
      <div class="input-group mb-3" style="height: 100%;">
        <div class="input-group-prepend">
          <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
              class="fas fa-calendar-alt"></i></span>
        </div>
        <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly
          style="height: 100%;" />
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
</div>
<div class="mt-5" id="chart"></div>
@break
@default

@endswitch
</div>

</div>
@endsection
@push('scripts')
@if(Auth::user()->roles->pluck('name')[0] == 'apoteker')
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
    url: "{{ route('dataTablesPendaftaran') }}",
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
            "data": "id",
          },
          {
            "data": "dokter.nama",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="resep/' + row.id_resep + '" class="btn btn-primary text-center">Detail</a>'
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

@elseif(Auth::user()->roles->pluck('name')[0] == 'admin')
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
    url: "{{ route('dataTablesPendaftaran') }}",
    type: "GET",
    data: {
      start_date: start_date,
      end_date: end_date
    },
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#records').DataTable({
        "data": data.pendaftaran,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "nama_pasien",
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
            "data": "nama_dokter",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="pasien/' + row.id + '" class="btn btn-primary text-center">Detail</a>'
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
//button menu
$(document).ready(function() {
  // Hide both tables initially
  $('#daftar').show();
  $('#jual').hide();

  // Show Pendaftaran table on button click
  $('#showPendaftaran').click(function() {
    $('#jual').hide();
    $('#daftar').show();

    // Toggle active class
    $(this).addClass('active');
    $('#showPenjualan').removeClass('active');
  });

  // Show Penjualan table on button click
  $('#showPenjualan').click(function() {
    $('#daftar').hide();
    $('#jual').show();

    // Toggle active class
    $(this).addClass('active');
    $('#showPendaftaran').removeClass('active');
  });

  // Rest of your existing scripts...
});
</script>
@else
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
    url: "{{ route('dataTablesPendaftaran') }}",
    type: "GET",
    data: {
      start_date: start_date,
      end_date: end_date
    },
    dataType: "json",
    success: function(data) {
      // Datatables
      var i = 1;
      $('#records').DataTable({
        "data": data.pendaftaran,
        // responsive
        "responsive": true,
        "columns": [{
            "data": "nama_pasien",
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
            "data": "nama_dokter",
          },
          {
            "data": 'button',
            "render": function(data, type, row, meta) {
              return '<a href="pasien/' + row.id + '" class="btn btn-primary text-center">Detail</a>'
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
@endif
<script>
$(function() {
  $("#start_date_penjualan").datepicker({
    "dateFormat": "yy-mm-dd"
  });
  $("#end_date_penjualan").datepicker({
    "dateFormat": "yy-mm-dd"
  });
});
// Fetch records
function fetchPenjualan(start_date, end_date) {
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
fetchPenjualan();
// Filter
$(document).on("click", "#filter_penjualan", function(e) {
  e.preventDefault();
  var start_date = $("#start_date_penjualan").val();
  var end_date = $("#end_date_penjualan").val();
  if (start_date == "" || end_date == "") {
    alert("Both date required");
  } else {
    $('#penjualan').DataTable().destroy();
    fetchPenjualan(start_date, end_date);
  }
});
// Reset
$(document).on("click", "#reset_penjualan", function(e) {
  e.preventDefault();
  $("#start_date_penjualan").val(''); // empty value
  $("#end_date_penjualan").val('');
  $('#penjualan').DataTable().destroy();
  fetchPenjualan();
});
</script>
<!-- <script src="https://code.highcharts.com/highcharts.js"></script>
<script>
var seriesData = [];

if (penjualanData.length <= 0) {
  seriesData.push({
    name: 'theres no data',
    y: 0
  });
} else {
  penjualanData.forEach(function(row) {
    seriesData.push({
      name: row.nama_obat,
      y: row.total > 0 ? row.total : 0
    });
  });
}

Highcharts.chart('chart', {
  title: {
    text: 'Laporan'
  },
  subtitle: {
    text: 'Berikut Obat paling banyak terjual'
  },
  xAxis: {
    categories: seriesData.map(function(row) {
      return row.name;
    }),
  },
  yAxis: {
    title: {
      text: 'Obat Terlaris'
    }
  },
  legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle'
  },
  plotOptions: {
    series: {
      allowPointSelect: true
    }
  },
  series: [{
    type: 'bar',
    name: 'penjualan',
    data: penjualanData.map(function(row) {
      return {
        name: row.nama_obat,
        y: row.total > 0 ? row.total : 0
      };
    })
  }],
  responsive: {
    rules: [{
      condition: {
        maxWidth: 500
      },
      chartOptions: {
        legend: {
          layout: 'vertical',
          align: 'center',
          verticalAlign: 'bottom'
        }
      }
    }]
  }
});
</script> -->
@endpush