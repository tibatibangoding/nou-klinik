@extends('layouts.dashboard')

@section('title')
poli
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
  <h1 class="h3 mb-0 text-gray-800">Tambah poli</h1>
</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <div class="card-title">Tambah poli</div>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('poli.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="judul">Nama Poli</label>
              <input type="text" class="form-control" name="nama_poli">
            </div>
            <div class="form-group">
              <button class="btn btn-custom " type="submit">Save</button>
              <a href="{{ route('poli.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
            @endsection