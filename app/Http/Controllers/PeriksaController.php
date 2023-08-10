<?php

namespace App\Http\Controllers;

use App\Exports\ObatExport;
use App\Exports\PasienExport;
use App\Exports\PeriksaExport;
use Carbon\Carbon;
use App\Models\CPPT;
use App\Models\Obat;
use App\Models\Poli;
use App\Models\Files;
use App\Models\Resep;
use App\Models\Pasien;
use App\Models\Periksa;
use App\Models\Surgery;
use App\Models\Tindakan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use App\Models\RadiologyReports;
use Illuminate\Support\Facades\DB;
use App\Models\InvestigationReports;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class PeriksaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pendaftaran = Pendaftaran::all();
        return view('page.admin.periksa.index', compact('pendaftaran'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $ubah = Pendaftaran::where('id', $id)->first();
        if ($ubah->status_periksa == 3 || $ubah->status_periksa == 2) {
            $update = $ubah->update([
                'status_periksa' => 2,
            ]);
            $periksa = Periksa::all();
            $tindakan = Tindakan::all();
            $pendaftaran = Pendaftaran::where('id', $id)->first();
            $pendaftaran_keluhan = Pendaftaran::where('id_pasien', $pendaftaran->id_pasien)->paginate(3);
            // dd($pendaftaran_keluhan->count());
            $pasien = Pasien::where('id', $pendaftaran->id_pasien)->first();
            // dd($pasien->jenis_kelamin);
            $nama_resep = $pasien->nama_pasien . ' ' . Carbon::now()->toDateString() . ' ' . $pendaftaran->nomor_antrian;
            $obat = Obat::where('stok', '>=', 1)->get();
            $polis = Poli::where('id', $pendaftaran->id_poli)->first();
            $poli = $polis->pluck('nama_poli')[0];
            // dd($poli);
            return view('page.admin.periksa.create', compact('periksa', 'tindakan', 'pendaftaran_keluhan', 'pendaftaran', 'pasien', 'obat', 'poli', 'nama_resep'));
        } else {
            Alert::error('Pasien ini telah di Periksa', 'Sebelumnya pasien ini telah selesai di periksa');
            return redirect()->route('pendaftaran.all');
        }
    }

    public function export()
    {
        return Excel::download(new PeriksaExport, 'periksa.xlsx');
    }

    public function exportPasien($id)
    {
        return Excel::download(new PasienExport($id), 'periksa.xlsx');
    }
    public function exportObat()
    {
        return Excel::download(new ObatExport, 'periksa.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($request->annotation_data);
        // dd(count($request->id_obat));
        if ($request->id_obat[0] != null && empty($request->file('gambar'))) {
            $total = [];
            $id_obat = $request->id_obat;
            for ($i = 0; $i < count($id_obat); $i++) {
                $i - 1;
                $datasave = [
                    'data_obat' => $id_obat,
                ];
                $req = $request->all();
                $data = Obat::whereIn('id', $id_obat)->get();
                $total_harga = ['total_harga' => $data[$i]->harga_jual * $request->jumlah[$i]];
                // dd($data[$i]_jua;);
                array_push($total, $total_harga);
            }
            $value = array_sum(array_column($total, 'total_harga'));
            // dd($value);
            $makeresep = Resep::create([
                'nama_resep' => $request->nama_resep,
                'id_pendaftaran' => $request->id_pendaftaran,
                'total_harga' => $value,
            ]);
            if ($makeresep) {
                $id_obat = $request->id_obat;
                $quantity = $request->jumlah;
                for ($i = 0; $i < count($id_obat); $i++) {
                    $i - 1;
                    $datas = Obat::whereIn('id', $id_obat)->get();
                    $data = [
                        'id_resep' => $makeresep->id,
                        'id_obat' => $id_obat[$i],
                        'jumlah' => $quantity[$i],
                        'harga' => $datas[$i]->harga_jual,
                    ];
                    DB::table('detail_resep')->insert($data);
                }
            }
            // $data['id_pendaftaran'] = $request->id_pendaftaran;
            $data = $request->all();
            $data['id_resep'] = $makeresep->id;
            $pendaftaran = Pendaftaran::where('id', $request->id_pendaftaran)->first();
            $data['id_pasien'] = $pendaftaran->id_pasien;
            $data['id_dokter'] = Auth::id();
            $create = Periksa::create($data);
            if ($create) {
                $ubah = Pendaftaran::where('id', $request->id_pendaftaran)->first();
                $update = $ubah->update([
                    'status_periksa' => 1,
                ]);
                if ($request->annotation_data != null) {
                    $pasien = Pasien::where('id', $ubah->id_pasien)->first();
                    $pasien->update([
                        'annotation_data' => $request->annotation_data,
                    ]);
                }
                if (!empty($request->file('files')) && !empty($request->file_description)) {
                    // $datafiles = $request->all();
                    $datafiles['files'] = $request->file('files')->store('uploads/files');
                    $datafiles['description'] = $request->file_description;
                    $datafiles['id_pasien'] = $pasien->id;
                    $datafiles['id_user'] = Auth::id();
                    $datafiles['id_periksa'] = $create->id;
                    // dd($datafiles);
                    Files::create($datafiles);
                } elseif (empty($request->file('files')) && !empty($request->file_description)) {
                    Alert::error('Tidak bisa', 'Jika file pendukung tidak memiliki dokumen atau apapun yang lain, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                    $dataradiology['files'] = $request->file('radiology_files')->store('uploads/radiology_files');
                    $dataradiology['description'] = $request->radiology_description;
                    $dataradiology['id_pasien'] = $pasien->id;
                    $dataradiology['id_user'] = Auth::id();
                    $dataradiology['id_periksa'] = $create->id;
                    RadiologyReports::create($dataradiology);
                } elseif (empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                    Alert::error('Tidak bisa', 'Jika tidak ada file mengenai laporan radiologi, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->file('investigation_files')) && !empty($request->investigation_description)) {
                    $datainvestigation = $request->all();
                    $datainvestigation['files'] = $request->file('investigation_files')->store('uploads/investigation_files');
                    $datainvestigation['description'] = $request->investigation_description;
                    $datainvestigation['id_pasien'] = $pasien->id;
                    $datainvestigation['id_user'] = Auth::id();
                    $datainvestigation['id_periksa'] = $create->id;
                    InvestigationReports::create($datainvestigation);
                } elseif (empty($request->file('investigation_files')) && !empty($request->investigation_description)) {
                    Alert::error('Tidak bisa', 'Jika tidak ada file mengenai laporan radiologi, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->surgery_name)) {
                    $surgery = Surgery::create([
                        'surgery_name' => $request->surgery_name,
                        'surgery_detail' => $request->surgery_detail,
                        'id_pasien' => $ubah->id_pasien,
                        'id_periksa' => $create->id,
                        'id_user' => Auth::id(),
                        'status' => 2,
                    ]);
                }
                $cppt = CPPT::create([
                    'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                    'status' => $request->status,
                    'id_user' => Auth::id(),
                    'id_periksa' => $create->id,
                    'id_pasien' => $ubah->id_pasien,
                ]);
                if ($cppt) {
                    $periksa = Periksa::where('id', $cppt->id_periksa)->first();
                    $periksa->update([
                        'id_cppt' => $cppt->id,
                    ]);
                }
                Alert::success('Pemeriksaan Berhasil', 'Pemeriksaan Berhasil');
                return redirect()->route('pendaftaran.all');
            } else {
                Alert::error('Pemeriksaan gagal', 'Data gagal di buat, silahkan isi kembali data sesuai!');
                return redirect()->back();
            }
        } elseif ($request->id_obat[0] == null && !empty($request->file('gambar'))) {
            $makeresep = Resep::create([
                'nama_resep' => $request->nama_resep,
                'foto_resep' => $request->file('gambar')->store('uploads/resep'),
                'id_pendaftaran' => $request->id_pendaftaran,
            ]);
            $data = $request->all();
            $data['id_resep'] = $makeresep->id;
            $pendaftaran = Pendaftaran::where('id', $request->id_pendaftaran)->first();
            $data['id_pasien'] = $pendaftaran->id_pasien;
            $data['id_dokter'] = Auth::id();
            $create = Periksa::create($data);
            if ($create) {
                $ubah = Pendaftaran::where('id', $request->id_pendaftaran)->first();
                $update = $ubah->update([
                    'status_periksa' => 1,
                ]);
                if ($request->annotation_data != null) {
                    $pasien = Pasien::where('id', $ubah->id_pasien)->first();
                    $pasien->update([
                        'annotation_data' => $request->annotation_data,
                    ]);
                }
                if (!empty($request->file('files')) && !empty($request->file_description)) {
                    // $datafiles = $request->all();
                    $datafiles['files'] = $request->file('files')->store('uploads/files');
                    $datafiles['description'] = $request->file_description;
                    $datafiles['id_pasien'] = $pasien->id;
                    $datafiles['id_user'] = Auth::id();
                    $datafiles['id_periksa'] = $create->id;
                    // dd($datafiles);
                    Files::create($datafiles);
                } elseif (empty($request->file('files')) && !empty($request->file_description)) {
                    Alert::error('Tidak bisa', 'Jika file pendukung tidak memiliki dokumen atau apapun yang lain, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                    $dataradiology['files'] = $request->file('radiology_files')->store('uploads/radiology_files');
                    $dataradiology['description'] = $request->radiology_description;
                    $dataradiology['id_pasien'] = $pasien->id;
                    $dataradiology['id_user'] = Auth::id();
                    $dataradiology['id_periksa'] = $create->id;
                    RadiologyReports::create($dataradiology);
                } elseif (empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                    Alert::error('Tidak bisa', 'Jika tidak ada file mengenai laporan radiologi, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->file('investigation_files')) && !empty($request->investigation_description)) {
                    $datainvestigation['files'] = $request->file('investigation_files')->store('uploads/investigation_files');
                    $datainvestigation['description'] = $request->investigation_description;
                    $datainvestigation['id_pasien'] = $pasien->id;
                    $datainvestigation['id_user'] = Auth::id();
                    $datainvestigation['id_periksa'] = $create->id;
                    InvestigationReports::create($datainvestigation);
                } elseif (empty($request->file('investigation_files')) && !empty($request->investigation_description)) {
                    Alert::error('Tidak bisa', 'Jika tidak ada file mengenai laporan radiologi, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->surgery_name)) {
                    $surgery = Surgery::create([
                        'surgery_name' => $request->surgery_name,
                        'surgery_detail' => $request->surgery_detail,
                        'id_pasien' => $ubah->id_pasien,
                        'id_periksa' => $create->id,
                        'id_user' => Auth::id(),
                        'status' => 2,
                    ]);
                }
                $cppt = CPPT::create([
                    'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                    'status' => $request->status,
                    'id_user' => Auth::id(),
                    'id_periksa' => $create->id,
                    'id_pasien' => $ubah->id_pasien,
                ]);
                if ($cppt) {
                    $periksa = Periksa::where('id', $cppt->id_periksa)->first();
                    $periksa->update([
                        'id_cppt' => $cppt->id,
                    ]);
                }
                Alert::success('Pemeriksaan Berhasil', 'Pemeriksaan Berhasil');
                return redirect()->route('pendaftaran.all');
            } else {
                Alert::error('Pemeriksaan gagal', 'Data gagal di buat, silahkan isi kembali data sesuai!');
                return redirect()->back();
            }
        } elseif ($request->id_obat[0] != null && !empty($request->file('gambar'))) {

            $total = [];
            $id_obat = $request->id_obat;
            for ($i = 0; $i < count($id_obat); $i++) {
                $i - 1;
                $datasave = [
                    'data_obat' => $id_obat,
                ];
                $req = $request->all();
                $data = Obat::whereIn('id', $id_obat)->get();
                $total_harga = ['total_harga' => $data[$i]->harga_jual * $request->jumlah[$i]];
                array_push($total, $total_harga);
            }
            $value = array_sum(array_column($total, 'total_harga'));
            $makeresep = Resep::create([
                'nama_resep' => $request->nama_resep,
                'total_harga' => $value,
                'foto_resep' => $request->file('gambar')->store('uploads/resep'),
                'id_pendaftaran' => $request->id_pendaftaran,
            ]);
            if ($makeresep) {
                $id_obat = $request->id_obat;
                $quantity = $request->jumlah;
                for ($i = 0; $i < count($id_obat); $i++) {
                    $i - 1;
                    $datas = Obat::whereIn('id', $id_obat)->get();
                    $data = [
                        'id_resep' => $makeresep->id,
                        'id_obat' => $id_obat[$i],
                        'jumlah' => $quantity[$i],
                        'harga' => $datas[$i]->harga_jual,
                    ];
                    DB::table('detail_resep')->insert($data);
                }
            }
            $data = $request->all();
            $data['id_resep'] = $makeresep->id;
            $pendaftaran = Pendaftaran::where('id', $request->id_pendaftaran)->first();
            $data['id_pasien'] = $pendaftaran->id_pasien;
            $data['id_dokter'] = Auth::id();
            $create = Periksa::create($data);
            if ($create) {
                $ubah = Pendaftaran::where('id', $request->id_pendaftaran)->first();
                $update = $ubah->update([
                    'status_periksa' => 1,
                ]);
                if ($request->annotation_data != null) {
                    $pasien = Pasien::where('id', $ubah->id_pasien)->first();
                    $pasien->update([
                        'annotation_data' => $request->annotation_data,
                    ]);
                }
                if (!empty($request->file('files')) && !empty($request->file_description)) {
                    $datafiles['files'] = $request->file('files')->store('uploads/files');
                    $datafiles['description'] = $request->file_description;
                    $datafiles['id_pasien'] = $pasien->id;
                    $datafiles['id_user'] = Auth::id();
                    $datafiles['id_periksa'] = $create->id;
                    // dd($datafiles);
                    Files::create($datafiles);
                } elseif (empty($request->file('files')) && !empty($request->file_description)) {
                    Alert::error('Tidak bisa', 'Jika file pendukung tidak memiliki dokumen atau apapun yang lain, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                    $dataradiology['files'] = $request->file('radiology_files')->store('uploads/radiology_files');
                    $dataradiology['description'] = $request->radiology_description;
                    $dataradiology['id_pasien'] = $pasien->id;
                    $dataradiology['id_user'] = Auth::id();
                    $dataradiology['id_periksa'] = $create->id;
                    RadiologyReports::create($dataradiology);
                } elseif (empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                    Alert::error('Tidak bisa', 'Jika tidak ada file mengenai laporan radiologi, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->file('investigation_files')) && !empty($request->investigation_description)) {
                    $datainvestigation['files'] = $request->file('investigation_files')->store('uploads/investigation_files');
                    $datainvestigation['description'] = $request->investigation_description;
                    $datainvestigation['id_pasien'] = $pasien->id;
                    $datainvestigation['id_user'] = Auth::id();
                    $datainvestigation['id_periksa'] = $create->id;
                    InvestigationReports::create($datainvestigation);
                } elseif (empty($request->file('investigation_files')) && !empty($request->investigation_description)) {
                    Alert::error('Tidak bisa', 'Jika tidak ada file mengenai laporan radiologi, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->surgery_name)) {
                    $surgery = Surgery::create([
                        'surgery_name' => $request->surgery_name,
                        'surgery_detail' => $request->surgery_detail,
                        'id_pasien' => $ubah->id_pasien,
                        'id_periksa' => $create->id,
                        'id_user' => Auth::id(),
                        'status' => 2,
                    ]);
                }
                $cppt = CPPT::create([
                    'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                    'status' => $request->status,
                    'id_user' => Auth::id(),
                    'id_periksa' => $create->id,
                    'id_pasien' => $ubah->id_pasien,
                ]);
                if ($cppt) {
                    $periksa = Periksa::where('id', $cppt->id_periksa)->first();
                    $periksa->update([
                        'id_cppt' => $cppt->id,
                    ]);
                }
                Alert::success('Pemeriksaan Berhasil', 'Pemeriksaan Berhasil');
                return redirect()->route('pendaftaran.all');
            } else {
                Alert::error('Pemeriksaan gagal', 'Data gagal di buat, silahkan isi kembali data sesuai!');
                return redirect()->back();
            }
        } elseif ($request->id_obat[0] == null && empty($request->file('gambar'))) {
            $data = $request->all();
            $makeresep = Resep::create([
                'nama_resep' => $request->nama_resep,
                'id_pendaftaran' => $request->id_pendaftaran,
                'total_harga' => 0,
            ]);
            $data['id_resep'] = $makeresep->id;
            $pendaftaran = Pendaftaran::where('id', $request->id_pendaftaran)->first();
            $data['id_pasien'] = $pendaftaran->id_pasien;
            $data['id_dokter'] = Auth::id();
            $create = Periksa::create($data);
            if ($create) {
                $ubah = Pendaftaran::where('id', $request->id_pendaftaran)->first();
                $update = $ubah->update([
                    'status_periksa' => 1,
                ]);
                if ($request->annotation_data != null) {
                    $pasien = Pasien::where('id', $ubah->id_pasien)->first();
                    $pasien->update([
                        'annotation_data' => $request->annotation_data,
                    ]);
                }
                if (!empty($request->file('files')) && !empty($request->file_description)) {
                    $datafiles['files'] = $request->file('files')->store('uploads/files');
                    $datafiles['description'] = $request->file_description;
                    $datafiles['id_pasien'] = $pasien->id;
                    $datafiles['id_user'] = Auth::id();
                    $datafiles['id_periksa'] = $create->id;
                    // dd($datafiles);
                    Files::create($datafiles);
                } elseif (empty($request->file('files')) && !empty($request->file_description)) {
                    Alert::error('Tidak bisa', 'Jika file pendukung tidak memiliki dokumen atau apapun yang lain, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                    $dataradiology['files'] = $request->file('radiology_files')->store('uploads/radiology_files');
                    $dataradiology['description'] = $request->radiology_description;
                    $dataradiology['id_pasien'] = $pasien->id;
                    $dataradiology['id_user'] = Auth::id();
                    $dataradiology['id_periksa'] = $create->id;
                    RadiologyReports::create($dataradiology);
                } elseif (empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                    Alert::error('Tidak bisa', 'Jika tidak ada file mengenai laporan radiologi, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->file('investigation_files')) && !empty($request->investigation_description)) {
                    $datainvestigation['files'] = $request->file('investigation_files')->store('uploads/investigation_files');
                    $datainvestigation['description'] = $request->investigation_description;
                    $datainvestigation['id_pasien'] = $pasien->id;
                    $datainvestigation['id_user'] = Auth::id();
                    $datainvestigation['id_periksa'] = $create->id;
                    InvestigationReports::create($datainvestigation);
                } elseif (empty($request->file('investigation_files')) && !empty($request->investigation_description)) {
                    Alert::error('Tidak bisa', 'Jika tidak ada file mengenai laporan radiologi, maka tidak dapat di masukkan deskripsi / keterangan');
                    return redirect()->route('periksa.create', $ubah->id);
                }
                if (!empty($request->surgery_name)) {
                    $surgery = Surgery::create([
                        'surgery_name' => $request->surgery_name,
                        'surgery_detail' => $request->surgery_detail,
                        'id_pasien' => $ubah->id_pasien,
                        'id_periksa' => $create->id,
                        'id_user' => Auth::id(),
                        'status' => 2,
                    ]);
                }
                $cppt = CPPT::create([
                    'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                    'status' => $request->status,
                    'id_user' => Auth::id(),
                    'id_periksa' => $create->id,
                    'id_pasien' => $ubah->id_pasien,
                ]);
                if ($cppt) {
                    $periksa = Periksa::where('id', $cppt->id_periksa)->first();
                    $periksa->update([
                        'id_cppt' => $cppt->id,
                    ]);
                }
                Alert::success('Pemeriksaan Berhasil', 'Pemeriksaan Berhasil');
                return redirect()->route('pendaftaran.all');
            } else {
                Alert::error('Pemeriksaan gagal', 'Data gagal di buat, silahkan isi kembali data sesuai!');
                return redirect()->back();
            }
        } else {
            Alert::error('Pemeriksaan gagal', 'Data gagal di buat, silahkan isi kembali data sesuai!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $periksa = Periksa::where('id', $id)->first();
        $files = Files::where('id_periksa', $periksa->id)->first();
        $radiology = RadiologyReports::where('id_periksa', $periksa->id)->first();
        $investigation = InvestigationReports::where('id_periksa', $periksa->id)->first();
        $cppt = CPPT::where('id_periksa', $periksa->id)->first();
        $pendaftaran = Pendaftaran::where('id', $periksa->id_pendaftaran)->first();
        $pasien = Pasien::where('id', $periksa->id_pasien)->first();
        $poli = Poli::where('id', $pendaftaran->id_poli)->first()->pluck('nama_poli');
        return view('page.admin.periksa.edit', compact('periksa', 'poli', 'pendaftaran', 'pasien', 'files', 'radiology', 'investigation', 'cppt'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $periksa = Periksa::where('id', $id)->first();
        $pendaftaran = Pendaftaran::where('id', $periksa->id_pendaftaran)->first();

        $data = $request->all();
        if ($request->annotation_data != null) {
            $pasien = Pasien::where('id', $periksa->id_pasien)->first();
            $pasien->update([
                'annotation_data' => $request->annotation_data,
            ]);
            $periksa = Periksa::where('id', $periksa->id)->first();
            $periksa->update([
                'annotation_data' => $request->annotation_data,
            ]);
        }
        $files_id = Files::where('id_periksa', $periksa->id)->first();
        if ($files_id != null) {
            if (!empty($request->file('files')) && !empty($request->file_description)) {
                // dd($datafiles);
                $files_update = Files::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('files')->store('uploads/files'),
                    'description' => $request->file_description,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } elseif (empty($request->file('files')) && !empty($request->file_description)) {
                $files_update = Files::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'description' => $request->file_description,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } else {
                $files_update = Files::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('files')->store('uploads/files'),
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            }
        } else {
            $pasien = Pasien::where('id', $periksa->id_pasien)->first();
            if (!empty($request->file('files')) && !empty($request->file_description)) {
                // $datafiles = $request->all();
                $datafiles['files'] = $request->file('files')->store('uploads/files');
                $datafiles['description'] = $request->file_description;
                $datafiles['id_pasien'] = $pasien->id;
                $datafiles['id_user'] = $pendaftaran->id_dokter;
                $datafiles['id_periksa'] = $periksa->id;
                // dd($datafiles);
                $files_update = Files::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('files')->store('uploads/files'),
                    'description' => $request->file_description,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } elseif (empty($request->file('files')) && !empty($request->file_description)) {
                Alert::error('Tidak bisa', 'Jika file pendukung tidak memiliki dokumen atau apapun yang lain, maka tidak dapat di masukkan deskripsi / keterangan');
                return redirect()->route('periksa.edit', $periksa->id);
            }
        }
        $radiology_id = RadiologyReports::where('id_periksa', $periksa->id)->first();
        if ($radiology_id != null) {
            if (!empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                // dd($datafiles);
                $files_update = RadiologyReports::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('radiology_files')->store('uploads/files'),
                    'description' => $request->radiology_description,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } elseif (empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                $files_update = RadiologyReports::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'description' => $request->radiology_description,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } else {
                $files_update = RadiologyReports::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('radiology_files')->store('uploads/files'),
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            }
        } else {
            if (!empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                // $datafiles = $request->all();
                $datafiles['files'] = $request->file('radiology_files')->store('uploads/files');
                $datafiles['description'] = $request->radiology_description;
                $datafiles['id_pasien'] = $pasien->id;
                $datafiles['id_user'] = $pendaftaran->id_dokter;
                $datafiles['id_periksa'] = $periksa->id;
                // dd($datafiles);
                $files_update = RadiologyReports::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('radiology_files')->store('uploads/files'),
                    'description' => $request->radiology_description,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } elseif (empty($request->file('radiology_files')) && !empty($request->radiology_description)) {
                Alert::error('Tidak bisa', 'Jika file pendukung tidak memiliki dokumen atau apapun yang lain, maka tidak dapat di masukkan deskripsi / keterangan');
                return redirect()->route('periksa.edit', $periksa->id_periksa);
            }
        }
        $investigation_id = InvestigationReports::where('id_periksa', $periksa->id)->first();
        if ($investigation_id != null) {
            if (!empty($request->file('investigation_files')) && !empty($request->investigation_reports)) {
                // dd($datafiles);
                $files_update = InvestigationReports::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('investigation_files')->store('uploads/files'),
                    'description' => $request->investigation_reports,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } elseif (empty($request->file('investigation_files')) && !empty($request->investigation_reports)) {
                $files_update = InvestigationReports::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'description' => $request->investigation_reports,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } else {
                $files_update = InvestigationReports::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('investigation_files')->store('uploads/files'),
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            }
        } else {
            if (!empty($request->file('investigation_files')) && !empty($request->investigation_reports)) {
                // $datafiles = $request->all();
                $datafiles['files'] = $request->file('investigation_files')->store('uploads/files');
                $datafiles['description'] = $request->investigation_reports;
                $datafiles['id_pasien'] = $pasien->id;
                $datafiles['id_user'] = $pendaftaran->id_dokter;
                $datafiles['id_periksa'] = $periksa->id;
                // dd($datafiles);
                $files_update = InvestigationReports::where('id_periksa', $periksa->id)->first();
                $files_update->update([
                    'files' => $request->file('investigation_files')->store('uploads/files'),
                    'description' => $request->investigation_reports,
                    'id_pasien' => $pasien->id,
                    'id_user' => $pendaftaran->id_dokter,
                    'id_periksa' => $periksa->id,
                ]);
            } elseif (empty($request->file('investigation_files')) && !empty($request->investigation_reports)) {
                Alert::error('Tidak bisa', 'Jika file pendukung tidak memiliki dokumen atau apapun yang lain, maka tidak dapat di masukkan deskripsi / keterangan');
                return redirect()->route('periksa.edit', $periksa->id_periksa);
            }
        }
        if (!empty($request->surgery_name)) {
            $surgery = Surgery::update([
                'surgery_name' => $request->surgery_name,
                'surgery_detail' => $request->surgery_detail,
                'id_pasien' => $periksa->id_pasien,
                'id_periksa' => $periksa->id,
                'id_user' => $pendaftaran->id_dokter,
                'status' => 2,
            ]);
        }
        $cppt_update = CPPT::where('id_periksa', $periksa->id)->first();
        $cppt = $cppt_update->update([
            'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
            'status' => $request->status,
            'id_user' => $pendaftaran->id_dokter,
            'id_periksa' => $periksa->id,
            'id_pasien' => $pasien->id,
        ]);
        if ($cppt) {
            $periksa = Periksa::where('id', $periksa->id)->first();
            $periksa->update([
                'id_cppt' => $cppt_update->id,
            ]);
        }
        Alert::success('Edit Pemeriksaan Berhasil', 'Edit Pemeriksaan Berhasil');
        return redirect()->route('pasien.show', $periksa->id_pendaftaran);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $periksa = Periksa::where('id', $id)->first();

        $delete = $periksa->delete();

        if ($delete) {
            Alert::success('Hapus Berhasil', 'Data berhasil di hapus');
            return redirect()->route('periksa.index');
        } else {
            Alert::error('Hapus gagal', 'Data gagal di hapus');
            return redirect()->route('periksa.index');
        }
    }
}
