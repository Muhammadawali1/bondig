<?php

namespace App\Http\Controllers\gudang;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $barangs = Barang::query();
        
        // Filter by category if provided
        if (request()->has('kategori') && request('kategori') !== '') {
            $barangs->where('kategori', request('kategori'));
        }
        
        // Filter by search if provided
        if (request()->has('search') && request('search') !== '') {
            $searchTerm = request('search');
            $barangs->where('nama_barang', 'like', '%' . $searchTerm . '%');
        }
        
        $barangs = $barangs->orderBy('nama_barang')->paginate(50);
        
        return view('gudang.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('gudang.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|in:atk,art,tinta',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ], [
            'stok.min' => 'stok minimal harus 1'
        ]);

        Barang::create($request->all());

        return redirect()->route('gudang.barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        
        return view('gudang.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|in:atk,art,tinta',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('gudang.barang.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('gudang.barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
