@extends('layouts.dashboard')

@section('title')
pendaftaran
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">pendaftaran</h1>

  <a href="{{ route('pendaftaran.create') }}" class="btn text-white" style="background-color: #a979a8;"
    onmouseover="this.style.backgroundColor='#6c5576';" onmouseout="this.style.backgroundColor='#a979a8';">Tambah
    Data</a>
</div>

<!-- Content Row -->
<div class="container">
  <div class="table-responsive">
    <table class="table table-bordered">
      <tr>
        <th>Nama Pasien</th>
        <th>Nama Poli</th>
        <th>Nama Dokter</th>
        <th>Status Periksa</th>
        <th class="text-center">Aksi</th>
      </tr>
      @forelse ($pendaftaran as $row)
      <tr>
        <th>{{ $row->pasien->nama_pasien }}</th>
        <th>{{ $row->poli->nama_poli }}</th>
        <th>{{ $row->dokter->nama }}</th>
        <th>{{ $row->status_periksa }}</th>
        <th class="text-center">
          @if ($row->status_periksa == 2)
          <a href="{{ route('periksa.create', $row->id) }}" class="btn text-white" style="background-color: #a979a8;"
            onmouseover="this.style.backgroundColor='#6c5576';"
            onmouseout="this.style.backgroundColor='#a979a8';">Periksa Pasien</a> |
          @else

          @endif
        </th>
      </tr>
      @empty

      @endforelse
    </table>
  </div>
</div>
@endsection