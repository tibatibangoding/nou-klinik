@extends('layouts.dashboard')
@section('title', 'Edit Suara')
@section('content')
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
            <h1 class="card-title">Edit kategori</h1>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('kategori.update', $kategori_obat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="foto">Nama kategori</label>
              <input type="text" class="form-control" value="{{ $kategori_obat->nama_kategori }}" name="nama_kategori">
            </div>
            <button class="btn text-white" style="background-color: #a979a8;"
              onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
              type="submit">Save</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary ml-auto">Back</a>
          </form>
          @endsection