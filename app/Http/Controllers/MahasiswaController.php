<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        $mahasiswa = $mahasiswa = DB::table('mahasiswa')->get(); // Mengambil semua isi tabel
        $posts = Mahasiswa::orderBy('Nim', 'desc');
        //menambahkan paginate pada index
        return view('index', [
            'mahasiswa' => DB::table('mahasiswa')->paginate(3)
        ]);

    }
    
    public function cari(\Illuminate\Http\Request $request)
    {
        $mahasiswa = mahasiswa::when($request->keyword, function ($query) use ($request) {
            $query->where('nim', 'like', "%{$request->keyword}%")
                ->orWhere('nama', 'like', "%{$request->keyword}%");
        })->get();
        return view('mahasiswa.detail', compact('mahasiswa'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //melakukan validasi data
            $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
    ]);
        //fungsi eloquent untuk menambah data
            Mahasiswa::create($request->all());

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
            return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
            $Mahasiswa = Mahasiswa::find($Nim);
            return view('detail', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($Nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
            $Mahasiswa = DB::table('mahasiswa')->where('nim', $Nim)->first();;
            return view('edit', compact('Mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //melakukan validasi data
        // $request->validate([
        //'Nim' => 'required',
        //'Nama' => 'required',
        //'Kelas' => 'required',
        //'Jurusan' => 'required',
    //]);
    DB::table('mahasiswa')->where('nim',$request->nim)->update([
        //'nim' => $request->nim,
        'nama' => $request->nama,
		'kelas' => $request->kelas,
		'jurusan' => $request->umur,
	]);
        //fungsi eloquent untuk mengupdate data inputan kita
        //Mahasiswa::find($Nim)->update($request->all());
        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
        ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data
            //Mahasiswa::find($Nim)->delete();
            DB::table('mahasiswa')->where('nim',$Nim)->delete();
            return redirect()->route('mahasiswa.index')
            -> with('success', 'Mahasiswa Berhasil Dihapus');
    }
}