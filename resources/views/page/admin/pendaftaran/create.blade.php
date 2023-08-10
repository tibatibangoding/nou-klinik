@extends('layouts.dashboard')

@section('title')
    pendaftaran
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah pendaftaran</h1>
</div>

<!-- Content Row -->
<div class="page-inner mt--5">
	<div class="row">
		<div class="col-md-12">
			<h4 class="font-weight-bold">Pilih Pasien : <span class="text-sm text-danger">(Jika ada)</span></h4>
			<div class="d-flex justify-content-center mt-4 mb-3">
				<select class="form-control select2" id="id-pasien" style="width: 80%">
					@foreach ($pasien as $row)
						<option value="{{ $row->id }}">{{ $row->nama_pasien }}</option>
					@endforeach
				</select>
			</div>
			<div class="card full-height">
				<div class="card-header">
					<div class="card-head-row">
						<div class="card-title">Tambah pendaftaran</div>
						<a href="{{ route('pendaftaran.all') }}" class="btn btn-primary btn-sm ml-auto">Back</a>
					</div>
				</div>
				<div class="card-body">
					<form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
                        <div class="form-group">
							<label for="judul">Nama Pasien</label>
							<input type="text" class="form-control after-id" name="nama_pasien" id="nama_pasien">
							<input type="hidden" name="id_pasien" id="id_pasien">
						</div>
						<div class="form-group">
							<label for="judul">No Telpon</label>
							<input type="text" class="form-control" name="no_telp" id="no_telp">
						</div>
						<div class="form-group">
							<label for="judul">Alamat</label>
							<input type="text" class="form-control" name="alamat" id="alamat">
						</div>
						<div class="form-group">
							<label for="judul">Pekerjaan</label>
							<input type="text" class="form-control" name="pekerjaan" id="pekerjaan">
						</div>
						<div class="form-group">
							<label for="judul" class="tgl-lahir-cls">Tanggal Lahir</label>
							<input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir">
						</div>
						<div class="form-group">
							<label for="judul" class="jenis-kelamin-cls">Jenis Kelamin</label>
							<select class="form-control select2" name="jenis_kelamin" id="jenis_kelamin">
								<option value="L">Laki Laki</option>
								<option value="P">Perempuan</option>
							</select>
						</div>
						<div class="form-group">
							<label for="judul" class="agama-cls">Agama</label>
							<select class="form-control select2" name="agama" id="agama">
								<option value="Islam">Islam</option>
								<option value="Kristen">Kristen</option>
								<option value="Konghucu">Konghucu</option>
								<option value="Buddha">Buddha</option>
								<option value="Katholik">Katholik</option>
								<option value="Hindu">Hindu</option>
							</select>
						</div>
						<div class="form-group">
							<label for="judul">Alergi</label>
							<input type="text" required name="alergi" id="alergi" class="form-control">
						</div>
						<div class="form-group">
							<label for="judul">Kewarganegaraan</label>
							<input type="text" name="kewarganegaraan" id="kewarganegaraan" class="form-control">
						</div>
                        <div class="form-group">
							<label for="judul">Keluhan</label>
							<input type="text" class="form-control" required name="keluhan" id="keluhan">
						</div>
                        <div class="form-group">
							<label for="judul">Dokter</label>
							<select class="form-control select2" name="id_dokter" id="id_dokter">
                                @foreach ($dokter as $row)
                                    <option value="{{ $row->id }}">{{ $row->nama }} | {{ $row->spesialis }}</option>
                                @endforeach
                            </select>
						</div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" type="submit">Save</button>
                        </div>
@endsection

@push('scripts')
<script>
	$('#id-pasien').on('change', (event) => {
		getDataPasien(event.target.value).then(pasien => {
			$('#id_pasien').remove();
			var id_pasien = '<input type="hidden" name="id_pasien" id="id_pasien" value='+pasien.id+' readonly>';
			$(id_pasien).insertAfter('.after-id');
			$('#nama_pasien').val(pasien.nama_pasien);
			$('#no_telp').val(pasien.no_telp);
			$('#alamat').val(pasien.alamat);
			$('#pekerjaan').val(pasien.pekerjaan);
			document.getElementById("tgl_lahir").value = pasien.tgl_lahir;
			// $('#tgl_lahir').remove();
			// var tgl_lahir = '<input type="text" name"tgl_lahir" class="form-control" id="tgl_lahir" value="'+pasien.tgl_lahir+'" readonly>';
			// $(tgl_lahir).insertAfter('.tgl-lahir-cls');
			$('#jenis_kelamin').val(pasien.jenis_kelamin).change();
			$('#agama').val(pasien.agama).change();
			$('#alergi').val(pasien.alergi);
			$('#kewarganegaraan').val(pasien.kewarganegaraan);
		})
	})
	async function getDataPasien(id) {
		let response = await fetch('/apipasien/' + id)
		let data = await response.json();

		return data;
	}
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
