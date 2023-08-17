@extends('layouts.dashboard')
@section('title', 'Edit Tindakan')
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <!-- <h1 class="h3 mb-0 text-gray-800">Edit Tindakan</h1> -->

</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Edit Tindakan</h1>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('tindakan.update', $tindakan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="foto">Nama tindakan</label>
              <input type="text" class="form-control" value="{{ $tindakan->nama_tindakan }}" name="nama_tindakan">
            </div>
            <div class="form-group">
              <label for="foto">Nama tindakan</label>
              <input type="text" class="form-control" value="{{ $tindakan->biaya_tindakan }}" name="biaya_tindakan">
            </div>
            <button class="btn text-white" style="background-color: #a979a8;"
              onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
              type="submit">Save</button>
            <a href="{{ route('tindakan.index') }}" class="btn btn-outline-secondary ml-auto">Cancel</a>
          </form>
          @endsection