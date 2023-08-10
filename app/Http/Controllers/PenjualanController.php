<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Obat;
// use App\Rules\ProductStockPriceRule;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Penjualan::where('status_bayar', 1)->get());
        if (Auth::user()->roles->pluck('name')[0] == 'admin') {
            $penjualan = Penjualan::all();
        } elseif (Auth::user()->roles->pluck('name')[0] == 'apoteker') {
            $penjualan = Penjualan::where('id_kasir', Auth::id())->get();
        }
        return view('page.admin.penjualan.index', compact('penjualan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $obat = Obat::where('stok', '>=', '1')->get();

        return view('page.admin.penjualan.create', compact('obat'));
    }

    public function fetchObat($id)
    {
        $obat = Obat::where('id', $id)->first();
        return response()->json($obat);
    }

    public function sukses(string $id)
    {
        $penjualan = Penjualan::where('id', $id)->first();
        return view('page.admin.transaksi-sukses', compact('penjualan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $total = [];
        $id_obat = $request->id_obat;
        $quantity = $request->jumlah;
        if (count($id_obat) < 1 || $id_obat[0] == null) {
            // dd($id_obat);
            Alert::error('Pembuatan gagal', 'Silahkan pilih obat terlebih dahulu');
            return redirect()->back();
        } else {
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
            if (count($id_obat) <= 1) {
                $datas = Obat::where('id', $id_obat[0])->first();
                $jumlah = $datas->stok;
                $value = array_sum(array_column($total, 'total_harga'));
                $quantitySingle = $request->jumlah;
                if ($jumlah < $quantitySingle[0]) {
                    Alert::error('Pembuatan gagal', 'Stok obat yang di minta tidak cukup');
                    return redirect()->back();
                } else {
                    $makepenjualan = Penjualan::create([
                        'tgl_penjualan' => Carbon::now()->toDateString(),
                        'total_bayar' => 0,
                        'kembalian' => 0,
                        'id_transaksi' => 'FJ -' . Str::random(10),
                        'status_bayar' => 2,
                        'total_harga' => $value,
                        'id_kasir' => Auth::id(),
                    ]);
                    $data = [
                        'id_penjualan' => $makepenjualan->id,
                        'id_obat' => $id_obat[0],
                        'jumlah' => $quantitySingle[0],
                        'harga' => $datas->harga_jual,
                    ];
                    DB::table('detail_penjualan')->insert($data);
                    // Alert::success('Penjualan berhasil', 'Berhasil di lakukan');
                    return redirect()->route('penjualan-konfirm', $makepenjualan->id);
                }
                
            } else {
                for ($i = 0; $i < count($id_obat); $i++) {
                    $i - 1;
                    $datas = Obat::whereIn('id', $id_obat)->get();
                    $jumlah = $datas[$i]->stok;
                    if ($datas[$i]->stok < $quantity[$i]) {
                        Alert::error('Pembuatan gagal', 'Stok obat yang di minta tidak cukup');
                        return redirect()->back();
                    } else {
                        $value = array_sum(array_column($total, 'total_harga'));
                        $makepenjualan = Penjualan::create([
                            'tgl_penjualan' => Carbon::now()->toDateString(),
                            'total_bayar' => 0,
                            'kembalian' => 0,
                            'id_transaksi' => 'FJ -' . Str::random(10),
                            'status_bayar' => 2,
                            'total_harga' => $value,
                            'id_kasir' => Auth::id(),
                        ]);
                        $data = [
                            'id_penjualan' => $makepenjualan->id,
                            'id_obat' => $id_obat[$i],
                            'jumlah' => $quantity[$i],
                            'harga' => $datas[$i]->harga_jual,
                        ];
                        DB::table('detail_penjualan')->insert($data);  
                        return redirect()->route('penjualan-konfirm', $makepenjualan->id); 
                    }
                }
        }
        
        }
        
        
    }

    public function konfirmasi(string $id)
    {
        $penjualan = Penjualan::where('id', $id)->first();
        return view('page.admin.penjualan.konfirmasi', compact('penjualan'));
    }

    public function konfirmasiUpdate(Request $request, string $id)
    {
        $penjualan = Penjualan::where('id', $id)->first();
        // dd($penjualan);

        if ($request->total_bayar > $penjualan->total_harga) {
            $update = $penjualan->update([
                'total_bayar' => $request->total_bayar,
                'status_bayar' => 1,
                'kembalian' => $request->total_bayar - $penjualan->total_harga,
            ]);
            if ($update) {
                $penjualan_detail = DetailPenjualan::where('id_penjualan', $id)->get()->pluck('id_obat')->toArray();
                // dd(count($penjualan_detail));
                $penjualan_detail_jumlah = DetailPenjualan::where('id_penjualan', $id)->get()->pluck('jumlah')->toArray();
                for ($i = 0; $i < count($penjualan_detail); $i++) {
                    // $i
                    // if (count($penjualan_detail) <= 1) {
                    //     $i + 1;
                    // } else {
                    //     # code...
                    // }

                    // dd($i++);
                    $datas = Obat::whereIn('id', $penjualan_detail)->get();
                    // dd($datas);

                    $jumlah = $datas[$i]->stok;
                    $update_obat = $datas[$i]->update([
                        'stok' => $datas[$i]->stok - $penjualan_detail_jumlah[$i],
                    ]);
                }
                Alert::success('Penjualan Berhasil', 'Data berhasil di buat');
                return redirect()->route('penjualan-sukses', $penjualan->id);
            } else {
                Alert::error('Pembuatan gagal', 'penjualan gagal di buat');
                return redirect()->route('penjualan.index');
            }
        } else {
            Alert::error('Penjualan gagal', 'uang kurang Rp. ' . number_format($penjualan->total_harga - $request->total_bayar));
            return redirect()->back();
        }
    }

    public function invoice(string $id)
    {
        $transaksi = Penjualan::where('id', $id)->first();
        $data['name'] = Auth::user()->nama;
        $data['tanggal_transaksi'] = $transaksi->tgl_penjualan;
        $data['total_harga'] = $transaksi->total_harga;
        $data['total_bayar'] = $transaksi->total_bayar;
        $data['kembalian'] = $transaksi->kembalian;
        $data['nomor_pemesanan'] = $transaksi->id_penjualan;
        $data['item'] = DetailPenjualan::where('id_penjualan', $id)->get();
        // dd($data['item'][0]);
        $pdf = Pdf::loadView('page.admin.penjualan.pdf', $data)->setPaper([0, 0, 277, 477], 'potrait');

        return $pdf->download('invoice.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penjualan = Penjualan::where('id', $id)->first();
        $detail_penjualan = DetailPenjualan::where('id_penjualan', $id)->get();

        return view('page.admin.penjualan.show', compact('penjualan', 'detail_penjualan'));
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
