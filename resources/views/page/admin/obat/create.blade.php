@extends('layouts.dashboard')

@section('title')
obat
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <!-- <h1 class="h3 mb-0 text-gray-800">Tambah obat</h1> -->

</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Tambah obat</h1>
            <!-- <a href="{{ route('obat.index') }}" class="btn btn-primary btn-sm ml-auto">Back</a> -->
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="judul">Nama Obat</label>
              <input type="text" class="form-control" name="nama_obat">
            </div>
            <div class="form-group">
              <label for="judul">Harga Beli</label>
              <input type="number" class="form-control" name="harga_beli">
            </div>
            <div class="form-group">
              <label for="judul">Harga Jual</label>
              <input type="number" class="form-control" name="harga_jual">
            </div>
            <div class="form-group">
              <label for="judul">Stok</label>
              <input type="number" class="form-control" name="stok">
            </div>

            <!-- <div class="form-group">
              <label for="judul">Foto Bukti</label>
              <img class="img-preview img-fluid mb-5 mt-5 col-sm-4">
              <input type="file" class="form-control" name="gambar" id="img-radio" onchange="previewImage()">
            </div> -->

            <div class="mt-4">
              <label for="judul">Foto Bukti</label>
              <div class="input-group mb-4 ">
                <div class="custom-file rounded">
                  <input type="file" class="custom-file-input" aria-describedby="inputGroupFileAddon01" name="gambar"
                    id="img-radio" onchange="previewImage()">
                  <label class="custom-file-label" for="img-radio">Choose file</label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="judul">Kategori</label>
              <a href="{{ route('createKategoriObat') }}" class="btn-sm btn-primary ml-3"
                style="background-color: #a979a8;" onmouseover="this.style.backgroundColor='#6c5576';"
                onmouseout="this.style.backgroundColor='#a979a8';">+ Tambah Kategori</a>
              <select class="select2 form-control" name="id_kategori" id="">
                @foreach ($kategori as $row)
                <option value="{{ $row->id }}">{{ $row->nama_kategori }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="judul">Jenis Obat</label>
              <a href="{{ route('createJenisObat') }}" class="btn-sm btn-primary ml-3"
                style="background-color: #a979a8;" onmouseover="this.style.backgroundColor='#6c5576';"
                onmouseout="this.style.backgroundColor='#a979a8';">+ Tambah Jenis</a>
              <select class="select2 form-control" name="id_jenis_obat" id="">
                @foreach ($jenis_obat as $row)
                <option value="{{ $row->id }}">{{ $row->nama_jenis }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <button class="btn text-white" style="background-color: #a979a8;"
                onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
                type="submit">Save</button>
              <a href="{{ route('obat.index') }}" class="btn btn-outline-secondary ml-auto">Cancel</a>

            </div>
            @endsection

            @push('scripts')
            <script>
            function previewImage() {
              const image = document.querySelector('#img-radio');
              const imgPreview = document.querySelector('.img-preview');

              imgPreview.style.display = 'block';

              const oFReader = new FileReader();
              oFReader.readAsDataURL(image.files[0]);

              oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
              }
            }
            </script>
            <script>
            $(document).ready(function() {
              $('.select2').select2();
            });
            </script>
            @endpush