@extends('layouts.dashboard')

@section('title')
edit admin
@endsection

@section('content')
<!-- Page Heading -->
<!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Edit admin</h1>

</div> -->

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Edit admin</h1>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('admin.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
              <label for="judul">ID</label>
              <input type="text" value="{{ $admin->username }}" class="form-control" name="username">
            </div>
            <div class="form-group">
              <label for="judul">Nama</label>
              <input type="text" value="{{ $admin->nama }}" class="form-control" name="nama">
            </div>
            <div class="form-group">
              <label for="judul">Password</label>
              <input type="text" class="form-control" name="password">
            </div>
            <div class="form-group">
              <button class="btn mr-3 text-white" style="background-color: #a979a8;"
                onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
                type="submit">Save</button>
              <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary  ml-auto">Cancel</a>

            </div>
            @endsection