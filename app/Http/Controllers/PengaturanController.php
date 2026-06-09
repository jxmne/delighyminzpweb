<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PengaturanController extends Controller
{
    public function index(Request $request)
    {
        $theme = $request->cookie('theme', 'light');
        $fontSize = $request->cookie('font_size', 'base');

        return view('pengaturan', compact('theme', 'fontSize'));
    }

    public function save(Request $request)
    {
        $theme = $request->input('theme');
        $fontSize = $request->input('font_size');

        // Simpan ke cookie
        Cookie::queue('theme', $theme, 60 * 24 * 7);
        Cookie::queue('font_size', $fontSize, 60 * 24 * 7);

        return response()->json([
            'status' => 'success',
            'message' => 'Preferensi tampilan berhasil diperbarui!',
            'theme' => $theme,
            'font_size' => $fontSize
        ]);
    }
}