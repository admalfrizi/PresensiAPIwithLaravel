<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Auth;
use Carbon\Carbon;
use App\Helpers\ResponseFormatter;
date_default_timezone_set("Asia/Jakarta");


class PresensiController extends Controller
{
    public function getData()
    {
        $data_presensi = Presensi::where('user_id', Auth::user()->id)->get();

        foreach ($data_presensi as $item) {
            if($item->tanggal == date('Y-m-d')) {
                $item->is_hari_ini = true;
            } else {
                $item->is_hari_ini = false;
            }

            $datetime = Carbon::parse($item->tanggal)->locale('id');
            $masuk = Carbon::parse($item->masuk)->locale('id');
            $pulang = Carbon::parse($item->pulang)->locale('id');

            $datetime->settings(['formatFunction' => 'translatedFormat']);
            $masuk->settings(['formatFunction' => 'translatedFormat']);
            $pulang->settings(['formatFunction' => 'translatedFormat']);

            $item->tanggal = $datetime->format('l, j F Y');
            $item->masuk = $masuk->format('H:i');
            $item->pulang = $pulang->format('H:i');
        }

        return ResponseFormatter::success($data_presensi, 'Data Has Been Showed');
    }

    public function saveData(Request $request)
    {
        $keterangan = "";
        $data_presensi = Presensi::whereDate('tanggal', '=', date('Y-m-d'))
                    ->where('user_id', Auth::user()->id)
                    ->first();
        
        if ($data_presensi == null) {
            $data_presensi = Presensi::create([
                'user_id' => Auth::user()->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'tanggal' => date('Y-m-d'),
                'masuk'=> date('H:i:s'),
                'pulang'=> null,
            ]);
        } else {
            $dataPlg = [
                'pulang' => date('H:i:s')
            ];

            Presensi::whereDate('tanggal', '=', date('Y-m-d'))->update($dataPlg);
        }

        $data_presensi = Presensi::whereDate('tanggal', '=', date('Y-m-d'))->first();

        return ResponseFormatter::success([
            'presensiData' => $data_presensi,
        ],'Succeed To Save');

    }
}
