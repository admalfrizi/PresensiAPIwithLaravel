<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data_presensi =  Presensi::select("presensi.*", "users.name")
                                ->join('users', 'presensi.user_id', '=', 'users.id')
                                ->get();
        
        foreach ($data_presensi as $item) {
            $datetime = Carbon::parse($item->tanggal)->locale('id');
            $datetime->settings(['formatFunction' => 'translatedFormat']);

            $item->tanggal = $datetime->format('l, j F Y');
        }
        return view('home',[
            'data_presensi' => $data_presensi
        ]);
    }
}
