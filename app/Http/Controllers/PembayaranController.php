<?php

namespace App\Http\Controllers;

use App\Models\CPPT;
use App\Models\DetailResep;
use App\Models\Files;
use App\Models\InvestigationReports;
use App\Models\Klinik;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Penjualan;
use App\Models\Periksa;
use Illuminate\Support\Str;
use App\Models\Poli;
use App\Models\RadiologyReports;
use App\Models\Resep;
use App\Models\Surgery;
use App\Models\Tindakan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $pembayaran = Pembayaran::where('id_pendaftaran', $id)->get();
        $pendaftaran = Pendaftaran::where('id', $id)->first();
        return view('page.admin.pembayaran.index', compact('pembayaran', 'pendaftaran'));
    }

    public function all()
    {
        $pembayaran = Pembayaran::all();
        return view('page.admin.pembayaran.all', compact('pembayaran'));
    }

    public function dataTablesPembayaran(Request $request)
    {
        if ($request->ajax()) {

            if ($request->input('start_date') && $request->input('end_date')) {

                $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                DB::enableQueryLog();
                if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                    $pembayaran = DB::table('pembayaran')->leftJoin('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                        ->whereBetween('pembayaran.created_at', [$start_date, $end_date])
                        ->selectRaw('pembayaran.*, periksa.id as id_periksa, pendaftaran.created_at as tanggal_daftar, pendaftaran.id as id_pendaftaran, pendaftaran.no_antrian')
                        ->get();
                    // dd(DB::getQueryLog());
                } else {
                    $pembayaran = DB::table('pembayaran')->leftJoin('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                        ->selectRaw('pembayaran.*, periksa.id as id_periksa, pendaftaran.created_at as tanggal_daftar, pendaftaran.id as id_pendaftaran, pendaftaran.no_antrian')
                        ->get();
                }
            } else {
                $pembayaran = DB::table('pembayaran')->leftJoin('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')->leftJoin('periksa', 'periksa.id_pendaftaran', '=', 'pendaftaran.id')
                    ->selectRaw('pembayaran.*, periksa.id as id_periksa, pendaftaran.created_at as tanggal_daftar, pendaftaran.id as id_pendaftaran, pendaftaran.no_antrian')
                    ->get();
            }
            // dd(response()->json(['pendaftaran' => $pendaftaran]));

            return response()->json([
                'pembayaran' => $pembayaran
            ]);
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $pembayaran = Pembayaran::all();
        $pendaftaran = Pendaftaran::where('id', $id)->first();
        $periksa = Periksa::where('id_pendaftaran', $id)->first();
        $pasien = Pasien::where('id', $pendaftaran->id_pasien)->first();
        $tindakan = Tindakan::where('id', $periksa->id_tindakan)->first();
        $harga_tindakan = $tindakan->biaya_tindakan;
        $dokter = User::where('id', $pendaftaran->id_dokter)->first();
        $resep = Resep::where('id_pendaftaran', $pendaftaran->id)->first();
        $klinik = Klinik::where('id', 1)->first();
        $biaya_dokter = $dokter->biaya_dokter;
        if ($resep != null) {
            $nama_pembayaran = 'Pembayaran ' . $resep->nama_resep;
            $harga_total = $resep->total_harga;
        } else {
            $nama_pembayaran = 'Pembayaran Pasien' . $pasien->nama_pasien . 'Tanggal ' . $pendaftaran->created_at->toDateString;
            $harga_total = 0;
        }

        $harga_admin = $klinik->biaya_admin;
        $total_biaya = $biaya_dokter + $harga_total + $harga_admin + $harga_tindakan;
        return view('page.admin.pembayaran.create', compact('pembayaran', 'harga_tindakan', 'tindakan', 'nama_pembayaran', 'pendaftaran', 'total_biaya', 'biaya_dokter', 'harga_total', 'harga_admin', 'harga_tindakan'));
    }

    public function sukses(string $id)
    {
        $pembayaran = Pembayaran::where('id', $id)->first();
        return view('page.admin.transaksi-sukses1', compact('pembayaran'));
    }

    public function invoice(string $id)
    {
        $transaksi = Pembayaran::where('id', $id)->first();
        $data['name'] = Auth::user()->nama;
        $data['tanggal_transaksi'] = Carbon::now()->toDateString();
        $data['total_harga'] = $transaksi->biaya_obat;
        $data['total_biaya'] = $transaksi->total_biaya;
        $data['biaya_tindakan'] = $transaksi->biaya_tindakan;
        $data['biaya_dokter'] = $transaksi->biaya_dokter;
        $data['biaya_admin'] = $transaksi->biaya_admin;
        $data['total_bayar'] = $transaksi->total_bayar;
        $data['kembalian'] = $transaksi->kembalian;
        $data['id_transaksi'] = $transaksi->id_pembayaran;
        $data['item'] = DetailResep::where('id_resep', $transaksi->id_resep)->get();
        $pdf = Pdf::loadView('page.admin.pembayaran.invoicepembayaran', $data)->setPaper([0, 0, 277, 477], 'potrait');
        return $pdf->download('invoice.pdf');
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
        $pendaftaran = Pendaftaran::where('id', $request->id_pendaftaran)->first();
        $dokter = User::where('id', $pendaftaran->id_dokter)->first();
        $resep = Resep::where('id_pendaftaran', $pendaftaran->id)->first();
        $id_obat = DetailResep::where('id_resep', $resep->id)->get()->pluck('id_obat')->toArray();
        // dd($id_obat);
        $quantity = DetailResep::where('id_resep', $resep->id)->get()->pluck('jumlah')->toArray();
        // dd($quantity);
        $klinik = Klinik::where('id', 1)->first();
        $biaya_dokter = $dokter->biaya_dokter;
        $harga_admin = $klinik->biaya_admin;
        $data['id_pendaftaran'] = $request->id_pendaftaran;
        if ($resep->foto_resep == null && $resep->total_harga == 0) {
            $harga_total = $resep->total_harga;
            $data['biaya_obat'] = 0;
            $data['id_resep'] = $resep->id;
        } else {
            $harga_total = $resep->total_harga;
            $data['biaya_obat'] = $request->biaya_obat;
            $data['id_resep'] = $resep->id;
        }

        $data['id_user'] = Auth::id();
        $data['biaya_admin'] = $request->biaya_admin;
        $data['biaya_dokter'] = $request->biaya_dokter;
        $periksa = Periksa::where('id_pendaftaran', $pendaftaran->id)->first();
        $pasien = Pasien::where('id', $pendaftaran->id_pasien)->first();
        $tindakan = Tindakan::where('id', $periksa->id_tindakan)->first();
        $harga_tindakan = $tindakan->biaya_tindakan;
        if ($resep->foto_resep == null && $resep->total_harga == 0) {
            $total_biaya = $biaya_dokter + $harga_total + $harga_admin + $harga_tindakan;
            $data['total_biaya'] = $biaya_dokter + $harga_total + $harga_admin + $harga_tindakan;
        } else {
            $total_biaya = $biaya_dokter + $harga_total + $harga_admin + $harga_tindakan;
            $data['total_biaya'] = $biaya_dokter + $harga_total + $harga_admin + $harga_tindakan;
        }

        if ($request->total_bayar < $total_biaya && $request->status_bayar == 1) {
            Alert::error('Pembayaran gagal', 'uang kurang');
            return redirect()->route('pembayaran.create', $pendaftaran->id);
        } elseif ($request->total_bayar >= $total_biaya && $request->status_bayar == 1) {
            Alert::error('Pembayaran gagal', 'silahkan cek status yang anda pilih kurang');
            return redirect()->route('pembayaran.create', $pendaftaran->id);
        } elseif ($request->total_bayar < $total_biaya && $request->status_bayar == 2) {
            $data['id_pembayaran'] = 'FS -' . Str::random(10);
            $data['kembalian'] = $request->total_bayar - $total_biaya;
            // dd($data);
            $create = Pembayaran::create($data);
        } else {
            $data['id_pembayaran'] = 'FS -' . Str::random(10);
            $data['kembalian'] = $request->total_bayar - $total_biaya;
            // dd($data);
            $create = Pembayaran::create($data);
        }

        if ($create) {
            if ($resep != null && count($id_obat) >= 1) {
                $total = [];
                if (count($id_obat) <= 1) {
                    $datasave = [
                        'data_obat' => $id_obat[0],
                    ];
                    $req = $request->all();
                    $data = Obat::where('id', $id_obat[0])->first();
                    $total_harga = ['total_harga' => $data->harga_jual * $quantity[0]];
                    array_push($total, $total_harga);
                } else {
                    for ($i = 0; $i < count($id_obat); $i++) {
                        $i - 1;
                        $datasave = [
                            'data_obat' => $id_obat,
                        ];
                        $req = $request->all();
                        $data = Obat::whereIn('id', $id_obat)->get();
                        $total_harga = ['total_harga' => $data[$i]->harga_jual * $quantity[$i]];
                        array_push($total, $total_harga);
                    }
                }

                $value = array_sum(array_column($total, 'total_harga'));
                $makepenjualan = Penjualan::create([
                    'tgl_penjualan' => Carbon::now()->toDateString(),
                    'total_bayar' => $request->biaya_obat,
                    'kembalian' => 0,
                    'id_transaksi' => 'FJ -' . Str::random(10),
                    'total_harga' => $value,
                    'id_kasir' => Auth::id(),
                ]);

                if ($makepenjualan) {
                    if (count($id_obat) <= 1) {
                        $datas = Obat::where('id', $id_obat[0])->first();
                        $jumlah = $datas->stok;
                        $update_obat = $datas->update([
                            'stok' => $datas->stok - $quantity[0],
                        ]);
                        $data = [
                            'id_penjualan' => $makepenjualan->id,
                            'id_obat' => $id_obat[0],
                            'jumlah' => $quantity[0],
                            'harga' => $datas->harga_jual,
                        ];
                        DB::table('detail_penjualan')->insert($data);
                    } else {
                        for ($i = 0; $i < count($id_obat); $i++) {
                            $i - 1;
                            $datas = Obat::whereIn('id', $id_obat)->get();
                            $jumlah = $datas[$i]->stok;
                            $update_obat = $datas[$i]->update([
                                'stok' => $datas[$i]->stok - $quantity[$i],
                            ]);
                            $data = [
                                'id_penjualan' => $makepenjualan->id,
                                'id_obat' => $id_obat[$i],
                                'jumlah' => $quantity[$i],
                                'harga' => $datas[$i]->harga_jual,
                            ];
                            DB::table('detail_penjualan')->insert($data);
                        }
                    }
                }
                $transaksi = Pendaftaran::where('id', $request->id_pendaftaran)->first();
                $ubah = $transaksi->update([
                    'status_bayar' => 1,
                ]);
                Alert::success('Pembuatan Berhasil', 'Data berhasil di buat');
                return redirect()->route('pembayaran-sukses', $create->id);
            } else {
                $transaksi = Pendaftaran::where('id', $request->id_pendaftaran)->first();
                $ubah = $transaksi->update([
                    'status_bayar' => 1,
                ]);
                Alert::success('Pembuatan Berhasil', 'Data berhasil di buat');
                return redirect()->route('pembayaran-sukses', $create->id);
            }
        } else {
            Alert::error('Pembayaran gagal', 'Data gagal di buat');
            return redirect()->route('resep.index');
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
        $periksa = Periksa::where('id_pendaftaran', $id)->first();
        $pasien = Pasien::where('id', $periksa->id_pasien)->first();
        $pembayaran = Pembayaran::where('id_pendaftaran', $id)->first();
        $poli = Poli::where('id', $pasien->id_poli)->first();
        // dd(json_decode($periksa->annotation_data));
        $cppt = CPPT::where('id_periksa', $periksa->id)->get();
        $cppt_single = CPPT::where('id_periksa', $periksa->id)->where('id_user', Auth::id())->get();
        $files = Files::where('id_periksa', $periksa->id)->get();
        $radiology = RadiologyReports::where('id_periksa', $periksa->id)->get();
        $investigation = InvestigationReports::where('id_periksa', $periksa->id)->get();
        $annotation = Periksa::where('id_pendaftaran', $id)->latest('created_at')->first();
        $surgery = Surgery::where('id_periksa', $periksa->id)->get();
        $resep = Resep::where('id', $periksa->id_resep)->first();
        $detail_resep = DetailResep::where('id_resep', $periksa->id_resep)->get();

        return view('page.admin.pembayaran.detail', compact('periksa', 'pembayaran', 'poli', 'cppt', 'cppt_single', 'resep', 'detail_resep', 'pasien', 'files', 'radiology', 'investigation', 'surgery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pembayaran = Pembayaran::where('id')->first();
        $pendaftaran = Pendaftaran::where('id', $pembayaran->id_pendaftaran);
        return view('page.admin.pembayaran.edit', compact('pembayaran', 'pendaftaran'));
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
        $pembayaran = Pembayaran::where('id', $id)->first();

        $update = $pembayaran->update([
            'nama_pembayaran' => $request->nama_pembayaran,
            'biaya' => $request->biaya,
        ]);

        if ($update) {
            Alert::success('Update Berhasil', 'Data berhasil di update');
            return redirect()->route('pembayaran.index');
        } else {
            Alert::error('Update gagal', 'Data gagal di update');
            return redirect()->route('pembayaran.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pembayaran = Pembayaran::where('id', $id)->first();

        $delete = $pembayaran->delete();

        if ($delete) {
            Alert::success('Hapus Berhasil', 'Data berhasil di hapus');
            return redirect()->route('pembayaran.index');
        } else {
            Alert::error('Hapus gagal', 'Data gagal di hapus');
            return redirect()->route('pembayaran.index');
        }
    }
}
