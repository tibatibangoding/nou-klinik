<?php

namespace App\Http\Controllers;

use App\Models\Klinik;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KlinikController extends Controller
{
    public function index()
    {
        $klinik = Klinik::where('id', 1)->first();
        return view('page.admin.klinik.index', compact('klinik'));
    }

    public function klinikUpdate(Request $request)
    {
        $klinik = Klinik::where('id', 1)->first();
        if (!empty($request->file('logo'))) {
            $update = $klinik->update([
                'nama_klinik' => $request->nama_klinik,
                'alamat_klinik' => $request->alamat_klinik,
                'kota' => $request->kota,
                'tlp_klinik' => $request->tlp_klinik,
                'fax_klinik' => $request->fax_klinik,
                'logo' => $request->file('logo')->store('uploads/klinik'),
                'biaya_admin' => $request->biaya_admin,
            ]);
        } else {
            $update = $klinik->update([
                'nama_klinik' => $request->nama_klinik,
                'alamat_klinik' => $request->alamat_klinik,
                'kota' => $request->kota,
                'tlp_klinik' => $request->tlp_klinik,
                'fax_klinik' => $request->fax_klinik,
                'biaya_admin' => $request->biaya_admin,
            ]);
        }


        if ($update) {
            Alert::success('Edit data berhasil', 'edit data klinik berhasil');
            return redirect()->route('dashboard');
        } else {
            Alert::success('Edit data gagal', 'edit data klinik gagal');
            return redirect()->back();
        }
    }
}
