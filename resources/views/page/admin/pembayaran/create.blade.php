@extends('layouts.dashboard')

@section('title')
    pembayaran
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah pembayaran</h1>
</div>

<!-- Content Row -->
<div class="page-inner mt--5">
	<div class="row">
		<div class="col-md-12">
			<div class="card full-height">
				<div class="card-header">
					<div class="card-head-row">
						<div class="card-title">Tambah pembayaran</div>
					</div>
				</div>

				<div class="card-body">
					<form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
                        <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id }}">
                        <div class="form-group">
							<label for="foto">Biaya Administrasi</label>
                            <input type="text" name="biaya_admins" readonly class="form-control" value="Rp {{ number_format($harga_admin) }}">
                            <input type="hidden" name="biaya_admin" readonly class="form-control" value="{{ $harga_admin }}">
						</div>
                        <div class="form-group">
							<label for="foto">Biaya Tindakan</label>
                            <input type="text" name="harga_tindakans" readonly class="form-control" value="Rp {{ number_format($harga_tindakan) }}">
                            <input type="hidden" name="harga_tindakan" readonly class="form-control" value="{{ $harga_tindakan }}">
						</div>
						<div class="form-group">
							<label for="foto">Total Biaya Obat</label>
                            <input type="text" name="biaya_obats" readonly class="form-control" value="Rp {{ number_format($harga_total) }}">
                            <input type="hidden" name="biaya_obat" readonly class="form-control" value="{{ $harga_total }}">
						</div>
						<div class="form-group">
							<label for="foto">Biaya Dokter</label>
                            <input type="text" name="biaya_dokters" readonly class="form-control" value="Rp {{ number_format($biaya_dokter) }}">
                            <input type="hidden" name="biaya_dokter" readonly class="form-control" value="{{ $biaya_dokter }}">
						</div>
						<div class="form-group">
							<label for="foto">Total Biaya</label>
                            <input type="text" name="total_biayas" readonly class="form-control" value="Rp {{ number_format($total_biaya) }}">
                            <input type="hidden" name="total_biaya" readonly class="form-control" value="{{ $total_biaya }}">
						</div>
                        <div class="form-group">
							<label for="foto">Status Bayar</label>
                            <select class="form-control" name="status_bayar">
                                <option value="1">Lunas</option>
                                <option value="2">DP</option>
                            </select>
						</div>
                        <div class="form-group">
							<label for="foto">Total Bayar / DP</label>
                            <input type="text" class="form-control" name="total_bayar">
						</div>
						<div class="form-group">
							<label for="foto">Nama Pembayaran</label>
                            <input type="text" readonly value="{{ $nama_pembayaran }}" class="form-control" name="nama_pembayaran">
						</div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" type="submit">Save</button>
                        </div>
@endsection
