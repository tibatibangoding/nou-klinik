@extends('layouts.dashboard')

@section('title')
    Detail Pasien
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pasien</h1>
    <a href="{{ route('pasien.showAll', $pasien->id) }}" class="btn btn-primary">Detail seluruh pemeriksaan</a>
    @if (Auth::user()->roles->pluck('name')[0] == 'admin')
        <a href="{{ route('periksa.edit', $periksa->id) }}" class="btn btn-primary">Edit Pemeriksaan</a>
    @endif
</div>

<!-- Content Row -->
<div class="container">
    <div class="row d-flex">
        <div class="col-md-12" style="margin-left:-20px">
            <div class="card mb-4">
                <div class="card-header">
                <h3 class="text-success"><span class="text-dark">Nama Pasien : </span>{{ $pasien->nama_pasien }}, <span class="text-dark">Alergi Pasien : </span>{{ $pasien->alergi }}</h3> <h5>({{ $pasien->jenis_kelamin }} | {{ Carbon\Carbon::parse($pasien->tgl_lahir)->age }} ({{ $pasien->tgl_lahir }}) | Keluhan : {{ $pendaftaran->keluhan }} | Nomor Antrian : {{ $pendaftaran->no_antrian }})</h5>
                </div>
            </div>
        </div>

        @if ($periksa != null)
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Catatan Pemeriksaan Fisik Saat Periksa</div>
                <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-header">{{ $cppt_single->user->nama }}</div>
                            <div class="card-body">
                                {{ $cppt_single->pemeriksaan_fisik }}
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Catatan Diagnosis Saat Periksa</div>
                <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-header">{{ $diagnosis_single->user->nama }}</div>
                            <div class="card-body">
                                {{ $diagnosis_single->diagnosis }}
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Catatan Tindakan Saat Periksa</div>
                <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-header">{{ $tindakan_single->user->nama }} | Jenis Tindakan : {{ $tindakan_single->tindakan->nama_tindakan }}</div>
                            <div class="card-body">
                                {{ $tindakan_single->detail_tindakan }}
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Files</div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered">
                            @forelse ($files as $row)
                                <tr>
                                    <td>{{ $row->description }}</td>
                                    <td>{{ $row->created_at }}</td>
                                    <td><a href="{{ '/storage/' . $row->files }}">View files</a></td>
                                </tr>
                            @empty
                                No Files
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Seluruh catatan pemeriksaan fisik yang di publik</div>
                <div class="card-body">
                    @forelse ($cppt as $row)
                        <div class="card mb-3">
                            <div class="card-header">{{ $row->user->nama }}</div>
                            <div class="card-body">
                                {{ $row->pemeriksaan_fisik }}
                            </div>
                        </div>
                    @empty
                        Tidak ada catatan pemeriksaan fisik yang di publik
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Seluruh catatan diagnosis dokter yang di publik</div>
                <div class="card-body">
                    @forelse ($diagnosis as $row)
                        <div class="card mb-3">
                            <div class="card-header">{{ $row->user->nama }}</div>
                            <div class="card-body">
                                {{ $row->diagnosis }}
                            </div>
                        </div>
                    @empty
                        Tidak ada catatan diagnosis dokter yang di publik
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Seluruh catatan detail tindakan yang di publik</div>
                <div class="card-body">
                    @forelse ($tindakan as $row)
                        <div class="card mb-3">
                            <div class="card-header">{{ $row->user->nama }} | Jenis Tindakan : {{ $row->tindakan->nama_tindakan }}</div>
                            <div class="card-body">
                                {{ $row->detail_tindakan }}
                            </div>
                        </div>
                    @empty
                        Tidak ada catatan tindakan yang di publik
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Radiology Reports</div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered">
                            @forelse ($radiology as $row)
                                <tr>
                                    <td>{{ $row->description }}</td>
                                    <td>{{ $row->created_at }}</td>
                                    <td><a href="{{ '/storage/' . $row->files }}">View files</a></td>
                                </tr>
                            @empty
                                No Radiologies History
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 ml-2">
                <div class="card-header">Investigation Reports</div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered">
                            @forelse ($investigation as $row)
                                <tr>
                                    <td>{{ $row->description }}</td>
                                    <td>{{ $row->created_at }}</td>
                                    <td><a href="{{ '/storage/' . $row->files }}">View files</a></td>
                                </tr>
                            @empty
                                No Investigations
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if ($periksa->annotation_data != null)
            <div class="col-md-6">
                @if ($poli == 'Gigi' || Auth::user()->spesialis == 'Gigi' || Auth::user()->spesialis == 'gigi')
                    <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                        <img src="{{ url('image/odontogram.jpeg') }}" id="sourceImage" style="width: 640px;" />
                        <img src="{{ url('image/odontogram.jpeg') }}" id="sampleImage" style="width: 640px; position: absolute;" />
                    </div>
                @elseif($pasien->jenis_kelamin == 'L')
                    <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                        <img src="{{ url('image/couo.png') }}" id="sourceImage" style="width: 640px;" />
                        <img src="{{ url('image/couo.png') }}" id="sampleImage" style="width: 640px; position: absolute;" />
                    </div>
                @elseif($pasien->jenis_kelamin == 'P')
                    <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                        <img src="{{ url('image/ceue.png') }}" id="sourceImage" style="width: 640px;" />
                        <img src="{{ url('image/ceue.png') }}" id="sampleImage" style="width: 640px; position: absolute;" />
                    </div>
                @endif
            </div>
        @else

        @endif
        <div class="col-md-12 mt-3">
            <div class="card mb-4">
                <div class="card-header">Surgery Details</div>
                <div class="card-body">
                    @forelse ($surgery as $row)
                        <div class="card mb-3">
                            <div class="card-header">Surgery By : {{ $row->user->nama }} | {{ $row->surgery_name }}</div>
                            <div class="card-body">{{ $row->surgery_details }}</div>
                        </div>
                    @empty
                        No Surgeries
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="card mb-4">
                <div class="card-header">Tanggal Periksa : {{ $periksa->created_at->toDateString() }}</div>
            </div>
            @if ($detail_resep != null)
                <div class="table table-responsive">
                    <table class="table table-bordered">
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
                                        <b>Total</b>
                                    </td>
                                    <td colspan="1">
                                        <td>
                                            <b>Rp{{ number_format($resep->total_harga) }}</b>
                                        </td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Status Bayar</b>
                                    </td>
                                    <td colspan="1">
                                        <td>
                                            @if($pendaftaran->status_bayar == 2)
                                                <b class="text-danger">Belum Bayar</b>
                                            @elseif($pendaftaran->status_bayar == 1 && $pembayaran->status_bayar == 1)
                                                <b class="text-success">Lunas</b>
                                            @elseif($pendaftaran->status_bayar == 1 && $pembayaran->status_bayar == 2)
                                                <b class="text-success">Belum Lunas / DP</b>
                                            @endif
                                        </td>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
@if ($periksa != null && $periksa->annotation_data != null)
@push('scripts')
<script src="https://unpkg.com/markerjs2/markerjs2.js"></script>
<script>
    let sourceImage, targetRoot, maState, state;

    // save references to the original image and its parent div (positioning root)
    function setSourceImage(source) {
    sourceImage = source;
    targetRoot = source.parentElement;
    }

    function showMarkerArea(target) {

    const markerArea = new markerjs2.MarkerArea(sourceImage);
    // since the container div is set to position: relative it is now our positioning root
    // end we have to let marker.js know that
    markerArea.targetRoot = targetRoot;
    markerArea.addEventListener("render", (event) => {
        target.src = event.dataUrl;
        // save the state of MarkerArea
        maState = event.state;
    });
    markerArea.addEventListener("markerselect", () => {
        saveMarkerState(markerArea.getState());
    });
    markerArea.show();
    markerArea.restoreState({!! $periksa['annotation_data'] !!});
    // if previous state is present - restore it

    if (maState) {
        console.log(maState);
        markerArea.restoreState(maState);
    }
    }
    setSourceImage(document.getElementById("sourceImage"));
    const sampleImage = document.getElementById("sampleImage");
    sampleImage.addEventListener("click", () => {
        showMarkerArea(sampleImage);
    });
// function saveMarkerState(states) {
//     state = states;
//     console.log(state)
//     document.getElementById('annotation-data').value = JSON.stringify(states);
// }
</script>
@endpush
@endif
