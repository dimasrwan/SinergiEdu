<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\StudentParent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Orang Tua beserta data anak-anaknya.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Cari profil orang tua berdasarkan user ID
        $parent = StudentParent::where('user_id', $user->id)->first();

        // Ambil anak-anak dari orang tua tersebut beserta data user & kelasnya
        $children = $parent 
            ? $parent->students()->with(['user', 'classes'])->get() 
            : collect();

        return view('pages.orangtua.dashboard', compact('children'));
    }
}
