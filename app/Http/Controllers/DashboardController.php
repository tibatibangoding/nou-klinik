<?php

namespace App\Http\Controllers;

use App\Models\DetailResep;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Penjualan;
use App\Models\Periksa;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // dd(Auth::user());
        // dd(Auth::user()->spesialis);
        $data_pendaftaran = Pendaftaran::where('status_bayar', 1)->get();
        $data_pendaftaran_single = Pendaftaran::where('id_dokter', Auth::id())->where('status_bayar', 1)->get();
        $data_periksa = Periksa::all();
        if (Auth::user()->roles->pluck('name')[0] == 'admin') {
            $data_penjualan = Penjualan::all();
            // dd($data_penjualan);
        } else {
            $data_penjualan = Penjualan::where('id_kasir', Auth::id())->get();
        }

        $data_banyak = DB::table('obat')
            ->leftJoin('detail_penjualan', 'obat.id', '=', 'detail_penjualan.id_obat')
            ->leftJoin('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id')
            ->where('detail_penjualan.jumlah', '>', '0')
            ->selectRaw('obat.id, obat.nama_obat, SUM(detail_penjualan.jumlah) as total')
            ->orderBy('total', 'DESC')
            ->groupBy('obat.id')
            ->take(1)
            ->get();

        $data_sedikit = DB::table('obat')
            ->leftJoin('detail_penjualan', 'obat.id', '=', 'detail_penjualan.id_obat')
            ->leftJoin('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id')
            ->where('detail_penjualan.jumlah', '>', '0')
            ->selectRaw('obat.id, obat.nama_obat, SUM(detail_penjualan.jumlah) as total')
            ->orderBy('total', 'ASC')
            ->groupBy('obat.id')
            ->take(1)
            ->get();
        // dd($data_banyak);

        $data_sudah = Pendaftaran::where('id_dokter', Auth::id())->where('created_at', 'LIKE', '%' . Carbon::today()->toDateString() . '%')->where('status_periksa', 1)->count();
        // dd($data_sudah);
        $data_tunggu = Pendaftaran::where('id_dokter', Auth::id())->where('created_at', 'LIKE', '%' . Carbon::today()->toDateString() . '%')->where('status_periksa', 3)->count();
        // dd(Auth::user()->roles->pluck('name')[0] == 'dokter');

        $penjualan_terbanyak = DB::table('obat')
            ->leftJoin('detail_penjualan', 'obat.id', '=', 'detail_penjualan.id_obat')
            ->leftJoin('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id')
            ->selectRaw('obat.id, obat.nama_obat, SUM(detail_penjualan.jumlah) as total')
            ->groupBy('obat.id')
            ->orderBy('total', 'asc')
            ->get();
        // dd(response()->json(['pendaftaran' => $data_pendaftaran]));
        return view('page.admin.admin', compact('data_pendaftaran', 'data_sudah', 'data_tunggu', 'data_banyak', 'data_sedikit', 'data_pendaftaran_single', 'data_periksa', 'data_penjualan', 'penjualan_terbanyak'));
    }

    public function showDokter(string $id)
    {
        $pendaftaran = Pendaftaran::where('id', $id)->first();
        $pasien = Pasien::where('id', $pendaftaran->id_pasien)->first();
        $periksa = Periksa::where('id_pendaftaran', $pendaftaran->id)->first();
        $resep = Resep::where('id', $periksa->id_resep)->first();
        $detail_resep = DetailResep::where('id_resep', $resep->id)->get();
        return view('page.admin.showapoteker', compact('pendaftaran', 'pasien', 'periksa', 'resep', 'detail_resep'));
    }

    public function dataTablesPasien(Request $request)
    {
        if (Auth::user()->roles->pluck('name')[0] == 'dokter') {
            $pendaftaran = Pendaftaran::where('status_periksa', 1)->where('id_dokter', Auth::user()->id)->get()->pluck('id_pasien')->toArray();
            $pasien = Pasien::whereIn('id', $pendaftaran)->get();
        } else {
            $pendaftaran = Pendaftaran::where('status_periksa', 1)->get()->pluck('id_pasien')->toArray();
            $pasien = Pasien::whereIn('id', $pendaftaran)->get();
        }
        if (Auth::user()->roles->pluck('name')[0] == 'dokter') {
            if ($request->ajax()) {
                $pendaftaran = Pendaftaran::where('status_periksa', 1)->where('id_dokter', Auth::user()->id)->get()->pluck('id_pasien')->toArray();
                $pasien = Pasien::whereIn('id', $pendaftaran)->get();
                // dd(response()->json(['pendaftaran' => $pendaftaran]));

                return response()->json([
                    'pasien' => $pasien
                ]);
            } else {
                abort(403);
            }
        } elseif (Auth::user()->roles->pluck('name')[0] == 'admin') {
            if ($request->ajax()) {

                $pendaftaran = Pendaftaran::where('status_periksa', 1)->get()->pluck('id_pasien')->toArray();
                $pasien = Pasien::whereIn('id', $pendaftaran)->get();
                // dd(response()->json(['pendaftaran' => $pendaftaran]));

                return response()->json([
                    'pasien' => $pasien
                ]);
            } else {
                abort(403);
            }
        }
    }

    public function dataTablesPendaftaran(Request $request)
    {
        if (Auth::user()->roles->pluck('name')[0] == 'admin') {
            if ($request->ajax()) {

                if ($request->input('start_date') && $request->input('end_date')) {

                    $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                    DB::enableQueryLog();
                    if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                        $pendaftaran = DB::table('pendaftaran')
                            ->where('status_bayar', 1)
                            ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                            ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                            ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                            ->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                            ->selectRaw('pendaftaran.*, pasien.nama_pasien as nama_pasien, poli.nama_poli as nama_poli, users.nama as nama_dokter, periksa.id as id_periksa')
                            ->whereBetween('pendaftaran.created_at', [$start_date, $end_date])
                            ->get();
                        // dd(DB::getQueryLog());
                    } else {
                        $pendaftaran = DB::table('pendaftaran')
                            ->where('status_bayar', 1)
                            ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                            ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                            ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                            ->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                            ->selectRaw('pendaftaran.*, pasien.nama_pasien as nama_pasien, poli.nama_poli as nama_poli, users.nama as nama_dokter, periksa.id as id_periksa')
                            ->latest()
                            ->get();
                    }
                } else {
                    $pendaftaran = DB::table('pendaftaran')
                        ->where('status_bayar', 1)
                        ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                        ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                        ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                        ->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                        ->selectRaw('pendaftaran.*, pasien.nama_pasien as nama_pasien, poli.nama_poli as nama_poli, users.nama as nama_dokter, periksa.id as id_periksa')
                        ->latest()
                        ->get();
                    // dd(DB::getQueryLog());
                }
                // dd(response()->json(['pendaftaran' => $pendaftaran]));

                return response()->json([
                    'pendaftaran' => $pendaftaran
                ]);
            } else {
                abort(403);
            }
        } elseif (Auth::user()->roles->pluck('name')[0] == 'dokter') {
            if ($request->ajax()) {

                if ($request->input('start_date') && $request->input('end_date')) {

                    $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                    // dd($end_date);
                    DB::enableQueryLog();
                    if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                        $pendaftaran = DB::table('pendaftaran')
                            ->where('id_dokter', Auth::id())
                            ->where('status_periksa', 1)
                            ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                            ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                            ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                            ->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                            ->selectRaw('pendaftaran.*, pasien.nama_pasien as nama_pasien, poli.nama_poli as nama_poli, users.nama as nama_dokter, periksa.id as id_periksa')
                            ->whereBetween('pendaftaran.created_at', [$start_date, $end_date])
                            ->get();
                        // dd(DB::getQueryLog());
                    } else {
                        $pendaftaran = DB::table('pendaftaran')
                            ->where('id_dokter', Auth::id())
                            ->where('status_periksa', 1)
                            ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                            ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                            ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                            ->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                            ->selectRaw('pendaftaran.*, pasien.nama_pasien as nama_pasien, poli.nama_poli as nama_poli, users.nama as nama_dokter, periksa.id as id_periksa')
                            ->selectRaw('pendaftaran.*',)
                            ->latest()
                            ->get();
                    }
                } else {
                    $pendaftaran = DB::table('pendaftaran')
                        ->where('id_dokter', Auth::id())
                        ->where('status_periksa', 1)
                        ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                        ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                        ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                        ->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                        ->selectRaw('pendaftaran.*, pasien.nama_pasien as nama_pasien, poli.nama_poli as nama_poli, users.nama as nama_dokter, periksa.id as id_periksa')
                        ->selectRaw('pendaftaran.*',)
                        ->latest()
                        ->get();
                }
                // dd(response()->json(['pendaftaran' => $pendaftaran]));

                return response()->json([
                    'pendaftaran' => $pendaftaran
                ]);
            } else {
                abort(403);
            }
        } elseif (Auth::user()->roles->pluck('name')[0] == 'apoteker') {
            if ($request->ajax()) {

                if ($request->input('start_date') && $request->input('end_date')) {

                    $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                    DB::enableQueryLog();
                    if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                        $pendaftaran = Pendaftaran::where('status_periksa', 1)->with(['pasien', 'dokter', 'poli'])->whereBetween('created_at', [$start_date, $end_date])->get();
                        // dd(DB::getQueryLog());
                    } else {
                        $pendaftaran = Pendaftaran::where('status_periksa', 1)->with(['pasien', 'dokter', 'poli'])->latest()->get();
                    }
                } else {
                    $pendaftaran = Pendaftaran::where('status_periksa', 1)->with(['pasien', 'dokter', 'poli'])->latest()->get();
                }
                // dd(response()->json(['pendaftaran' => $pendaftaran]));

                return response()->json([
                    'pendaftaran' => $pendaftaran
                ]);
            } else {
                abort(403);
            }
        }
    }
    public function dataTablesPendaftaranAll(Request $request)
    {
        if (Auth::user()->roles->pluck('name')[0] == 'admin') {
            if ($request->ajax()) {

                if ($request->input('start_date') && $request->input('end_date')) {

                    $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                    DB::enableQueryLog();
                    if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                        $pendaftaran = Pendaftaran::with(['pasien', 'dokter', 'poli'])->whereBetween('created_at', [$start_date, $end_date])->get();
                        // dd(DB::getQueryLog());
                    } else {
                        $pendaftaran = Pendaftaran::with(['pasien', 'dokter', 'poli'])->latest()->get();
                    }
                } else {
                    $pendaftaran = Pendaftaran::with(['pasien', 'dokter', 'poli'])->latest()->get();
                    // dd(DB::getQueryLog());
                }
                // dd(response()->json(['pendaftaran' => $pendaftaran]));

                return response()->json([
                    'pendaftaran' => $pendaftaran
                ]);
            } else {
                abort(403);
            }
        } elseif (Auth::user()->roles->pluck('name')[0] == 'dokter') {
            if ($request->ajax()) {

                if ($request->input('start_date') && $request->input('end_date')) {

                    $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                    DB::enableQueryLog();
                    if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                        $pendaftaran = Pendaftaran::where('id_dokter', Auth::id())->with(['pasien', 'dokter', 'poli'])->whereBetween('created_at', [$start_date, $end_date])->get();
                        // dd(DB::getQueryLog());
                    } else {
                        $pendaftaran = Pendaftaran::where('id_dokter', Auth::id())->with(['pasien', 'dokter', 'poli'])->latest()->get();
                    }
                } else {
                    $pendaftaran = Pendaftaran::where('id_dokter', Auth::id())->with(['pasien', 'dokter', 'poli'])->latest()->get();
                }
                // dd(response()->json(['pendaftaran' => $pendaftaran]));

                return response()->json([
                    'pendaftaran' => $pendaftaran
                ]);
            } else {
                abort(403);
            }
        } elseif (Auth::user()->roles->pluck('name')[0] == 'apoteker') {
            if ($request->ajax()) {

                if ($request->input('start_date') && $request->input('end_date')) {

                    $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                    DB::enableQueryLog();
                    if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                        $pendaftaran = Pendaftaran::with(['pasien', 'dokter', 'poli'])->whereBetween('created_at', [$start_date, $end_date])->get();
                        // dd(DB::getQueryLog());
                    } else {
                        $pendaftaran = Pendaftaran::with(['pasien', 'dokter', 'poli'])->latest()->get();
                    }
                } else {
                    $pendaftaran = Pendaftaran::with(['pasien', 'dokter', 'poli'])->latest()->get();
                }
                // dd(response()->json(['pendaftaran' => $pendaftaran]));

                return response()->json([
                    'pendaftaran' => $pendaftaran
                ]);
            } else {
                abort(403);
            }
        }
    }

    public function dataTablesTransaksi(Request $request)
    {
        if ($request->ajax()) {

            if (Auth::user()->roles->pluck('name')[0] == 'admin') {
                if ($request->input('start_date') && $request->input('end_date')) {

                    $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                    DB::enableQueryLog();
                    if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                        $penjualan = Penjualan::where('status_bayar', 1)->whereBetween('created_at', [$start_date, $end_date])->get();
                        // dd(DB::getQueryLog());
                    } else {
                        $penjualan = Penjualan::where('status_bayar', 1)->get();
                    }
                } else {
                    $penjualan = Penjualan::where('status_bayar', 1)->get();
                }
            } else {
                if ($request->input('start_date') && $request->input('end_date')) {

                    $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                    DB::enableQueryLog();
                    if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                        $penjualan = Penjualan::where('status_bayar', 1)->where('id_kasir', Auth::id())->whereBetween('created_at', [$start_date, $end_date])->get();
                        // dd(DB::getQueryLog());
                    } else {
                        $penjualan = Penjualan::where('status_bayar', 1)->where('id_kasir', Auth::id())->latest()->get();
                    }
                } else {
                    $penjualan = Penjualan::where('status_bayar', 1)->where('id_kasir', Auth::id())->latest()->get();
                }
            }

            // dd(response()->json(['penjualan' => $penjualan]));

            return response()->json([
                'penjualan' => $penjualan
            ]);
        } else {
            abort(403);
        }
    }
    public function dataTablesResep(Request $request)
    {
        if ($request->ajax()) {

            if ($request->input('start_date') && $request->input('end_date')) {

                $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                DB::enableQueryLog();
                if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                    $resep = DB::table('resep')
                        ->leftJoin('pendaftaran', 'resep.id_pendaftaran', '=', 'pendaftaran.id')
                        ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                        ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                        ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                        ->leftJoin('pembayaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
                        ->selectRaw('pendaftaran.*, pembayaran.status_bayar as pembayaran, pasien.nama_pasien as nama_pasien, poli.nama_poli, users.nama as nama_dokter, resep.created_at as resep_dibuat, resep.id as id_resep, resep.nama_resep, resep.total_harga')
                        ->whereBetween('resep.created_at', [$start_date, $end_date])
                        ->get();
                    // dd(DB::getQueryLog());
                } else {
                    $resep = DB::table('resep')
                        ->leftJoin('pendaftaran', 'resep.id_pendaftaran', '=', 'pendaftaran.id')
                        ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                        ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                        ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                        ->leftJoin('pembayaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
                        ->selectRaw('pendaftaran.*, pembayaran.status_bayar as pembayaran, pasien.nama_pasien as nama_pasien, resep.created_at as resep_dibuat, poli.nama_poli, users.nama as nama_dokter, resep.id as id_resep, resep.nama_resep, resep.total_harga')
                        ->latest()
                        ->get();
                }
            } else {
                $resep = DB::table('resep')
                    ->leftJoin('pendaftaran', 'resep.id_pendaftaran', '=', 'pendaftaran.id')
                    ->leftJoin('poli', 'poli.id', '=', 'pendaftaran.id_poli')
                    ->leftJoin('users', 'users.id', '=', 'pendaftaran.id_dokter')
                    ->leftJoin('pasien', 'pasien.id', '=', 'pendaftaran.id_pasien')
                    ->leftJoin('pembayaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
                    ->selectRaw('pendaftaran.*, pembayaran.status_bayar as pembayaran, pasien.nama_pasien as nama_pasien, poli.nama_poli, resep.created_at as resep_dibuat, users.nama as nama_dokter, resep.id as id_resep, resep.nama_resep, resep.total_harga')
                    ->latest()
                    ->get();
            }

            // dd(response()->json(['penjualan' => $penjualan]));

            return response()->json([
                'resep' => $resep
            ]);
        } else {
            abort(403);
        }
    }

    public function showApoteker(string $id)
    {
        $pendaftaran = Pendaftaran::where('id', $id)->first();
        $pasien = Pasien::where('id', $pendaftaran->id_pasien)->first();
        $periksa = Periksa::where('id_pendaftaran', $pendaftaran->id)->first();
        $resep = Resep::where('id', $periksa->id_resep)->first();
        $detail_resep = DetailResep::where('id_resep', $resep->id)->get();
        $dokter = User::where('id', $pendaftaran->id_dokter)->first();
        return view('page.admin.showapoteker', compact('pendaftaran', 'pasien', 'periksa', 'resep', 'dokter', 'detail_resep'));
    }
}
