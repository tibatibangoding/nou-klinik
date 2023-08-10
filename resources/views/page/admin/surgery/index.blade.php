@extends('layouts.dashboard')

@section('title')
    pasien
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">pasien</h1>

    {{-- <a href="{{ route('pasien.create') }}" class="btn btn-primary" >Tambah Data</a> --}}
</div>

<!-- Content Row -->
<div class="container">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No Telp</th>
                <th class="text-center">Aksi</th>
            </tr>
            @forelse ($pasien as $row)
                <tr>
                    <th>{{ $row->pasien->pluck('nama_pasien') }}</th>
                    <th>{{ $row->surgery_name }}</th>
                    <th class="text-center">
                        <a href="{{ route('surgery.show', $row->id) }}" class="btn btn-primary">Show Detail</a>
                    </th>
                </tr>
            @empty

            @endforelse
        </table>
    </div>
</div>
@endsection
