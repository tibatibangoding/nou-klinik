@extends('layouts.dashboard')

@section('title')
edit dokter
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

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Edit Dokter</h1>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('dokter.update', $dokter->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
              <label for="judul">ID</label>
              <input type="text" required value="{{ $dokter->username }}" class="form-control" name="username">
            </div>
            <div class="form-group">
              <label for="judul">Password</label>
              <input type="text" class="form-control" name="password">
            </div>
            <div class="form-group">
              <label for="judul">Nama</label>
              <input type="text" required value="{{ $dokter->nama }}" class="form-control" name="nama">
            </div>
            <div class="form-group">
              <label for="judul">Spesialis</label>
              <input type="text" required value="{{ $dokter->spesialis }}" class="form-control" name="spesialis">
            </div>
            <div class="form-group">
              <label for="judul">biaya dokter</label>
              <input type="number" required value="{{ $dokter->biaya_dokter }}" class="form-control"
                name="biaya_dokter">
            </div>
            <div class="form-group">
              <label for="foto">Poli</label>
              <select name="id_poli" class="form-control">
                @foreach ($poli as $row)
                @if ($row->id == $dokter->id_poli)
                <option value="{{ $row->id }}" selected>{{ $row->nama_poli }}</option>
                @else
                <option value="{{ $row->id }}">{{ $row->nama_poli }}</option>
                @endif
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <button class="btn btn-custom " type="submit">Save</button>
              <a href="{{ route('dokter.index') }}" class="btn btn-outline-secondary  ml-auto">Cancel</a>
            </div>
            @endsection