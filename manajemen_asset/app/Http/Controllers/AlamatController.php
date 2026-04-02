<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class AlamatController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        return view('alamat.index', compact('provinces'));
    }

    // Tambahkan method ini
    public function getProvinces()
    {
        try {
            $provinces = Province::all();
            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load provinces'], 500);
        }
    }

    public function getCities($provinceCode)
    {
        return City::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get();
    }

    public function getDistricts($cityCode)
    {
        return District::where('city_code', $cityCode)
            ->orderBy('name')
            ->get();
    }

    public function getVillages($districtCode)
    {
        return Village::where('district_code', $districtCode)
            ->orderBy('name')
            ->get();
    }

}