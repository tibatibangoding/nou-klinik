@extends('layouts.dashboard')

@section('title')
    periksa
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah periksa</h1>
</div>

<!-- Content Row -->
<div class="page-inner mt--5">
	<div class="row">
		<div class="col-md-12">
			<div class="card full-height">
				<div class="card-header">
					<div class="card-head-row">
						<div class="card-title">Tambah periksa</div>
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
					<form action="{{ route('periksa.store') }}" method="POST" id="form-submit" enctype="multipart/form-data" name="form-obat">
						@csrf
                        <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id }}">
                        <div class="form-group">
							<label for="foto">Alergi</label>
							<input type="text" class="form-control" readonly value="{{ $pasien->alergi }}"></input>
						</div>
                        <div class="form-group">
							<label for="foto">Keluhan</label>
							<input type="text" class="form-control" readonly value="{{ $pendaftaran->keluhan }}"></input>
						</div>
                        <div class="card mb-4 ml-2">
                            <div class="card-header">Keluhan Terdahulu</div>
                            <div class="card-body">
                                @forelse ($pendaftaran_keluhan as $row)
                                    <div class="card mb-3">
                                        <div class="card-header">{{ $row->created_at->toDateString() }}</div>
                                        <div class="card-body">
                                            {{ $row->keluhan }}
                                        </div>
                                    </div>
                                @empty
                                    No Clinical Notes
                                @endforelse
                            </div>
                            @if($pendaftaran_keluhan->count())
                                <div class="row">
                                    <div class="col d-flex justify-content-center">
                                        {{ $pendaftaran_keluhan->links('layouts.paginationlinks') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
							<label for="foto">Pemeriksaan Fisik</label>
							<textarea class="form-control" style="height: 100px" required name="pemeriksaan_fisik"></textarea>
						</div>
                        <div class="form-group">
                            <label for="">Status Catatan Pemeriksaan Fisik</label>
                            <select name="status" class="form-control" id="">
                                <option value="1">Private</option>
                                <option value="2">Publik</option>
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
							<input type="text" class="form-control" name="file_description">
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
							<input type="text" class="form-control" name="radiology_description" placeholder="Contoh seperti nama dari tindakan radiologi yang di lakukan">
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
							<input type="text" class="form-control" name="investigation_description" placeholder="Contoh seperti hasil investigasi tentang tindakan ct scan">
						</div>
                        <br>
                        <h1>Tindakan Medis yang di Lakukan</h1>
                        <div class="form-group">
							<label for="foto">Jenis tindakan yang di lakukan</label>
							<select name="id_tindakan" id="" class="form-control selectobat">
                                <option value="">---- Pilih Jenis Tindakan Yang Akan Di Lakukan ----</option>
                                @foreach ($tindakan as $row)
                                    <option value="{{ $row->id }}">{{ $row->nama_tindakan }} | Rp. {{ number_format($row->biaya_tindakan) }}</option>
                                @endforeach
                            </select>
						</div>
                        <div class="form-group">
							<label for="foto">Detail tindakan yang di lakukan</label>
							<textarea class="form-control" name="detail_tindakan" placeholder="Detail tindakan yang di lakukan pada pasien" style="height: 100px"></textarea>
						</div>
                        <div class="form-group">
                            <label for="">Status Catatan Tindakan</label>
                            <select name="status_tindakan" class="form-control" id="">
                                <option value="1">Private</option>
                                <option value="2">Publik</option>
                            </select>
                        </div>
                        <br>
                        <h1>Diagnosis Dokter</h1>
                        <div class="form-group">
							<label for="foto">Detail diagnosis untuk pasien</label>
							<textarea class="form-control" name="diagnosis" placeholder="Detail diagnosis untuk pasien" style="height: 100px"></textarea>
						</div>
                        <div class="form-group">
                            <label for="">Status Catatan Diagnosis</label>
                            <select name="status_diagnosis" class="form-control" id="">
                                <option value="1">Private</option>
                                <option value="2">Publik</option>
                            </select>
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
                        <h1>Resep Dokter</h1>
                        <h5>* Pilih salah satu atau pilih kedua opsi di bawah ini untuk menginputkan data manual / mengunggah foto resep <b style="font-size: 20px" class="text-danger">(Tidak wajib di isi)</b></h5>
                        <div class="form-group">
							<label for="foto">Foto Resep</label>
							<input type="file" class="form-control" name="gambar">
						</div>
                        <div class="form-group">
							<label for="foto">Nama Resep</label>
							<input type="text" readonly value="{{ $nama_resep }}" class="form-control" name="nama_resep">
						</div>
                        <div class="form-group classAdded mb-2">
                            <div class="row select-obat">
                                <div class="col-md-5">
                                    <label for="exampleInputEmail1">Obat</label>
                                    <select name="id_obat[]" class="form-control selectobat">
                                        <option value="">---- Pilih Obat ----</option>
                                        @foreach ($obat as $row)
                                        @if ($row->id_jenis_obat != null)
                                            <option value="{{ $row->id }}">{{ $row->nama_obat }} / {{ $row->jenis->nama_jenis }} | Sisa stok : {{ $row->stok }}</option>
                                        @else
                                            <option value="{{ $row->id }}">{{ $row->nama_obat }} / - | | Sisa stok : {{ $row->stok }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 jumlahobat">
                                    <label for="exampleInputEmail1">Jumlah</label>
                                    <input type="number" name="jumlah[]" class="form-control">
                                </div>
                                <div class="col-md-1 addingClass" style="margin-top: 1.8rem">
                                    <a class="btn btn-success addClass" href="javascript:void(0)">+</a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <h1 class="annotation-h1">Image Annotation</h1>
                        <h5>* Jika ada yang ingin di tandai dari gambar tubuh berikut sebagai panduan untuk pemeriksaan selanjutnya <span class="text-danger">bersifat tidak wajib untuk di isi</span></h5>
                        <br>
                        <div class="form-group mb-4">
                            <label for="">Bagian yang ingin di tandai</label>
                            @if ($pasien->jenis_kelamin == 'L')
                                <select onchange="changeImage();" name="pilihan_annotation" class="form-control" id="annotation_choice">
                                    <option value="kepala_depan">Kepala Depan</option>
                                    <option value="kepala_belakang">Kepala Belakang</option>
                                    <option value="kepala_samping">Kepala Samping</option>
                                    <option value="perut_depan">Perut Depan</option>
                                    <option value="perut_belakang">Perut Belakang</option>
                                    <option value="perut_samping">Perut Samping</option>
                                    <option value="tangan_depan">Tangan Depan</option>
                                    <option value="tangan_belakang">Tangan Belakang</option>
                                    <option value="tangan_samping">Tangan Samping</option>
                                    <option value="dada_depan">Dada Depan</option>
                                    <option value="dada_belakang">Dada Belakang</option>
                                    <option value="dada_samping">Dada Samping</option>
                                    <option value="alat_vital">Alat Vital</option>
                                </select>
                            @else
                                <select onchange="changeImage();" name="pilihan_annotation" class="form-control" id="annotation_choice">
                                    <option value="kepala_depan">Kepala Depan</option>
                                    <option value="kepala_belakang">Kepala Belakang</option>
                                    <option value="kepala_samping">Kepala Samping</option>
                                    <option value="perut_depan">Perut Depan</option>
                                    <option value="perut_belakang">Perut Belakang</option>
                                    <option value="perut_samping">Perut Samping</option>
                                    <option value="tangan_depan">Tangan Depan</option>
                                    <option value="tangan_belakang">Tangan Belakang</option>
                                    <option value="tangan_samping">Tangan Samping</option>
                                    <option value="dada_depan">Dada Depan</option>
                                    <option value="dada_belakang">Dada Belakang</option>
                                    <option value="dada_samping">Dada Samping</option>
                                    <option value="alat_vital">Alat Vital</option>
                                </select>
                            @endif
                        </div>
                        @if ($poli == 'Gigi' || Auth::user()->spesialis == 'Gigi' || Auth::user()->spesialis == 'gigi')
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                                <img src="{{ url('image/odontogram.jpeg') }}" id="sourceImage" style="width: 640px;" />
                                <img src="{{ url('image/odontogram.jpeg') }}" id="sampleImage" style="width: 640px; position: absolute;" />
                            </div>
                        @elseif($pasien->jenis_kelamin == 'L')
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                                <img src="" id="sourceImage" style="width: 640px;" />
                                <img src="" id="sampleImage" style="width: 640px; position: absolute;" />
                            </div>
                        @elseif($pasien->jenis_kelamin == 'P')
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; padding-top: 50px;">
                                <img src="" id="sourceImage" style="width: 640px;" />
                                <img src="" id="sampleImage" style="width: 640px; position: absolute;" />
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
                if (result) {
                    form.submit();
                } else {
                    return false;
                }
            });
    })
    $('.selectobat').select2();
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
    $('form').on('click', '.addClass', function () {
        var form = "<div class='form-group classAdded'>"+
                            "<div class='row'>"+
                                "<div class='col-md-5'>"+
                                    "<label for='exampleInputEmail1'>Obat</label>"+
                                    "<select name='id_obat[]' class='form-control selectobat'>"+
                                        "@foreach ($obat as $row)"+
                                            "@if ($row->id_jenis_obat != null)"+
                                                "<option value='{{ $row->id }}'>{{ $row->nama_obat }} / {{ $row->jenis->nama_jenis }}</option>"+
                                            "@else"+
                                                "<option value='{{ $row->id }}'>{{ $row->nama_obat }} / -</option>"+
                                            "@endif"+
                                        "@endforeach"+
                                    "</select>"+
                                "</div>"+
                                "<div class='col-md-5 jumlahobat'>"+
                                    "<label for='exampleInputEmail1'>Jumlah</label>"+
                                    "<input type='number' name='jumlah[]' class='form-control'>"+
                                "</div>"+
                                "<div class='col-md-1' style='margin-top: 1.8rem'>"+
                                    "<a class='btn btn-danger removeClass' href='javascript:void(0)'>-</a>"+
                                "</div>"+
                            "</div>"+
                        "</div>"
        $(form).insertBefore('.annotation-h1');
    });
    $('form').on('click', '.removeClass', function() {
        $(this).parent().parent().remove();
    });
</script>
<script src="https://unpkg.com/markerjs2/markerjs2.js"></script>
<script>
    var changeImage = function changeImage() {
        if({{ $pasien->jenis_kelamin == 'L' }}) {
            var e = document.getElementById("annotation_choice");
            var value = e.value;
            var url = '/image/';
            switch(value) {
                case 'kepala_depan':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Kepala_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Kepala_Depan.png';
                    break;
                case 'kepala_belakang':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Kepala_Belakang.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Kepala_Belakang.png';
                    break;
                case 'kepala_samping':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Kepala_Samping.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Kepala_Samping.png';
                    break;
                case 'perut_depan':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Perut_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Perut_Depan.png';
                    break;
                case 'perut_belakang':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Perut_Belakang.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Perut_Belakang.png';
                    break;
                case 'perut_samping':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Perut_Samping.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Perut_Samping.png';
                    break;
                case 'tangan_depan':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Tangan_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Tangan_Depan.png';
                    break;
                case 'tangan_belakang':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Tangan_Belakang.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Tangan_Belakang.png';
                    break;
                case 'tangan_samping':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Tangan_Samping.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Tangan_Samping.png';
                    break;
                case 'dada_depan':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Dada_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Dada_Depan.png';
                    break;
                case 'dada_belakang':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Dada_Belakang.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Dada_Belakang.png';
                    break;
                case 'dada_samping':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Dada_Samping.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Dada_Samping.png';
                    break;
                case 'alat_vital':
                    document.getElementById('sourceImage').src = url + 'Annotation_Laki_Vital_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Laki_Vital_Depan.png';
                    break;
                default:
                    document.getElementById('sourceImage').src = url + 'couo.png';
                    document.getElementById('sampleImage').src = url + 'couo.png';
                    break;
            }
        } else {
            var e = document.getElementById("annotation_choice");
            var value = e.value;
            var url = '/storage/image/';
            switch(value) {
                case 'kepala_depan':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Kepala_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Kepala_Depan.png';
                    break;
                case 'kepala_belakang':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Kepala_Belakang.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Kepala_Belakang.png';
                    break;
                case 'kepala_samping':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Kepala_Samping.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Kepala_Samping.png';
                    break;
                case 'perut_depan':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Perut_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Perut_Depan.png';
                    break;
                case 'perut_belakang':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Perut_Belakang.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Perut_Belakang.png';
                    break;
                case 'perut_samping':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Perut_Samping.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Perut_Samping.png';
                    break;
                case 'tangan_depan':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Tangan_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Tangan_Depan.png';
                    break;
                case 'tangan_belakang':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Tangan_Belakang.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Tangan_Belakang.png';
                    break;
                case 'tangan_samping':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Tangan_Samping.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Tangan_Samping.png';
                    break;
                case 'dada_depan':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Dada_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Dada_Depan.png';
                    break;
                case 'dada_belakang':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Dada_Belakang.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Dada_Belakang.png';
                    break;
                case 'dada_samping':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Dada_Samping.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Dada_Samping.png';
                    break;
                case 'alat_vital':
                    document.getElementById('sourceImage').src = url + 'Annotation_Cewe_Vital_Depan.png';
                    document.getElementById('sampleImage').src = url + 'Annotation_Cewe_Vital_Depan.png';
                    break;
                default:
                    document.getElementById('sourceImage').src = url + 'ceue.png';
                    document.getElementById('sampleImage').src = url + 'ceue.png';
            }
        }
    }
</script>
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

