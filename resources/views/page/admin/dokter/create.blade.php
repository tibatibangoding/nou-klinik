@extends('layouts.dashboard')

@section('title')
dokter
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
  <h1 class="h3 mb-0 text-gray-800">Tambah dokter</h1>

</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <div class="card-title">Tambah dokter</div>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('dokter.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="judul">ID</label>
              <input type="text" required class="form-control" name="username">
            </div>
            <div class="form-group">
              <label for="judul">Password</label>
              <input type="text" required class="form-control" name="password">
            </div>
            <div class="form-group">
              <label for="judul">Nama dokter</label>
              <input type="text" required class="form-control" name="nama">
            </div>
            <div class="form-group">
              <label for="judul">spesialis</label>
              <input type="text" required class="form-control" name="spesialis">
            </div>
            <div class="form-group">
              <label for="judul">biaya dokter</label>
              <input type="number" required class="form-control" name="biaya_dokter">
            </div>
            <div class="form-group">
              <label for="judul">Poli</label>
              <a href="{{ route('createPoliDokter') }}" class="btn-sm btn-custom ml-3">+ Tambah Poli</a>
              <select name="id_poli" class="form-control selectpoli" id="">
                @foreach ($poli as $row)
                <option value="{{ $row->id }}">{{ $row->nama_poli }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <button class="btn btn-custom " type="submit">Save</button>
              <a href="{{ route('dokter.index') }}" class="btn btn-outline-secondary  ml-auto">Cancel</a>
            </div>
            @endsection
            @push('scripts')
            <script>
            $(document).ready(function() {
              $('.selectpoli').select2();
            });
            </script>
            @endpush