@extends('layouts.dashboard')

@section('title')
    Penjualan
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Resep</h1>
</div>

<!-- Content Row -->
<div class="container">
    <div class="row">
        @if ($detail_resep->count() <= 0 && $resep->foto_resep != null)
            <div class="col-md-12">
                <h4>Nama Resep : {{ $resep->nama_resep }}</h4>
                <h4>Nama Dokter : {{ $status_bayar->dokter->nama }}</h4>
                <h4>Nama Poli : {{ $status_bayar->poli->nama_poli }}</h4>
                <h4>Nomor Antrian : {{ $status_bayar->no_antrian }}</h4>
                @if ($resep->foto_resep != null)
                    <h3 class="mt-4">Foto Resep :</h3>
                    <img src="{{ asset('/storage/' . $resep->foto_resep) }}" height="100" class="mt-3" alt="">
                @endif
            </div>
            <div class="col-md-12 mt-4">
                <h2 class="">Daftar Racikan :</h2>
                <div class="table-responsive mt-2">
                    <a href="{{ route('resep.edit', $resep->id) }}" class="btn btn-primary">Tambah data resep!</a>
                    <table class="table mb-0 text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td colspan="3">Belum ada data resep! silahkan tambahkan.</td>
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($detail_resep->count() >= 1)
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr class="text-danger">
                                <td>
                                    <b>Nama Resep :</b>
                                </td>
                                <td colspan="1">
                                    <td>
                                        <b>{{ $resep->nama_resep }}</b>
                                    </td>
                                </td>
                            </tr>
                            <tr class="text-danger">
                                <td>
                                    <b>Nama Dokter :</b>
                                </td>
                                <td colspan="1">
                                    <td>
                                        <b>{{ $pendaftaran->dokter->nama }}</b>
                                    </td>
                                </td>
                            </tr>
                            <tr class="text-danger">
                                <td>
                                    <b>Nama Poli :</b>
                                </td>
                                <td colspan="1">
                                    <td>
                                        <b>{{ $pendaftaran->poli->nama_poli }}</b>
                                    </td>
                                </td>
                            </tr>
                            <tr class="text-danger">
                                <td>
                                    <b>Nomor Antrian :</b>
                                </td>
                                <td colspan="1">
                                    <td>
                                        <b>{{ $pendaftaran->no_antrian }}</b>
                                    </td>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if ($resep->foto_resep != null)
                    <h3 class="mt-4">Foto Resep :</h3>
                    <img src="{{ asset('/storage/' . $resep->foto_resep) }}" height="100" class="mt-3" alt="">
                @endif
            </div>
            <div class="col-md-12 mt-4">
                <h2 class="">Daftar Racikan :</h2>
                <div class="table-responsive mt-2">
                    <div class="table-responsive">
                        <table class="table mb-0 text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($detail_resep as $row)
                                    <tr>
                                        <td>{{ $row->obat->pluck('nama_obat')[0] }}</td>
                                        <td>{{ $row->jumlah }}</td>
                                        <td>Rp{{ number_format($row->obat->pluck('harga_jual')[0]) }}</td>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            <b>Total Harga Obat</b>
                                        </td>
                                        <td colspan="1">
                                            <td>
                                                <b>Rp{{ number_format($resep->total_harga) }}</b>
                                            </td>
                                        </td>
                                    </tr>
                                    @if ($pembayaran != null)
                                        <tr>
                                            <td>
                                                <b>Biaya Dokter</b>
                                            </td>
                                            <td colspan="1">
                                                <td>
                                                    <b>Rp. {{ number_format($pembayaran->biaya_dokter) }}</b>
                                                </td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Biaya Admin</b>
                                            </td>
                                            <td colspan="1">
                                                <td>
                                                    <b>Rp. {{ number_format($pembayaran->biaya_admin) }}</b>
                                                </td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Total Biaya</b>
                                            </td>
                                            <td colspan="1">
                                                <td>
                                                    <b>Rp. {{ number_format($pembayaran->total_biaya) }}</b>
                                                </td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Total Bayar</b>
                                            </td>
                                            <td colspan="1">
                                                <td>
                                                    <b>Rp. {{ number_format($pembayaran->total_bayar) }}</b>
                                                </td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Kembalian</b>
                                            </td>
                                            <td colspan="1">
                                                <td>
                                                    <b>Rp. {{ number_format($pembayaran->kembalian) }}</b>
                                                </td>
                                            </td>
                                        </tr>
                                    @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($status_bayar->status_bayar == '2')
                <a href="{{ route('pembayaran.create', $resep->id_pendaftaran) }}" class="btn btn-primary mt-2">Pembayaran</a>
                @endif
            </div>
        @elseif($detail_resep->count() <= 0 && $resep->foto_resep == null)
            <div class="table-responsive">
                <table class="table mb-0 text-center">
                    <tbody>
                            <td>Pasien tersebut tidak memerlukan resep apapun!</td>
                    </tbody>
                </table>
            </div>
            @if ($status_bayar->status_bayar == '2')
            <a href="{{ route('pembayaran.create', $resep->id_pendaftaran) }}" class="btn btn-primary mt-2">Pembayaran</a>
            @endif
        @endif
    </div>
</div>
@endsection
