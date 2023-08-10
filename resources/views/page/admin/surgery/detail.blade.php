@extends('layouts.dashboard')

@section('title')
    Kandidat
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Kandidat</h1>
</div>

<!-- Content Row -->
<div class="container">
    <div class="row d-flex">
        <div class="col-md-12" style="margin-left:-20px">
            <h3 class="text-success">{{ $surgery->pasien->pluck('nama_pasien') }}</h3>
            <button class="btn btn-primary"><a href="{{ route('surgery.edit', $surgery->id) }}">Edit Data Operasi</a></button>
        </div>

        <div class="col-md-12">
            <h3>Surgery Name : {{ $surgery->surgery_name }}</h3>
            @if ($surgery->status == '2')
                <h3>Surgery Status : Operasi Belum Selesai Dilaksanakan</h3>
            @elseif($surgery->status == '1')
                <h3>Surgery Status : Operasi Sudah Selesai Dilaksanakan</h3>
            @endif
            <br>
            <br>
            <br>
            <br>
            <h3>Surgery Detail : </h3>
            <p>{{ $surgery->surgery_detail }}</p>
        </div>
    </div>
</div>
@endsection
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
      markerArea.restoreState({!! json_decode($periksa->annotation_data) !!});
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
