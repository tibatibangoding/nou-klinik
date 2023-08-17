@extends('layouts.dashboard')

@section('title')
Kandidat
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <!-- <h1 class="h3 mb-0 text-gray-800">Tambah Kandidat</h1> -->

</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Tambah Kandidat</h1>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('kandidat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="judul">Nama</label>
              <input type="text" class="form-control" name="nama">
            </div>
            <div class="form-group">
              <label for="judul">Visi</label>
              <input type="text" class="form-control" name="visi">
              <!-- <textarea name="visi" id="editor1"></textarea> -->
            </div>
            <div class="form-group">
              <label for="judul">Misi</label>
              <!-- <input type="text" class="form-control" name="misi"> -->
              <textarea name="misi" id="editor1"></textarea>
            </div>
            <div class="form-group">
              <label for="judul">Pengalaman Berorganisasi</label>
              <!-- <input type="text" class="form-control" name="misi"> -->
              <textarea name="pengalaman" id="editor2"></textarea>
            </div>
            <div class="form-group">

              <input type="text" class="form-control" name="periode">
            </div>
            <div class="form-group">
              <label for="foto">Foto</label>
              <input type="file" class="form-control" name="foto">
            </div>
            <div class="form-group">
              <label for="judul">Jumlah Suara</label>
              <input type="text" class="form-control" name="jumlahsuara">
            </div>
            <div class="form-group">
              <button class="btn text-white" style="background-color: #a979a8;"
                onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
                type="submit">Save</button>
              <a href="{{ route('kandidat.index') }}" class="btn btn-outline-secondary ml-auto">Back</a>
            </div>
            @endsection