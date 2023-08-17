@extends('layouts.dashboard')
@section('title', 'Edit data Klinik')
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <!-- <h1 class="h3 mb-0 text-gray-800">Edit klinik</h1> -->

</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Edit Klinik</h1>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('klinik.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="foto">Nama klinik</label>
              <input type="text" class="form-control" name="nama_klinik" value="{{ $klinik->nama_klinik }}">
            </div>
            <div class="form-group">
              <label for="foto">Alamat klinik</label>
              <input type="text" class="form-control" name="alamat_klinik" value="{{ $klinik->alamat_klinik }}">
            </div>
            <div class="form-group">
              <label for="foto">Kota klinik</label>
              <input type="text" class="form-control" name="kota" value="{{ $klinik->kota }}">
            </div>
            <div class="form-group">
              <label for="foto">Telpon klinik</label>
              <input type="text" class="form-control" name="tlp_klinik" value="{{ $klinik->tlp_klinik }}">
            </div>
            <div class="form-group">
              <label for="foto">FAX klinik</label>
              <input type="text" class="form-control" name="fax_klinik" value="{{ $klinik->fax_klinik }}">
            </div>
            <div class="form-group">
              <label for="judul">Logo klinik</label>
              <br>
              @if ($klinik->logo != null)
              <img src="{{ asset(asset('/storage/' .$klinik->logo)) }}"
                class="img-preview img-fluid mb-2 mt-2 col-sm-4">
              @else
              <img class="img-preview img-fluid mb-5 mt-5 col-sm-4">
              @endif
              <input type="file" class="form-control" name="logo" id="img-radio" onchange="previewImage()">
            </div>
            <div class="form-group">
              <label for="foto">Biaya admin klinik</label>
              <input type="number" class="form-control" name="biaya_admin" value="{{ $klinik->biaya_admin }}">
            </div>
            <button class="btn text-white" style="background-color: #a979a8;"
              onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
              type="submit">Save</button>
            <a href="{{ route('klinik.index') }}" class="btn btn-outline-secondary ml-auto">Back</a>
          </form>
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