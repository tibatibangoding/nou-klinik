@extends('layouts.dashboard')

@section('title')
Detail Pemeriksaan
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Detail Pemeriksaan</h1>
</div>

<!-- Content Row -->
<div class="container">
  <div class="row d-flex">
    <div class="col-md-12" style="margin-left:-20px">
      <div class="card mb-4">
        <div class="card-header">
          <h3 class="text-success">{{ $pasien->nama_pasien }},</h3>
          <h5>({{ $pasien->jenis_kelamin }} | {{ Carbon\Carbon::parse($pasien->tgl_lahir)->age }}
            ({{ $pasien->tgl_lahir }}))</h5>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card mb-4 ml-2">
        <div class="card-header">Clinical Notes</div>
        <div class="card-body">
          @forelse ($cppt_single as $row)
          <div class="card">
            <div class="card-header">{{ $row->user->nama }}</div>
            <div class="card-body">
              {{ $row->clinical_notes }}
            </div>
          </div>
          @empty
          No Clinical Notes
          @endforelse
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
        <div class="card-header">CPPT</div>
        <div class="card-body">
          @forelse ($cppt as $row)
          <div class="card">
            <div class="card-header">{{ $row->user->nama }}</div>
            <div class="card-body">
              {{ $row->clinical_notes }}
            </div>
          </div>
          @empty
          No Clinical Notes
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
          <div class="card">
            <div class="card-header">Surgery By : {{ $row->user->nama }} | {{ $row->surgery_name }}</div>
            <div class="card-body">{{ $row->surgery_details }}</div>
          </div>
          @empty
          No Surgeries
          @endforelse
        </div>
      </div>
    </div>
    @if ($detail_resep->count() <= 0) <div class="col-md-12">
      <h4>Nama Resep : {{ $resep->nama_resep }}</h4>
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
    <h4>Nama Resep : {{ $resep->nama_resep }}</h4>
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
                <b>Total Harga Resep</b>
              </td>
              <td colspan="1">
              <td>
                <b>Rp{{ number_format($resep->total_harga) }}</b>
              </td>
              </td>
            </tr>

            <tr>
              <td>
                <b>Biaya Dokter</b>
              </td>
              <td colspan="1">
              <td>
                <b>Rp{{ number_format($pembayaran->biaya_dokter) }}</b>
              </td>
              </td>
            </tr>
            <tr>
              <td>
                <b>Biaya Admin</b>
              </td>
              <td colspan="1">
              <td><b>Rp{{ number_format($pembayaran->biaya_admin) }}</b></td>
              </td>
            </tr>
            <tr>
              <td>
                <b>Total Harga Keseluruhan</b>
              </td>
              <td colspan='1'>
              <td>
                <b>Rp{{ number_format($pembayaran->total_biaya) }}</b>
              </td>
              </td>
            </tr>
            <tr>
              <td>
                @if ($pembayaran->status_bayar == 1)
                <b class="text-success">Status Pembayaran : Lunas</b>
                @else
                <b class="text-success">Status Pembayaran : Belum Lunas / DP</b>
                @endif
              </td>
              <td colspan='1'>
              <td>

              </td>
              </td>
            </tr>
            <tr>
              <td>
                <b>Jumlah Dibayar</b>
              </td>
              <td colspan='1'>
              <td>
                <b>Rp{{ number_format($pembayaran->total_bayar) }}</b>
              </td>
              </td>
            </tr>
            <tr>
              <td>
                <b>Kembalian</b>
              </td>
              <td colspan='1'>
              <td>
                <b>Rp{{ number_format($pembayaran->kembalian) }}</b>
              </td>
              </td>
            </tr>
            <tr>
              <td>
                <b>Petugas Pembayaran : {{ $pembayaran->apoteker->nama }}</b>
              </td>
              <td colspan='1'>
              <td>
                <b>Rp{{ number_format($pembayaran->total_biaya) }}</b>
              </td>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @elseif($detail_resep == null && $resep->foto_resep == null)
  <div class="table-responsive">
    <table class="table mb-0 text-center">
      <tbody>
        <td>Pasien tersebut tidak memerlukan resep!</td>
      </tbody>
    </table>
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
  markerArea.restoreState({
    !!$periksa['annotation_data'] !!
  });
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