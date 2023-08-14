@extends('layouts.dashboard')
@section('title', 'Edit Suara')
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

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <!-- <h1 class="h3 mb-0 text-gray-800">Edit Kandidat</h1> -->
</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Edit Poli</h1>

          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('poli.update', $poli->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="foto">Nama Poli</label>
              <input type="text" class="form-control" value="{{ $poli->nama_poli }}" name="nama_poli">
            </div>
            <button class="btn btn-custom" type="submit">Save</button>
            <a href="{{ route('poli.index') }}" class="btn btn-outline-secondary ml-auto">Cancel</a>
          </form>
          @endsection