@extends('layouts.dashboard')

@section('title')
    Detail Pasien
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pasien</h1>
    
</div>

<!-- Content Row -->
<div class="container">
    <div class="row d-flex">
        <div class="col-md-12" style="margin-left:-20px">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="text-success">{{ $pasien->nama_pasien }},</h3> <h5>({{ $pasien->jenis_kelamin }} | {{ Carbon\Carbon::parse($pasien->tgl_lahir)->age }} ({{ $pasien->tgl_lahir }}))</h5>
                </div>
            </div>
        </div>
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
