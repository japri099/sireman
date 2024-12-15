<?php

namespace App\Http\Controllers;

use App\Models\Menu; // Model Menu digunakan untuk berinteraksi dengan tabel menu di database
use Illuminate\Http\Request; // Digunakan untuk menangani permintaan HTTP

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai dari parameter query pencarian jika ada
        $searchTerm = $request->input('search');

        // Ambil daftar menu berdasarkan pencarian
        // Logika pencarian didelegasikan ke Model melalui scope
        $menus = Menu::filterBySearch($searchTerm)->get();

        // Tampilkan hasil pencarian pada view 'index'
        return view('index', compact('menus'));
    }
}
