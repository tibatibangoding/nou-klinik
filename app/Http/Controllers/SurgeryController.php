<?php

namespace App\Http\Controllers;

use App\Models\Surgery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SurgeryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surgery = Surgery::where('id_user', Auth::id())->get();
        return view('page.admin.surgery.index', compact('surgery'));
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
        $surgery = Surgery::where('id', $id)->first();
        return view('page.admin.surgery.detail', compact('surgery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surgery = Surgery::where('id', $id)->first();
        return view('page.admin.surgery.edit', compact('surgery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $surgery = Surgery::where('id', $id)->first();
        $update = $surgery->update([
            'surgery_name' => $request->surgery_name,
            'surgery_details' => $request->surgery_details,
            'id_pasien' => $surgery->id_pasien,
            'id_user' => Auth::id(),
            'status' => $request->status,
        ]);

        if ($update) {
            Alert::success('Berhasil', 'Data operasi berhasil di perbarui');
            return redirect()->route('surgery.index');
        } else {
            Alert::success('Gagal', 'Data operasi gagal di perbarui');
            return redirect()->route('surgery.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $surgery = Surgery::where('id', $id)->first();
        $delete = $surgery->delete();
        if ($delete) {
            Alert::success('Berhasil', 'Data operasi berhasil di hapus');
            return redirect()->route('surgery.index');
        } else {
            Alert::success('Gagal', 'Data operasi gagal di hapus');
            return redirect()->route('surgery.index');
        }
    }
}
