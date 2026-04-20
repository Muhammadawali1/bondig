<?php

namespace App\Http\Controllers\atasan;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $barangs = Barang::query();
        
        // Sembunyikan barang dengan stok 0 untuk atasan
        $barangs->where('stok', '>', 0);
        
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
        
        return view('atasan.barang.index', compact('barangs'));
    }
}
