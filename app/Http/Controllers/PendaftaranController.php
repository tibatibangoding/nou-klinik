<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use RealRashid\SweetAlert\Facades\Alert;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $pendaftaran = Pendaftaran::where('id_pasien', $id)->first();
        return view('page.admin.pendaftaran.index', compact('pendaftaran'));
    }

    public function all()
    {
        if (Auth::user()->roles->pluck('name')[0] == 'dokter') {
            $pendaftaran = Pendaftaran::where('id_dokter', Auth::id())->get();
        } else {
            $pendaftaran = Pendaftaran::all();
        }

        return view('page.admin.pendaftaran.all', compact('pendaftaran'));
    }

    public function dataTablesPendaftaranAll(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->roles->pluck('name')[0] == 'dokter') {
                $pendaftaran = Pendaftaran::with(['poli', 'dokter', 'pasien'])->where('id_dokter', Auth::id())->get();
            } else {
                $pendaftaran = Pendaftaran::with(['poli', 'dokter', 'pasien'])->get();
            }

            // dd(response()->json(['penjualan' => $poli]));

            return response()->json([
                'pendaftaran' => $pendaftaran
            ]);
        } else {
            abort(403);
        }
    }

    public function cekantrian()
    {
        $didalam = Pendaftaran::with(['poli', 'dokter'])->where('status_periksa', 2)->where('created_at', 'LIKE', '%' . Carbon::today()->toDateString(). '%')->get();
        $belum = Pendaftaran::with(['poli', 'dokter'])->where('status_periksa', 3)->where('created_at', 'LIKE', '%' . Carbon::today()->toDateString(). '%')->get();
        $selesai = Pendaftaran::with(['poli', 'dokter'])->where('status_periksa', 1)->where('created_at', 'LIKE', '%' . Carbon::today()->toDateString(). '%')->get();
        return response()->json(['didalam' => $didalam, 'belum' => $belum, 'selesai' => $selesai]);
    }

    public function live()
    {
        return view('page.admin.pendaftaran.livecount');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pasien = Pasien::all();
        $poli = Poli::all();
        $dokter = User::whereHas("roles", function ($q) {
            $q->where("name", "dokter");
        })->get();
        return view('page.admin.pendaftaran.create', compact('pasien', 'poli', 'dokter'));
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
        $cek = Pasien::where('nama_pasien', $request->nama_pasien)->first();
        $dokter = User::where('id', $request->id_dokter)->first();
        $poli = Poli::where('nama_poli', 'LIKE', '%' . $dokter->spesialis . '%')->first();
        $count = Pendaftaran::where('created_at', 'LIKE', '%' . Carbon::now()->toDateString() . '%')->count();
        if ($cek == null) {
            $create = Pasien::create([
                'nama_pasien' => $request->nama_pasien,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'tgl_lahir' => $request->tgl_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pekerjaan' => $request->pekerjaan,
                'agama' => $request->agama,
                'alergi' => $request->alergi,
                'kewarganegaraan' => $request->kewarganegaraan,
            ]);

            if ($create) {
                if ($count < 10) {
                    $no_antrian = '00' . $count + 1 . 'A';
                } elseif($count >= 10 && $count < 100) {
                    $no_antrian = '0' . $count + 1 . 'A';
                } elseif($count >= 100) {
                    $no_antrian = $count + 1 . 'A';
                }
                $make = Pendaftaran::create([
                    'id_pasien' => $create->id,
                    'id_poli' => $poli->id,
                    'id_dokter' => $request->id_dokter,
                    'keluhan' => $request->keluhan,
                    'no_antrian' => $no_antrian,
                    'status_periksa' => 3,
                    'status_bayar' => 2,
                ]);
                if ($make) {
                    Alert::success('Success', 'Pendaftaran anda berhasil!');
                    return redirect()->route('pendaftaran.all');
                } else {
                    Alert::error('Error', 'Pendaftaran anda tidak berhasil!');
                    return redirect()->route('pendaftaran.all');
                }
            } else {
                Alert::error('Error', 'Pendaftaran anda tidak berhasil!');
                return redirect()->route('pendaftaran.all');
            }
        } else {
            // dd($request->all());
            $pasien = Pasien::where('id', $request->id_pasien)->first();
            $update = $pasien->update([
                'nama_pasien' => $request->nama_pasien,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'tgl_lahir' => $request->tgl_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pekerjaan' => $request->pekerjaan,
                'agama' => $request->agama,
                'alergi' => $request->alergi,
                'kewarganegaraan' => $request->kewarganegaraan,
            ]);
            if ($update) {
                if ($count < 10) {
                    $no_antrian = '00' . $count + 1 . 'A';
                } elseif($count >= 10 && $count < 100) {
                    $no_antrian = '0' . $count + 1 . 'A';
                } elseif($count >= 100) {
                    $no_antrian = $count + 1 . 'A';
                }
                $make = Pendaftaran::create([
                    'id_pasien' => $cek->id,
                    'id_poli' => $poli->id,
                    'id_dokter' => $request->id_dokter,
                    'keluhan' => $request->keluhan,
                    'no_antrian' => $no_antrian,
                    'status_periksa' => 3,
                    'status_bayar' => 2,
                ]);
                if ($make) {
                    Alert::success('Success', 'Pendaftaran anda berhasil!');
                    return redirect()->route('pendaftaran.all');
                } else {
                    Alert::error('Error', 'Pendaftaran anda tidak berhasil!');
                    return redirect()->route('pendaftaran.all');
                }
            } else {
                Alert::error('Error', 'Pendaftaran anda tidak berhasil!');
                return redirect()->route('pendaftaran.all');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
