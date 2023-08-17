@extends('layouts.dashboard')

@section('title')
penjualan
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <!-- <h1 class="h3 mb-0 text-gray-800">Tambah penjualan</h1> -->
</div>

<!-- Content Row -->
<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-12">
      <div class="card full-height">
        <div class="card-header">
          <div class="card-head-row">
            <h1 class="card-title">Pembayaran</h1>
            <!-- <a href="{{ route('penjualan.index') }}" class="btn btn-primary btn-sm ml-auto">Back</a> -->
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('penjualan-konfirm-update', $penjualan->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group classAdded mb-2">
              <label for="">Total Harga</label>
              <input type="text" readonly value="Rp. {{ number_format($penjualan->total_harga) }}" class="form-control">
            </div>
            <div class="form-group classAdded mb-2">
              <label for="">Total Bayar</label>
              <input type="number" name="total_bayar" class="form-control">
            </div>

            <div class="form-group">
              <button class="btn text-white subm" style="background-color: #a979a8;"
                onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';"
                type="submit">Bayar</button>
              <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary ml-auto">Back</a>

            </div>
            @endsection