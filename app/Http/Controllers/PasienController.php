<?php

namespace App\Http\Controllers;

use App\Models\CPPT;
use App\Models\DetailResep;
use App\Models\Files;
use App\Models\InvestigationReports;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Periksa;
use App\Models\Poli;
use App\Models\RadiologyReports;
use App\Models\Resep;
use App\Models\Surgery;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasien = Pasien::all();

        return view('page.admin.pasien.index');
    }

    public function all()
    {
        if (Auth::user()->roles->pluck('name')[0] == 'dokter') {
            $pendaftaran = Pendaftaran::where('status_periksa', 1)->where('id_dokter', Auth::user()->id)->get()->pluck('id_pasien')->toArray();
            $pasien = Pasien::whereIn('id', $pendaftaran)->get();
        } else {
            $pendaftaran = Pendaftaran::where('status_periksa', 1)->get()->pluck('id_pasien')->toArray();
            $pasien = Pasien::whereIn('id', $pendaftaran)->get();
        }


        return view('page.admin.pasien.all', compact('pasien'));
    }

    public function rekamMedis($id)
    {
        $pasien = Pasien::where('id', $id)->first();
        return view('page.admin.pasien.rekamMedis', compact('pasien'));
    }

    public function dataTablesRekamMedis(Request $request, $id)
    {
        if ($request->ajax()) {

            if ($request->input('start_date') && $request->input('end_date')) {

                $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                DB::enableQueryLog();
                if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                    $pendaftaran = Pendaftaran::where('id_pasien', $id)->where('status_periksa', 1)->with(['pasien', 'dokter', 'poli', 'pembayaran'])->whereBetween('created_at', [$start_date, $end_date])->get();
                    // dd(DB::getQueryLog());
                } else {
                    $pendaftaran = Pendaftaran::where('id_pasien', $id)->where('status_periksa', 1)->with(['pasien', 'dokter', 'poli', 'pembayaran'])->latest()->get();
                }
            } else {
                $pendaftaran = Pendaftaran::where('id_pasien', $id)->where('status_periksa', 1)->with(['pasien', 'dokter', 'poli', 'pembayaran'])->latest()->get();
            }
            // dd(response()->json(['pendaftaran' => $pendaftaran]));

            return response()->json([
                'pendaftaran' => $pendaftaran
            ]);
        } else {
            abort(403);
        }
    }

    public function apiPasien($id)
    {
        $pasien = Pasien::where('id', $id)->first();

        return response()->json($pasien);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pendaftaran = Pendaftaran::where('id', $id)->first();
        $periksa = Periksa::where('id_pendaftaran', $id)->first();
        $diagnosis = Periksa::where('id_pasien', $periksa->id_pasien)->where('status_diagnosis', 2)->get();
        $diagnosis_single = Periksa::where('id', $periksa->id)->first();
        $tindakan = Periksa::where('id_pasien', $periksa->id_pasien)->where('status_tindakan', 2)->get();
        $tindakan_single = Periksa::where('id', $periksa->id)->first();
        // dd($tindakan_single->user);
        $pasien = Pasien::where('id', $pendaftaran->id_pasien)->first();
        $poli = Poli::where('id', $pendaftaran->id_poli)->first();
        // dd(json_decode($periksa->annotation_data));
        $cppt = CPPT::where('id_pasien', $periksa->id_pasien)->where('status', 2)->get();
        // dd($cppt);
        $cppt_single = CPPT::where('id_periksa', $periksa->id)->first();
        $files = Files::where('id_periksa', $periksa->id)->get();

        $pembayaran = Pembayaran::where('id_pendaftaran', $id)->first();
        $reseps = Resep::where('id', $periksa->id_resep)->first();
        $resep = Resep::where('id', $periksa->id_resep)->first();
        $detail_resep = DetailResep::where('id_resep', $reseps->id)->get();

        $radiology = RadiologyReports::where('id_periksa', $periksa->id)->get();
        $investigation = InvestigationReports::where('id_periksa', $periksa->id)->get();
        $annotation = Periksa::where('id_pendaftaran', $id)->first();
        $surgery = Surgery::where('id_periksa', $periksa->id)->get();
        // dd($periksa->annotation_data);
        return view('page.admin.pasien.detail', compact('pasien', 'pembayaran', 'diagnosis', 'tindakan', 'diagnosis_single', 'tindakan_single', 'pendaftaran', 'cppt', 'resep', 'detail_resep', 'cppt_single', 'poli', 'files', 'radiology', 'investigation', 'annotation', 'surgery', 'periksa'));
    }

    public function showAll(string $id)
    {
        $pasien = Pasien::where('id', $id)->first();
        $poli = Poli::where('id', $pasien->id_poli)->first();
        $periksa = Periksa::where('id_pasien', $id)->latest()->first();
        $diagnosis = Periksa::where('id_pasien', $periksa->id_pasien)->where('status_diagnosis', 2)->get();
        $tindakan = Periksa::where('id_pasien', $periksa->id_pasien)->where('status_tindakan', 2)->get();
        $diagnosis_single = Periksa::where('id_pasien', $periksa->id)->where('id_dokter', Auth::id())->first();
        $tindakan_single = Periksa::where('id_pasien', $periksa->id)->where('id_dokter', Auth::id())->first();
        // dd(json_decode($periksa->annotation_data));
        $cppt = CPPT::where('id_pasien', $id)->where('status', 2)->get();
        $cppt_single = CPPT::where('id_pasien', $id)->where('id_user', Auth::id())->get();
        $files = Files::where('id_pasien', $id)->get();
        $radiology = RadiologyReports::where('id_pasien', $id)->get();
        $investigation = InvestigationReports::where('id_pasien', $id)->get();
        $annotation = Periksa::where('id_pasien', $id)->latest('created_at')->first();
        $surgery = Surgery::where('id_pasien', $id)->get();
        // dd($periksa->annotation_data);
        return view('page.admin.pasien.detailAll', compact('pasien', 'diagnosis_single', 'tindakan_single', 'diagnosis', 'tindakan', 'cppt', 'cppt_single', 'poli', 'files', 'radiology', 'investigation', 'annotation', 'surgery', 'periksa'));
    }

    public function showPasienBelum(string $id)
    {
        $pasien = Pasien::where('id', $id)->first();
        $pendaftaran = Pendaftaran::where('id_pasien', $id)->where('status_periksa', 1)->latest()->first();
        $periksa = Periksa::where('id_pendaftaran', $pendaftaran->id)->first();
        // dd($periksa->annotation_data);
        return view('page.admin.pasien.detailBelum', compact('pasien', 'periksa'));
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
