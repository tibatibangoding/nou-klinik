<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Poli;
use App\Models\Tindakan;
use Illuminate\Http\Request;
use App\Imports\TindakanImport;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class TindakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tindakan = Tindakan::all();
        return view('page.admin.tindakan.index', compact('tindakan'));
    }

    public function importExcel(Request $request)
    {
        $import = Excel::import(new TindakanImport, $request->file('excel_file'));
        if ($import) {
            Alert::success('Import tindakan berhasil', 'import tindakan sukses');
            return redirect()->back();
        } else {
            Alert::error('Import tindakan gagal', 'import tindakan gagal, silahkan cek kembali');
            return redirect()->back();
        }
    }

    public function dataTablesTindakan(Request $request)
    {
        if ($request->ajax()) {
            $tindakan = Tindakan::all();

            return response()->json(['tindakan' => $tindakan]);
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tindakan = Tindakan::all();
        return view('page.admin.tindakan.create', compact('tindakan'));
    }

    public function createTindakan()
    {
        $tindakan = Tindakan::all();
        return view('page.admin.tindakan.createTindakan', compact('tindakan'));
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
        $create = Tindakan::create($data);

        if ($create) {
            Alert::success('Pembuatan Berhasil', 'Data berhasil di buat');
            return redirect()->route('tindakan.index');
        } else {
            Alert::error('Pembuatan gagal', 'Data gagal di buat');
            return redirect()->back();
        }
    }

    public function storeTindakan(Request $request)
    {
        $data = $request->all();
        $create = Tindakan::create($data);

        if ($create) {
            Alert::success('Pembuatan Berhasil', 'Data tindakan berhasil di buat');
            return redirect()->route('periksa.create');
        } else {
            Alert::error('Pembuatan gagal', 'Data gagal di buat');
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
        $tindakan = Tindakan::where('id', $id)->first();
        return view('page.admin.tindakan.edit', compact('tindakan'));
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
        $tindakan = Tindakan::where('id', $id)->first();

        $update = $tindakan->update([
            'nama_tindakan' => $request->nama_tindakan,
            'biaya_tindakan' => $request->biaya_tindakan,
        ]);

        if ($update) {
            Alert::success('Update Berhasil', 'Data berhasil di update');
            return redirect()->route('tindakan.index');
        } else {
            Alert::error('Update gagal', 'Data gagal di update');
            return redirect()->route('tindakan.index');
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
        $tindakan = Tindakan::where('id', $id)->first();

        $delete = $tindakan->delete();

        if ($delete) {
            Alert::success('Hapus Berhasil', 'Data berhasil di hapus');
            return redirect()->route('tindakan.index');
        } else {
            Alert::error('Hapus gagal', 'Data gagal di hapus');
            return redirect()->route('tindakan.index');
        }
    }
}
