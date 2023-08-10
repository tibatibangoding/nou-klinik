<?php

namespace App\Http\Controllers;

use App\Imports\ObatImport;
use App\Models\Obat;
use App\Models\Poli;
use App\Models\JenisObat;
use App\Models\KategoriObat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obat = Obat::all();
        return view('page.admin.obat.index', compact('obat'));
    }

    public function dataTablesObat(Request $request)
    {
        if ($request->ajax()) {
            if ($request->input('start_date') && $request->input('end_date')) {

                $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
                $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
                DB::enableQueryLog();
                if ($end_date->greaterThan($start_date) || $end_date->equalTo($start_date)) {
                    $obat = Obat::with(['kategori', 'jenis'])->whereBetween('updated_at', [$start_date, $end_date])->get();
                    // dd(DB::getQueryLog());
                } else {
                    $obat = Obat::with(['kategori', 'jenis'])->latest()->get();
                }
            } else {
                $obat = Obat::with(['kategori', 'jenis'])->latest()->get();
            }
            return response()->json([
                'obat' => $obat
            ]);
        } else {
            abort(403);
        }
    }

    public function importExcel(Request $request)
    {
        $import = Excel::import(new ObatImport, $request->file('excel_file'));
        if ($import) {
            Alert::success('Import Obat berhasil', 'import obat sukses');
            return redirect()->back();
        } else {
            Alert::error('Import Obat gagal', 'import obat gagal, silahkan cek kembali');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = KategoriObat::all();
        $jenis_obat = JenisObat::all();
        return view('page.admin.obat.create', compact('jenis_obat', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!empty($request->file('gambar'))) {
            $data = $request->all();
            $data['foto_bukti'] = $request->file('gambar')->store('/uploads/foto_pendukung');
            $create = Obat::create($data);

            if ($create) {
                Alert::success('Pembuatan Berhasil', 'Data berhasil di buat');
                return redirect()->route('obat.index');
            } else {
                Alert::error('Pembuatan gagal', 'Data gagal di buat');
                return redirect()->route('obat.index');
            }
        } else {
            $data = $request->all();
            $create = Obat::create($data);

            if ($create) {
                Alert::success('Pembuatan Berhasil', 'Data berhasil di buat');
                return redirect()->route('obat.index');
            } else {
                Alert::error('Pembuatan gagal', 'Data gagal di buat');
                return redirect()->route('obat.index');
            }
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
        $obat = Obat::where('id', $id)->first();
        $jenis_obat = JenisObat::all();
        $kategori = KategoriObat::all();
        return view('page.admin.obat.edit', compact('obat', 'jenis_obat', 'kategori'));
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
        $obat = Obat::where('id', $id)->first();

        if ($request->file('gambar') != null) {
            $update = $obat->update([
                'nama_obat' => $request->nama_obat,
                'id_jenis_obat' => $request->id_jenis_obat,
                'kategori' => $request->kategori,
                'foto_bukti' => $request->file('gambar')->store('/uploads/foto_pendukung'),
                'harga_jual' => $request->harga_jual,
                'harga_beli' => $request->harga_beli,
                'stok' => $request->stok,
            ]);
        } else {
            $update = $obat->update([
                'nama_obat' => $request->nama_obat,
                'id_jenis_obat' => $request->id_jenis_obat,
                'kategori' => $request->kategori,
                'harga_jual' => $request->harga_jual,
                'harga_beli' => $request->harga_beli,
                'stok' => $request->stok,
            ]);
        }


        if ($update) {
            Alert::success('Update Berhasil', 'Data berhasil di update');
            return redirect()->route('obat.index');
        } else {
            Alert::error('Update gagal', 'Data gagal di update');
            return redirect()->route('obat.index');
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
        $obat = Obat::where('id', $id)->first();

        $delete = $obat->delete();

        if ($delete) {
            Alert::success('Hapus Berhasil', 'Data berhasil di hapus');
            return redirect()->route('obat.index');
        } else {
            Alert::error('Hapus gagal', 'Data gagal di hapus');
            return redirect()->route('obat.index');
        }
    }
}
