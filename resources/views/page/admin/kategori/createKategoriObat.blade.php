@extends('layouts.dashboard')

@section('title')
kategori
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <!-- <h1 class="h3 mb-0 text-sgray-800">Tambah Kategori</h1> -->
</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Tambah Kategori</h1>
            <!-- <a href="{{ route('obat.create') }}" class="btn btn-primary btn-sm ml-auto">Back</a> -->
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('storeKategoriObat') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="judul">Nama kategori</label>
              <input type="text" class="form-control" name="nama_kategori">
            </div>
            <div class="form-group">
              <button class="btn text-white" style="background-color: #a979a8;"
                onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
                type="submit">Save</button>
              <a href="{{ route('obat.create') }}" class="btn btn-outline-secondary ml-auto">Back</a>

            </div>
            @endsection