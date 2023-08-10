@extends('layouts.dashboard')

@section('title')
    periksa
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit periksa</h1>
</div>

<!-- Content Row -->
<div class="page-inner mt--5">
	<div class="row">
		<div class="col-md-12">
			<div class="card full-height">
				<div class="card-header">
					<div class="card-head-row">
						<div class="card-title">Edit periksa</div>
					</div>
				</div>

				<div class="card-body">
                    <div class="col-md-12" style="margin-left:-20px">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="text-success">{{ $pasien->nama_pasien }} | {{ $pasien->alamat }} | {{ $pasien->no_telp }} | {{ $pasien->agama }} | {{ $pasien->kewarganegaraan }} | {{ $pasien->pekerjaan }}</h3> <h5>({{ $pasien->jenis_kelamin }} | {{ Carbon\Carbon::parse($pasien->tgl_lahir)->age }} ({{ $pasien->tgl_lahir }}))</h5>
                            </div>
                        </div>
                    </div>
					<form action="{{ route('periksa.update', $periksa->id) }}" method="POST" id="form-submit" enctype="multipart/form-data" name="form-obat">
						@csrf
						@method('PUT')
                        <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id }}">
                        <div class="form-group">
							<label for="foto">Alergi</label>
							<input type="text" class="form-control" value="{{ $pasien->alergi }}"></input>
						</div>
                        <div class="form-group">
							<label for="foto">Keluhan</label>
							<input type="text" class="form-control" value="{{ $pendaftaran->keluhan }}"></input>
						</div>
                        <div class="form-group">
							<label for="foto">Catatan Dokter</label>
							<textarea class="form-control" required name="catatan_dokter">{{ $cppt->clinical_notes }}</textarea>
						</div>
                        <div class="form-group">
                            <label for="">Status Catatan</label>
                            <select name="status" class="form-control" id="">
                                @if ($cppt->status == 1)
									<option value="1" selected>Private</option>
                                    <option value="2">Publik</option>
								@elseif($cppt->status == 2)
                                    <option value="1">Private</option>
									<option value="2" selected>Publik</option>
                                @else
                                    <option value="1">Private</option>
                                    <option value="2">Publik</option>
								@endif
                            </select>
                        </div>
                        <br>
                        <h1 class="file-pendukung">File pendukung untuk pasien</h1>
                        <h5>* Tambahkan file berupa gambar atau dokumen yang merupakan pendukung untuk pasien seperti file tindakan medis dan sejenis jika ada, jika tidak ada maka boleh di kosongkan</h5>
                        <div class="form-group">
							<label for="foto">File Pendukung</label>
							<input type="file" class="form-control" name="files">
						</div>
                        <div class="form-group">
							<label for="foto">Deskripsi untuk file pendukung</label>
							@if ($files != null && $files->file_description != null)
                                <input type="text" class="form-control" name="file_description" value="{{ $files->file_description }}">
                            @else
                                <input type="text" class="form-control" name="file_description">
                            @endif
						</div>
                        <br>
                        <h1>Radiology Reports / Laporan Radiologi</h1>
                        <h5>* Tambahkan file berupa gambar atau dokumen yang merupakan hasil dari laporan radiologi pasien yang telah di lakukan sebelumnya <span class="text-danger">jika ada tindakan radiologi</span>, jika tidak ada maka boleh di kosongkan</h5>
                        <div class="form-group">
							<label for="foto">File Laporan Radiologi</label>
                            <img class="img-preview img-fluid mb-5 mt-5 col-sm-4">
							<input type="file" class="form-control" name="radiology_files" id="img-radio" onchange="previewImage()">
						</div>
                        <div class="form-group">
							<label for="foto">Deskripsi untuk file radiologi</label>
							@if ($radiology != null && $radiology->radiology_description != null)
                                <input type="text" class="form-control" name="radiology_description" placeholder="Contoh seperti nama dari tindakan radiologi yang di lakukan" value="{{ $radiology->radiology_description }}">
                            @else
                                <input type="text" class="form-control" name="radiology_description" placeholder="Contoh seperti nama dari tindakan radiologi yang di lakukan">
                            @endif
						</div>
                        <br>
                        <h1>Investigation Reports / Laporan Investigasi Dokter</h1>
                        <h5>* Tambahkan file berupa gambar atau dokumen yang merupakan hasil dari investigasi yang telah di lakukan oleh dokter, seperti hasil ct scan yang telah di bacakan oleh dokter dan sejenisnya <span class="text-danger">jika ada hasil investigasi</span>, jika tidak ada maka boleh di kosongkan</h5>
                        <div class="form-group">
							<label for="foto">File Laporan Investigasi</label>
							<input type="file" class="form-control" name="investigation_files">
						</div>
                        <div class="form-group">
							<label for="foto">Deskripsi untuk file investigasi</label>
							@if ($investigation != null && $investigation->investigation_description != null)
                                <input type="text" class="form-control" name="investigation_description" placeholder="Contoh seperti hasil investigasi tentang tindakan ct scan" value="{{ $investigation->investigation_description }}">
                            @else
                                <input type="text" class="form-control" name="investigation_description" placeholder="Contoh seperti hasil investigasi tentang tindakan ct scan">
                            @endif
						</div>
                        <br>
                        <h1>Surgery / Operasi</h1>
                        <h5>* Tambahkan data data operasi <span class="text-danger">jika ada tindakan operasi yang di perlukan</span>, jika tidak ada maka boleh di kosongkan</h5>
                        <div class="form-group">
							<label for="foto">Tindakan Operasi</label>
							<input type="text" class="form-control" name="surgery_name">
						</div>
                        <div class="form-group">
							<label for="foto">Deskripsi untuk tindakan operasi</label>
							<input type="text" class="form-control" name="surgery_detail">
						</div>
                        <br>
                        <br>
                        <h1 class="annotation-h1">Image Annotation</h1>
                        <h5>* Jika ada yang ingin di tandai dari gambar tubuh berikut sebagai panduan untuk pemeriksaan selanjutnya <span class="text-danger">bersifat tidak wajib untuk di isi</span></h5>
                        @if ($poli == 'Gigi' || Auth::user()->spesialis == 'Gigi' || Auth::user()->spesialis == 'gigi')
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                            <!-- we are putting a copy of the original image under the result image so it's always annotation-free -->
                                <img src="{{ url('image/odontogram.jpeg') }}" id="sourceImage" style="width: 640px;" />
                                <img src="{{ url('image/odontogram.jpeg') }}" id="sampleImage" style="width: 640px; position: absolute;" />
                            </div>
                        @elseif($pasien->jenis_kelamin == 'L')
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                            <!-- we are putting a copy of the original image under the result image so it's always annotation-free -->
                                <img src="{{ url('image/couo.png') }}" id="sourceImage" style="width: 640px;" />
                                <img src="{{ url('image/couo.png') }}" id="sampleImage" style="width: 640px; position: absolute;" />
                            </div>
                        @elseif($pasien->jenis_kelamin == 'P')
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                            <!-- we are putting a copy of the original image under the result image so it's always annotation-free -->
                                <img src="{{ url('image/ceue.png') }}" id="sourceImage" style="width: 640px;" />
                                <img src="{{ url('image/ceue.png') }}" id="sampleImage" style="width: 640px; position: absolute;" />
                            </div>
                        @endif
                        <input type="hidden" name="annotation_data" id="annotation-data">
                        <input type="hidden" name="annotation_data_wajah" id="annotation-data2">
                        <br>
                        <div class="form-group">
                            <button class="btn btn-primary subm sub-sub btn-sm" type="submit">Selesai Periksa</button>
                        </div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('.sub-sub').click(function(event) {
        var form = $('#form-submit');
        event.preventDefault();
        Swal.fire({
                title: 'Yakin mengirimkan form?',
                text: "Form akan langsung di kirimkan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, kirim!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else if(result.isDismissed) {
                    return Swal.close();
                }
            });
    })
    function previewImage() {
        const image = document.querySelector('#img-radio');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }
</script>
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
    if({!! $periksa['annotation_data'] != null !!}) {
        markerArea.restoreState({!! $periksa['annotation_data'] !!});
    }
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
    function saveMarkerState(states) {
        state = states;
        console.log(state)
        document.getElementById('annotation-data').value = JSON.stringify(states);
    }
</script>
@endpush

